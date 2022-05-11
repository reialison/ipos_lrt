<?php
function indexPage2(){
	$CI =& get_instance();
	$CI->make->sDiv(array('id'=>'control_panel'));
		
	$CI->make->eDiv();
	return $CI->make->code();
}	
function indexPage($needEod=false,$set,$today=null,$trans_buttons=null){
	$CI =& get_instance();
	$user = $CI->session->userdata('user');
		$CI->make->sDiv(array('id'=>'cashier-panel'));
			$CI->make->sDivRow(array('class'=>'cpanel-top'));
				$CI->make->sDivCol(2,'left',0,array('class'=>'cpanel-left'));
					// $CI->make->H(5,'NEW ORDER',array('class'=>'headline text-center','style'=>'margin-bottom:10px;'));
					// $CI->make->button('DINE IN',array('id'=>'dine-in-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('DELIVERY',array('id'=>'delivery-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('COUNTER',array('id'=>'counter-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('RETAIL',array('id'=>'retail-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('PICKUP',array('id'=>'pickup-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('TAKEOUT',array('id'=>'takeout-btn','class'=>'btn-block cpanel-btn-blue'));
					// $CI->make->button('DRIVE-THRU',array('id'=>'drive-thru-btn','class'=>'btn-block cpanel-btn-blue'));
					$ids = explode(',',$set->controls);
					// $search_opts = array("- SELECT ORDER TYPE -"=>"");
					// echo "<pre>",print_r($ids),"</pre>";die();

					$CI->make->sDiv(array('style'=>'height:440px;overflow:auto;'));


						foreach($ids as $value){
							$text = explode('=>',$value);
							if($text[0] == 1){
								$texts='dine-in';
							}elseif($text[0] == 8){
								$texts='foodpanda';
							}
							// elseif($text[0] == 19){
							// 	$texts='lala';
							// }
							else{
								$texts=$text[1];
							}

							if($texts == 'takeout'){
								$text[1] = TAKEOUTTEXT;
							}

							if($texts == 'dinein' || $texts == 'dine-in' || $texts == 'dine in'){
								$text[1] = DINEINTEXT;
							}


							if($texts == 'counter'){
								$text[1] = COUNTERTEXT;
							}
	// echo $text[1];
							// if($texts == 'to go'){
								
							// 	$CI->make->button(strtoupper($text[1]),array('id'=>'takeout-btn','class'=>'new-order-btns btn-block cpanel-btn-blue'));
							// }else{
							// 	$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-violet'));

							// }
							$new_trans_type = "old_trans_type";
							if(strtolower($text[0]) == $text[1]){
								$new_trans_type = "new_trans_type";
							}
							if(MANANGS){


								if($texts == 'dinein'){
									$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-silver '.$new_trans_type,'ref'=>$texts));
								}elseif($texts == 'takeout'){
									$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-blue '.$new_trans_type,'ref'=>$texts));
								}elseif($texts == 'foodpanda'){
									$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-pink '.$new_trans_type,'ref'=>$texts));
								}elseif($texts == 'grabfood'){
									$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-violet '.$new_trans_type,'ref'=>$texts));
								}else{
									$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-silver '.$new_trans_type,'ref'=>$texts));
								}

							}else{
								$CI->make->button(strtoupper($text[1]),array('id'=>$texts.'-btn','class'=>'new-order-btns btn-block cpanel-btn-blue '.$new_trans_type,'ref'=>$texts));
							}




							$tyy = str_replace(array(" ","-"), "", $text[1]);
							$search_opts[strtoupper($text[1])] = strtolower($tyy);
						}
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(8,'left',0,array('style'=>'padding:0px;margin:0px;'));
					$CI->make->sDiv(array('class'=>'cpanel-center','style'=>'margin-right:4px;margin-left:4px;'));
						$CI->make->sDivRow(array('class'=>'center-btns'));
							// $CI->make->sDivCol(2);
							// 	$CI->make->button('<span id="terminal_text">'.fa('fa-desktop fa-2x fa-fw').'<br> MY</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'terminal-btn','type'=>'my','btn'=>'terminal'));
							// $CI->make->eDivCol();
							$CI->make->hidden('terminal-btn',TERMINAL_ID,array('type'=>'my'));
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="status_text">'.fa('fa-arrow-up fa-2x fa-fw').'<br> '.lng('cp_btn_open').'</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'status-btn','type'=>'open','btn'=>'status'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="types_text">'.fa('fa-book fa-2x fa-fw').'<br> '.lng('cp_btn_all_types').'</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'types-btn','type'=>'all','btn'=>'types'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button('<span id="day_text">'.fa('fa-clock-o fa-2x fa-fw').'<br> '.lng('cp_btn_today').'</span>',array('class'=>'btn-block no-raduis cpanel-btn-red double btnheader','id'=>'now-btn','type'=>'now','btn'=>'now'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-search fa-2x fa-fw').'<br> '.lng('cp_btn_search'),array('class'=>'btn-block no-raduis cpanel-btn-red double','id'=>'look-btn'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(2);
							// 	$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> FOOD SERVER',array('class'=>'btn-block no-raduis cpanel-btn-red double','id'=>'server-btn'));
							// $CI->make->eDivCol();
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-refresh fa-2x fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block no-raduis cpanel-btn-orange double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-chevron-circle-up fa-2x'),array('id'=>'order-scroll-up-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(1);
								$CI->make->button(fa('fa-chevron-circle-down fa-2x'),array('id'=>'order-scroll-down-btn','class'=>'btn-block no-raduis cpanel-btn-red-gray double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDiv(array('class'=>'orders-lists center-loads-div orders-div','style'=>'margin-top:10px;'));
							$CI->make->sDiv(array('class'=>'orders-lists-load'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('style'=>'margin-top:10px;display:none;','id'=>'orders-search','class'=>''));
							// $CI->make->append(onScrNumPad('search-order','go-search-order'));
							$CI->make->sForm("",array('id'=>'search-form-order'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(3);
									$CI->make->H(3,'Order #',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->input(null,'s_ref_code',null,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
									$CI->make->H(3,'Order Type',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->select(null,'s_order_type',$search_opts,null,array('class'=>'big-man-input rOkay'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3);
									$CI->make->H(3,'Receipt #',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->input(null,'s_rec_code',null,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
									$CI->make->H(3,'Amount From',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->input(null,'s_from_amt',null,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3);
									$CI->make->H(3,'Status',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$opts = array('Open'=>'open','Settled'=>'settled','Void'=>'void','Cancelled'=>'cancel');
									// '- SELECT STATUS -'=>null,
									$CI->make->select(null,'s_status',$opts,null,array('class'=>'big-man-input rOkay'));
									$CI->make->H(3,'Amount To',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->input(null,'s_to_amt',null,null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3);
									$CI->make->H(3,'Order Date',array('class'=>'text-left','style'=>'margin-bottom:10px;font-size:20px;'));
									$CI->make->date(null,'s_order_date',sql2Date($today),null,array('class'=>'count-inputs big-man-input rOkay','ro-msg'=>'tenant code must not be empty'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->eForm();
							$CI->make->H(3,null,array('class'=>'page-header'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(3);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH ORDER",array('id'=>'search-order-btn','class'=>'btn-block cpanel-btn-green'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('style'=>'margin-top:10px;display:none;','id'=>'server-search','class'=>''));
							
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->userDropSearch("SELECT FOOD SERVER NAME",'user',null,null,array('id'=>'user'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-search fa-2x fa-fw').'<br> SEARCH',array('id'=>'search-server-btn','class'=>'btn-block cpanel-btn-green double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow(array('style'=>'margin-bottom:225px;'));
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'orders-view-div center-loads-div'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(6);
									$CI->make->sDiv(array('class'=>'order-view-list','ref'=>null));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								if(ORDER_SLIP_PRINTER_SETUP){
									$CI->make->sDivCol(6);
										$CI->make->sDivRow();
											$buttons = array("recall"	=> fa('fa-search fa-lg fa-fw')." Recall",
															 // "transfer"	=> fa('fa-exchange fa-lg fa-fw')." Transfer Server",
															 "split"	=> fa('fa-arrows-h fa-lg fa-fw')." Split",
															 "combine"	=> fa('fa-compress fa-lg fa-fw')." Combine",
															 "settle"	=> fa('fa-check-square-o fa-lg fa-fw')." Settle",
															 // "receipt"	=> fa('fa-print fa-lg fa-fw')." Print Billing",
															 "cash"		=> fa('fa-money fa-lg fa-fw')." Settle Cash",
															 "credit"	=> fa('fa-credit-card fa-lg fa-fw')." Settle Credit"
															 // "checklist"	=> fa('fa-credit-card fa-lg fa-fw')." Checklist"
															 );
											if(ORDERING_STATION){
												unset($buttons['settle']);
												unset($buttons['cash']);
												unset($buttons['credit']);
												if(LOCAVORE){
													unset($buttons['combine']);
													unset($buttons['split']);
												}
											}else{
												if($user['role_id'] == SERVER_ID){
													unset($buttons['settle']);
													unset($buttons['cash']);
													unset($buttons['credit']);
													if(LOCAVORE){
														unset($buttons['combine']);
														unset($buttons['split']);
													}
												}
											}
											foreach ($buttons as $id => $text) {
												$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
													$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-blue'));
												$CI->make->eDivCol();
											}
											$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button(fa('fa-print fa-lg fa-fw')." Print Billing",array('id'=>'receipt-btn','class'=>'btn-block cpanel-btn-green'));
											$CI->make->eDivCol();
											if(!LOCAVORE && !KERMIT){
												$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
													$CI->make->button(fa('fa-print fa-lg fa-fw')." RePrint Order Slip",array('id'=>'reprint-os-btn','class'=>'btn-block cpanel-btn-green'));
												$CI->make->eDivCol();
											}
											if(ORDERING_STATION){
												if(!LOCAVORE){
													$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
														$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-teal'));
													$CI->make->eDivCol();
												}
											}else{
												if($user['role_id'] == SERVER_ID){
													if(!LOCAVORE){
														$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
															$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-teal'));
														$CI->make->eDivCol();
													}
												}else{
													$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
														$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-teal'));
													$CI->make->eDivCol();
												}
											}

											$buttons = array("void"		=> fa('fa-ban fa-lg fa-fw')." Void");
											if(ORDERING_STATION){
												unset($buttons['void']);
											}else{
												if($user['role_id'] == SERVER_ID){
													unset($buttons['void']);
												}
											}
											foreach ($buttons as $id => $text) {
												$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
													$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-red'));
												$CI->make->eDivCol();
											}
											$CI->make->sDivCol(12,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('id'=>'back-order-list-btn','class'=>'btn-block cpanel-btn-orange'));
											$CI->make->eDivCol();
											// $buttons = array(
											// 				 "back-order-list"		=> fa('fa-reply fa-lg fa-fw')." Back");
											// foreach ($buttons as $id => $text) {
											// }
										$CI->make->eDivRow();
									$CI->make->eDivCol();
								}
								else{
									$CI->make->sDivCol(6);
										$CI->make->sDivRow();
											$buttons = array("recall"	=> fa('fa-search fa-lg fa-fw')." Recall",
															 // "transfer"	=> fa('fa-exchange fa-lg fa-fw')." Transfer Server",
															 "split"	=> fa('fa-arrows-h fa-lg fa-fw')." Split",
															 "combine"	=> fa('fa-compress fa-lg fa-fw')." Combine",
															 "settle"	=> fa('fa-check-square-o fa-lg fa-fw')." Settle",
															 "receipt"	=> fa('fa-print fa-lg fa-fw')." Print Billing",
															 "cash"		=> fa('fa-money fa-lg fa-fw')." Settle Cash",
															 "credit"	=> fa('fa-credit-card fa-lg fa-fw')." Settle Credit"
															 // "checklist"	=> fa('fa-credit-card fa-lg fa-fw')." Checklist"
															 );
											if(ORDERING_STATION){
												unset($buttons['settle']);
												unset($buttons['cash']);
												unset($buttons['credit']);
												if(LOCAVORE){
													unset($buttons['combine']);
													unset($buttons['split']);
												}
											}else{
												if($user['role_id'] == SERVER_ID){
													unset($buttons['settle']);
													unset($buttons['cash']);
													unset($buttons['credit']);
													if(LOCAVORE){
														unset($buttons['combine']);
														unset($buttons['split']);
													}
												}
											}
											foreach ($buttons as $id => $text) {
												$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
													$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-blue'));
												$CI->make->eDivCol();
											}
											if(ORDERING_STATION){
												if(!LOCAVORE){
													$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
														$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-green'));
													$CI->make->eDivCol();
												}
											}else{
												if($user['role_id'] == SERVER_ID){
													if(!LOCAVORE){
														$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
															$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-green'));
														$CI->make->eDivCol();
													}
												}else{
													$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
														$CI->make->button(fa('fa-forward fa-lg fa-fw')." Change To",array('id'=>'change-to-btn','class'=>'btn-block cpanel-btn-green'));
													$CI->make->eDivCol();
												}
											}
											$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
												$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('id'=>'back-order-list-btn','class'=>'btn-block cpanel-btn-red'));
											$CI->make->eDivCol();
											$buttons = array("void"		=> fa('fa-ban fa-lg fa-fw')." Void",
															 );
											if(ORDERING_STATION){
												unset($buttons['void']);
											}else{
												if($user['role_id'] == SERVER_ID){
													unset($buttons['void']);
												}
											}
											foreach ($buttons as $id => $text) {
												$CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
													$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block cpanel-btn-red'));
												$CI->make->eDivCol();
											}
											// $buttons = array(
											// 				 "back-order-list"		=> fa('fa-reply fa-lg fa-fw')." Back");
											// foreach ($buttons as $id => $text) {
											// }
										$CI->make->eDivRow();
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'reasons-div center-loads-div'));
							$CI->make->sDivRow();
								$buttons = array(
									"Wrong Items Ordered",
									"No Show Pick up",
									"No Show Delivery",
									"Took Too long",
									"Customer Did Not Like it",
									"Manager Comp",
									"Employee Training",
								);
								foreach ($buttons as $text) {
									$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red reason-btns double'));
									$CI->make->eDivCol();
								}
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button("Other Reason",array('class'=>'btn-block cpanel-btn-teal double','id'=>'cancel-other-reason-btn'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('class'=>'btn-block cpanel-btn-orange cancel-reason-btn double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'change-to-div center-loads-div'));
							$CI->make->sDivRow();
								$CI->make->H(3,'Change Order To:',array('class'=>'text-center','style'=>'margin-top:10px;margin-bottom:15px;'));
								// foreach (unserialize(SALE_TYPES) as $st) {
								// 	$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
								// 		$CI->make->button($st,array('ref'=>strtolower($st),'class'=>'btn-block cpanel-btn-blue change-to-btns double'));
								// 	$CI->make->eDivCol();
								// }
								$ids = explode(',',$set->controls);
								foreach($ids as $value){
									$text = explode('=>',$value);
									$text_ref=$text[1];
									$texts=str_replace(' ', '', $text[1]);
									$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button(strtoupper($text_ref),array('ref'=>strtolower($texts),'class'=>'btn-block cpanel-btn-blue change-to-btns double'));
									$CI->make->eDivCol();
								}
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('class'=>'btn-block cpanel-btn-orange cancel-reason-btn double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(2,'left',0,array('class'=>'cpanel-right'));
					// $CI->make->H(5,'TRANSACTIONS',array('class'=>'headline text-center','style'=>'margin-bottom:10px;'));
					// $CI->make->button('NEW DELIVERY',array('class'=>'btn-block cpanel-btn-green'));
					$CI->make->button(lng('cp_btn_customers'),array('id'=>'customer-btn','class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('NO SALE',array('class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('PAYOUT',array('class'=>'btn-block cpanel-btn-green'));
					// $CI->make->button('ADD TIPS',array('class'=>'btn-block cpanel-btn-green'));
					if(!ORDERING_STATION){
						if(MALL != 'megaworld'){
							$CI->make->button(lng('cp_btn_cust_deposit'),array('id'=>'cust-bank-btn','class'=>'btn-block cpanel-btn-green'));
						}
						// $CI->make->button(lng('cp_btn_gift_cards'),array('id'=>'gift-card-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(lng('cp_btn_gift_cards'),array('id'=>'gift-card-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(lng('cp_btn_loyalty_cards'),array('id'=>'loyalty-card-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(lng('cp_btn_reservation'),array('id'=>'reserve-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(lng('cp_btn_quick_edit'),array('id'=>'quick-edit-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(lng('cp_btn_printer_setup'),array('id'=>'printer-setup-btn','class'=>'btn-block cpanel-btn-green'));
					}
				$CI->make->eDivCol();
			$CI->make->eDivRow();
			$CI->make->sDivRow(array('class'=>'cpanel-bottom'));
				if(!ORDERING_STATION){
					// if(!has_restart_printer){
					// 	$CI->make->sDivCol(1);
					// 	$CI->make->eDivCol();
					// }
					if($user['role_id'] == SERVER_ID){
						$CI->make->sDivCol(4);
						$CI->make->eDivCol();
					}else{
						$CI->make->sDivCol(2);
								// if ($user['role_id'] == 1 || $user['role_id'] == 2)
									$CI->make->button(fa('fa-user fa-2x fa-fw').'<br> '.lng('cp_btn_manager'),array('id'=>'manager-btn','class'=>'btn-block cpanel-btn-brown-bear double'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							if ($user['role_id'] == 1 || $user['role_id'] == 2)
								$CI->make->button(fa('fa-cogs fa-2x fa-fw').'<br> '.lng('cp_btn_back_office'),array('id'=>'back-office-btn','class'=>'btn-block cpanel-btn-brown-bear double'));
						$CI->make->eDivCol();
					}
				}
				else{
					$CI->make->sDivCol(4);
					$CI->make->eDivCol();
				}
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array("id"=>"time-div"));
						$CI->make->sDiv(array('id'=>'time','class'=>'headline text-center'));
						$CI->make->eDiv();
						$CI->make->H(3,$today,array("style"=>"font-size:18px;","class"=>'headline text-center'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
				if(!ORDERING_STATION){

					if($user['role_id'] == SERVER_ID){

					}else{
						$CI->make->sDivCol(2);
							$CI->make->button(fa('fa-clock-o fa-2x fa-fw').'<br> '.lng('cp_btn_time_clock'),array('id'=>'time-clock-btn','class'=>'btn-block cpanel-btn-brown-bear double'));
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->button(fa('fa-inbox fa-2x fa-fw').'<br> '.lng('cp_btn_open_drawer'),array('id'=>'open-drawer-btn','class'=>'btn-block cpanel-btn-orange double'));
						$CI->make->eDivCol();
					}
				}

				// if(has_restart_printer){

				// 	$CI->make->sDivCol(2);
				// 		$CI->make->button(fa('fa-print fa-2x fa-fw').'<br> '.lng('cp_btn_reset_printer'),array('id'=>'restart-printer-button','class'=>'btn-block cpanel-btn-orange double'));
				// 	$CI->make->eDivCol();
				// }
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function searchPanelPage($needEod=false,$set,$today=null){
	$CI =& get_instance();
	$user = $CI->session->userdata('user');
		$CI->make->sDiv(array('id'=>'cashier-panel','class'=>'front-end'));
			$CI->make->sDivRow();
				$CI->make->sDivCol(3,'left',0,array('class'=>'cpanel-left'));
					$CI->make->H(5,fa('fa fa-search').' SEARCH ORDER',array('class'=>'headline text-center','style'=>'margin-bottom:10px;'));
					$CI->make->sForm("",array('id'=>'search-form'));
						$CI->make->input('Receipt #','receipt_no',null);
						$CI->make->input('Date & Time Range','calendar_range',null,null,array('class'=>'rOkay daterangepicker datetimepicker','style'=>'position:initial;'),fa('fa-calendar'));
						$CI->make->input('From Amount','from_amt',null);
						$CI->make->input('To Amount','to_amt',null);
						$CI->make->userDrop('Employee','cashier',null,null,array());
						$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'search-btn','class'=>'btn-block cpanel-btn-green'));
						$CI->make->button(fa('fa-reply fa-lg fa-fw').' Go Back',array('id'=>'back-btn','class'=>'btn-block cpanel-btn-red'));
					$CI->make->eForm();
				$CI->make->eDivCol();
				$CI->make->sDivCol(9);
					$CI->make->sDiv(array('class'=>'cpanel-center','style'=>'min-height:420px;'));
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function counterPage($type=null,$time=null,$loaded=null,$order=array(),$typeCN=array(),$local_tax=0,$kitchen_printer=null,$app_type=null){
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
				#LEFT
				$CI->make->sDivCol(1,'left',0,array('class'=>'counter-left gc-left-panel'));
					// $CI->make->button("",array('id'=>'','class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-magnet fa-lg fa-fw').'<br>Take Out',array('id'=>'take-all-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
					if(ORDERING_STATION){
						if($type == 'retail'){
							// echo "<pre>",print_r($type),"</pre>";die();
							if(KERMIT){
								$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}else{
								$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}
						}
						else if ($type == 'counter' || $type == 'takeout' && SERVER_NO_SETUP){
							$CI->make->button(fa('fa-ticket fa-lg fa-fw').'<br> Serve No.',array('id'=>'serve-no-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						}
						else{
							if(isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'retail'){
								if(KERMIT){
									$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
								}else{
									$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
								}
							}
							else if(SERVER_NO_SETUP && isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'counter' || SERVER_NO_SETUP && isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'takeout'){
								$CI->make->button(fa('fa-ticket fa-lg fa-fw').'<br> Serve No.',array('id'=>'serve-no-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}
							else	
								$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						}
						$CI->make->button(fa('fa-tags fa-lg fa-fw').'<br> Quantity',array('id'=>'qty-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Inv Check',array('id'=>'inv-check-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-text-width fa-lg fa-fw').'<br> Add Remarks',array('id'=>'remarks-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-times fa-lg fa-fw').'<br>REMOVE',array('id'=>'remove-btn','class'=>'btn-block counter-btn-red double media-btn-height '.$loaded));
						$CI->make->button(fa('fa-reply fa-lg fa-fw').'<br>Back',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
					}else{
						// $CI->make->button(fa('fa-book fa-lg fa-fw').'<br>Receipt Preview',array('id'=>'rcpt-prev-btn','class'=>'btn-block counter-btn-red double'));
						if($type == 'retail'){
							// echo "<pre>",print_r($type),"</pre>";die();
							if(KERMIT){
								$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}else{
								$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}
						}
						else if ($type == 'counter' || $type == 'takeout' && SERVER_NO_SETUP){
							$CI->make->button(fa('fa-ticket fa-lg fa-fw').'<br> Serve No.',array('id'=>'serve-no-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						}
						else{
							if(isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'retail'){
								if(KERMIT){
									$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
								}else{
									$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
								}
							}
							else if(SERVER_NO_SETUP && isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'counter' || SERVER_NO_SETUP && isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'takeout'){
								$CI->make->button(fa('fa-ticket fa-lg fa-fw').'<br> Serve No.',array('id'=>'serve-no-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
							}
							// else	
							// 	$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						}
							
						$CI->make->button(fa('fa-tags fa-lg fa-fw').'<br> Quantity',array('id'=>'qty-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						if(ORDERING_STATION){
							$CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Discount',array('id'=>'no-discount-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						}else{
							$CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Discount',array('id'=>'add-discount-choose-btn','class'=>'btn-block counter-btn-red double media-btn-height'));						
						}
						// $CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Line Discount',array('id'=>'add-discount-line-btn','class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Zero-Rated',array('id'=>'zero-rated-btn','class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Inv Check',array('id'=>'inv-check-btn','class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-tag fa-lg fa-fw').'<br> Add Charges',array('id'=>'charges-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-asterisk fa-lg fa-fw').'<br> Free',array('id'=>'free-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-text-width fa-lg fa-fw').'<br> Add Remarks',array('id'=>'remarks-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
						$CI->make->button(fa('fa-times fa-lg fa-fw').'<br>REMOVE',array('id'=>'remove-btn','class'=>'btn-block counter-btn-red double media-btn-height '.$loaded));
						// $CI->make->button(fa('fa-keyboard-o fa-lg fa-fw').'<br>MISC',array('class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-file fa-lg fa-fw').'<br>RECALL',array('class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-circle-o fa-lg fa-fw').'<br> ORDER TAX EXEMPT',array('id'=>'tax-exempt-btn','class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-magnet fa-lg fa-fw').'<br>HOLD ALL',array('id'=>'hold-all-btn','class'=>'btn-block counter-btn-red double'));
						// $CI->make->button(fa('fa-power-off fa-lg fa-fw').'<br>LOGOUT',array('id'=>'logout-btn','class'=>'btn-block counter-btn-red double'));
						$CI->make->button(fa('fa-reply fa-lg fa-fw').'<br>Back',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double media-btn-height'));
					}


				$CI->make->eDivCol();
				#CENTER
				$CI->make->sDivCol(5,'',0,array('class'=>'gc-center-panel'));
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div',"style"=>'height:100%;'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
						    	$tableN = null;
						    	$guestN = 1;
						    	$serveN = null;

						    	if(isset($_SESSION['problem'])){
						    		$CI->make->H(3,'<i class="fa fa-warning"></i>'.$_SESSION['problem'],array('id'=>'trans-header','class'=>'receipt text-center text-uppercase','style'=>'color:red;font-size:20px;'));
						    	}

							    if(isset($typeCN[0]['table_name']))
							    	$tableN = " - ".$typeCN[0]['table_name'];
							    if(isset($order['table_name']) && $order['table_name'] != "")
							    	$tableN = " - ".$order['table_name'];

							    if(isset($typeCN[0]['guest']))
							    	$guestN = $typeCN[0]['guest'];
							    if(isset($order['guest']) && $order['guest'] != "")
							    	$guestN = $order['guest'];
							    
							    if(isset($typeCN[0]['serve_no']))
							    	$serveN = $typeCN[0]['serve_no'];
							    if(isset($order['serve_no']) && $order['serve_no'] != "")
							    	$serveN = $order['serve_no'];

								$waiter = '';

								if(isset($order['waiter_username'])){
									$waiter_name = trim($order['waiter_username']);
									$display = '';
									if($waiter_name != '')
										$waiter = 'FS: '.$order['waiter_username'];
								}
						// echo $app_type;
								if($type== 'dinein'){
									$CI->make->H(3,strtoupper(DINEINTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($type== 'counter'){
									$CI->make->H(3,strtoupper(COUNTERTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($type=='takeout'){
									$CI->make->H(3,strtoupper(TAKEOUTTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($app_type=='App%20Order'){
									$CI->make->H(3,strtoupper('App Order').$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}else{
									$CI->make->H(3,strtoupper($type).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}
								$guestNtxt="";
								if($guestN != null){
									if($guestN  == 0){
										$guestN = 1;
									}
									$guestNtxt = 'Guest #<span id="ord-guest-no">'.$guestN.'</span> <a href="#" id="edit-order-guest-no">'.fa('fa-edit fa-lg').'</a>';
								}
								$serveNtxt="";
								$serveNx="";
								if($serveN  > 0 ){
									$serveNx = "Serve # ".$serveN;
								}
								$serveNtxt = '<span id="ord-serve-no">'.$serveNx.'</span>';
								if(isset($typeCN[0]['customer_id'])){
									$CI->make->H(5,"CUSTOMER ID: ".$typeCN[0]['customer_id'],array('id'=>'trans-customer','class'=>'receipt text-center text-uppercase'));
								}
								else{
									$CI->make->H(5,"",array('id'=>'trans-customer','class'=>'receipt text-center text-uppercase','style'=>'display:none;'));
								}
								$CI->make->H(5,$time." ".$guestNtxt." ".$serveNtxt,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$display='display:none;';
								$CI->make->H(3,'ADD PROMO <span id="promo-qty"></span> QTY',array('id'=>'promo-txt','class'=>'receipt text-center','style'=>'color:red;display:none;'));
								// $CI->make->H(5,$waiter,array('id'=>'trans-server-txt','class'=>'addon-texts receipt text-center','style'=>'margin-top:5px;'.$display));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->sDiv(array('class'=>'foot-det'));
									$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
									// $CI->make->H(5,'LOCAL TAX: <span id="local-tax-txt">0.00</span>',array('class'=>'receipt text-center'));
									$lt_txt = "";
									if($local_tax > 0){
										$lt_txt = 'LOCAL TAX: <span id="local-tax-txt">0.00</span>';
									}
									$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span> '.$lt_txt,array('class'=>'receipt text-center'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div',"style"=>"margin-top: 0px;"));
						$CI->make->sDivRow();
							
							if(ORDER_SLIP_PRINTER_SETUP){
							// if($kitchen_printer != null){
								if(ORDERING_STATION){
									$CI->make->sDivCol(1);
										$CI->make->button('',array('id'=>'print-btn','class'=>'btn-block counter-btn-green double disabled','doprint'=>'true','style'=>'height:62px!important;display:none;'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(12);
										$CI->make->button(fa('fa-check fa-lg fa-fw').' Send',array('id'=>'send-trans-btn','class'=>'btn-block counter-btn-green double check_fs','style'=>'height:62px!important;'));
									$CI->make->eDivCol();
								}
								else{
									// if($user['role_id'] == SERVER_ID){
									// 	$CI->make->sDivCol(1);
									// 		$CI->make->button('',array('id'=>'print-btn','class'=>'btn-block counter-btn-green double disabled','doprint'=>'false','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// 	$CI->make->sDivCol(10);
									// 		$CI->make->button(fa('fa-check fa-lg fa-fw').' Send',array('id'=>'send-trans-btn','class'=>'btn-block counter-btn-green double check_fs','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// }elseif($type== 'reservation'){
									// 	$CI->make->sDivCol(12);
									// 		$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'rhold-all-btn','class'=>'btn-block counter-btn-green double','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// }else{
									// 	$CI->make->sDivCol(4);
									// 		$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// 	$CI->make->sDivCol(2);
									// 		$CI->make->button(fa('fa-ban fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'false','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// }
								}
								// if(ORDERING_STATION){
								// 	if($type != 'reservation'){
								// 		$CI->make->sDivCol(1);
								// 			$CI->make->button('',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-green double disabled','doprint'=>'true','style'=>'height:62px!important;display:none;'));
								// 		$CI->make->eDivCol();
								// 	}
								// }
								// else{
								// 	if($user['role_id'] == SERVER_ID){
								// 		$CI->make->sDivCol(1);
								// 			$CI->make->button('',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-green double disabled','doprint'=>'true','style'=>'height:62px!important;'));
								// 		$CI->make->eDivCol();
								// 	}else{
								// 		if($type != 'reservation'){
								// 			$CI->make->sDivCol(2);
								// 				$CI->make->button(fa('fa-print fa-lg fa-fw').'<br>ORDER SLIP',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true','style'=>'height:62px!important;'));
								// 			$CI->make->eDivCol();
								// 		}
								// 	}
								// }

							}
							else{

								if(ORDERING_STATION){
									// $CI->make->sDivCol(6);
									// 	$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double','style'=>'height:62px!important;'));
									// $CI->make->eDivCol();
									// $CI->make->sDivCol(6);
									// 	$CI->make->button(fa('fa-print fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true','style'=>'height:62px!important;'));
									// $CI->make->eDivCol();	
								}
								else{
									// if($user['role_id'] == SERVER_ID){
									// 	$CI->make->sDivCol(6);
									// 		$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// 	$CI->make->sDivCol(6);
									// 		$CI->make->button(fa('fa-print fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// }else{
									// 	$CI->make->sDivCol(5);
									// 		$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();
									// 	$CI->make->sDivCol(2);
									// 		$CI->make->button(fa('fa-print fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true','style'=>'height:62px!important;'));
									// 	$CI->make->eDivCol();		
									// }
								}

							}


						// $CI->make->eDivRow();
						// $CI->make->sDivRow();
							if(ORDERING_STATION){
								// $CI->make->sDivCol(3);
								// 	$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double disabled'));
								// $CI->make->eDivCol();
								// $CI->make->sDivCol(3);
								// 	$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double disabled'));
								// $CI->make->eDivCol();
								// $CI->make->sDivCol(4);
								// 	$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double disabled','style'=>'height:62px!important;'));
								// $CI->make->eDivCol();
								// if($order){
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->button(fa('fa-arrows-h fa-lg fa-fw').' SPLIT',array('id'=>'splitin-btn','class'=>'btn-block counter-btn-teal double','atype'=>$order['type'],'aid'=>$order['sales_id']));
								// 	$CI->make->eDivCol();
								// }else{
								// 	$CI->make->sDivCol(3);
								// 		$CI->make->button(fa('fa-arrows-h fa-lg fa-fw').' SPLIT',array('id'=>'splitin-btn','class'=>'btn-block counter-btn-teal double disabled'));
								// 	$CI->make->eDivCol();
								// }
							}
							else{
								if($user['role_id'] == SERVER_ID){
								}else{
									// $CI->make->sDivCol(3);
									// 	$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double'));
									// $CI->make->eDivCol();
									// $CI->make->sDivCol(3);
									// 	$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double'));
									// $CI->make->eDivCol();
									if(ORDER_SLIP_PRINTER_SETUP){
										if($type != 'reservation'){

											$CI->make->sDivCol(12);
												$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double','style'=>'height:60px!important;width:100%'));
											$CI->make->eDivCol();
										}
									}
									else{
										$CI->make->sDivCol(5);
											$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double','style'=>'height:60px!important;width:100%'));
										$CI->make->eDivCol();
									}
									// if($order){
									// 	$CI->make->sDivCol(3);
									// 		$CI->make->button(fa('fa-arrows-h fa-lg fa-fw').' SPLIT',array('id'=>'splitin-btn','class'=>'btn-block counter-btn-teal double','atype'=>$order['type'],'aid'=>$order['sales_id']));
									// 	$CI->make->eDivCol();
									// }else{
									// 	$CI->make->sDivCol(3);
									// 		$CI->make->button(fa('fa-arrows-h fa-lg fa-fw').' SPLIT',array('id'=>'splitin-btn','class'=>'btn-block counter-btn-teal double disabled'));
									// 	$CI->make->eDivCol();
									// }
								}
							}
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(1,'left');
					$CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-cat-scroll-up','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'menu-cat-container gc-menu-cat-container',"style"=>""));
					$CI->make->eDiv();
					$CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-cat-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(5,'left',0,array('class'=>'gc-right-panel'));
					// $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
							// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'counter-right gc-counter-right','style'=>'height:671px;overflow:hidden;'));
						$CI->make->sDiv(array('class'=>'ar-div','style'=>'margin-top:40px'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;'));
								$CI->make->input(null,'ar',isset($order['ref']) ? $order['ref']:'',null,array()," Aknowledgment Receipt #");
							$CI->make->eDiv();
						$CI->make->eDiv();
						#MENU
						$CI->make->sDiv(array('class'=>'menus-div loads-div','style'=>'display:none'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;'));
								$CI->make->input(null,'search-menu',null,null,array(),fa('fa fa-search')." Search");
							$CI->make->eDiv();
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'items-lists','style'=>'height:520px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#MODS
						$CI->make->sDiv(array('class'=>'mods-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'MODIFIERS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'mods-lists','style'=>'height:525px;overflow:hidden;'));
						 	 	$CI->make->hidden('mods-mandatory',"0",array("class"=>"mods-mandatory"));
						 	 	$CI->make->hidden('mod-group-name',null,array("class"=>"mod-group-name"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#SUB MODS
						$CI->make->sDiv(array('class'=>'submods-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SUB MODIFIERS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'submods-lists','style'=>'height:525px;overflow:hidden;'));
						 	 	// $CI->make->hidden('mods-mandatory',"0",array("class"=>"mods-mandatory"));
						 	 	$CI->make->hidden('submods-name',null,array("class"=>"submods-name"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#SUBCATEGORY added subcategory
						$CI->make->sDiv(array('class'=>'subcategory-div loads-div','style'=>'display:none'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;'));
							// 	$CI->make->input(null,'search-menu',null,null,array(),fa('fa fa-search')." Menu");
								// $CI->make->sDivCol(9);
								// $CI->make->eDivCol();
								// $CI->make->sDivCol(3);
								// 	$CI->make->button('Back',array('id'=>'subcat-back','class'=>'btn-block counter-btn-red'));
								// $CI->make->eDivCol();
							$CI->make->eDiv();
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'subcategory-lists','style'=>'height:520px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#QTY
						$CI->make->sDiv(array('class'=>'qty-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Quantity',array('class'=>'receipt text-center title','style'=>'margin-bottom:20px'));
							$CI->make->sDiv(array('class'=>'qty-lists'));
								
								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->sDivCol(12);
										// $CI->make->input(null,'times-qty',null,'Quantity',array('class'=>'noletter','style'=>'height:50px;'),fa('fa-times fa-2x'));
									$CI->make->input(null,'times-qty',null,'Quantity',array('class'=>'noletter','style'=>'height:50px;'),'Quantity');
									$CI->make->eDivCol();
									// $CI->make->sDivCol(6);
									// 	$CI->make->button('Apply',array('id'=>'multiply-items','class'=>'btn-block counter-btn-orange','operator'=>'times'));
									// $CI->make->eDivCol();
								$CI->make->eDivRow();

								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->sDivCol(6);
										$CI->make->input('GC Code From','gc-code-from',null,'GC Code From',array('class'=>'noletter','style'=>'height:50px;'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->input('GC Code To','gc-code-to',null,'GC Code To',array('class'=>'noletter','style'=>'height:50px;'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();

								$CI->make->sDiv(array('class'=>'counter-center-btns'));
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->button('Reset',array('value'=>'1','operator'=>'equal','class'=>'btn-block edit-qty-btn counter-btn-red double'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->button('Submit',array('id'=>'qty-btn-done','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#DISCOUNT
						$CI->make->sDiv(array('class'=>'sel-discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'select-discounts-lists','style'=>'height:550px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->H(3,'RATE: % <span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'discounts-lists'));
								 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 $CI->make->sDivCol(12);
								    	$guestN = null;
									    if(isset($typeCN[0]['guest']))
									    	$guestN = $typeCN[0]['guest'];
								 	 	$CI->make->input(null,'disc-guests',$guestN,'Total No. Of Guests',array('readOnly'=>'true'),fa('fa-user'));
								 	 $CI->make->eDivCol();
								 $CI->make->eDivRow();
								 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('ALL ITEMS',array('ref'=>'all','class'=>'disc-btn-row btn-block counter-btn-teal'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('EQUALLY DIVIDED',array('ref'=>'equal','class'=>'disc-btn-row btn-block counter-btn-orange'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
								 $CI->make->sForm("",array('id'=>'disc-form'));
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 	$CI->make->hidden('disc-disc-id',null);
									 	 	$CI->make->hidden('disc-disc-rate',null);
									 	 	$CI->make->hidden('disc-disc-code',null);
									 	 	$CI->make->hidden('disc-no-tax',null);
									 	 	$CI->make->hidden('disc-fix',null);
									 	 $CI->make->eDivCol();
									 	 // $CI->make->sDivCol(6);
									 	 	// $CI->make->input(null,'disc-cust-guest',null,'No. Of Guest',array('class'=>'rOkay','ro-msg'=>'Add No. Of Guest for Discount'),fa('fa-male'));
									 	 // $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-bday',null,'Birthday',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-remarks',null,'Remarks',array('ro-msg'=>'Add Remarks'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	// $CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
										 	$CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-line-btn','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
										 // $CI->make->sDivCol(6);
									 	//  	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
									 	//  $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	// $CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc','class'=>'btn-block counter-btn-green'));
										 	$CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc-line','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 	 // 'ref'=>'equal',
									 $CI->make->eDivRow();
									 $CI->make->sDivRow();
										 $CI->make->sDivCol(12);
										 	$CI->make->sDiv(array('class'=>'disc-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
											$CI->make->eDiv();
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 $CI->make->eForm();
								 // $CI->make->sDivRow(array('style'=>'margin-top:20px;'));
								 // 	 $CI->make->sDivCol(12);
								 // 	 	$CI->make->button(fa('fa fa-plus fa-lg fa-fw').' SELECTED ITEM ONLY',array('ref'=>'item','class'=>'disc-btn-row btn-block counter-btn-green'));
								 // 	 	$CI->make->sUl(array('class'=>'item-disc-list','style'=>'margin-top:10px;'));
								 // 	 	$CI->make->eUl();
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#Choose didscount
						$CI->make->sDiv(array('class'=>'choosedisc-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Discount Type',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'option-lists'));
								$CI->make->sDivCol(12);
		                            $CI->make->button('TOTAL DISCOUNT',array('id'=>'add-discount-btn','class'=>'btn-block counter-btn-green'));
		                        $CI->make->eDivCol();
		                        $CI->make->sDivCol(12);
		                            $CI->make->button('LINE DISCOUNT',array('id'=>'add-discount-line-btn','class'=>'btn-block counter-btn-green'));
		                        $CI->make->eDivCol();
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
						#CHARGES
						$CI->make->sDiv(array('class'=>'charges-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Charges',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'charges-lists','style'=>"overflow:auto;height:550px;"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#WAITER
						$CI->make->sDiv(array('class'=>'waiter-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Food Server',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							 $CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
							 	 	$CI->make->button(fa('fa-times fa-lg fa-fw').' REMOVE FOOD SERVER',array('id'=>'remove-waiter-btn','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
							 $CI->make->eDivRow();
							$CI->make->sDiv(array('class'=>'waiters-lists','style'=>"overflow:auto;height:460px;"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#REMARKS
						$CI->make->sDiv(array('class'=>'remarks-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'REMARKS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDivRow();
								$buttons = array(
									"Instruction:",
									// "Omotenashi No:",
								);
								foreach ($buttons as $text) {
									$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red add-rem-btns double'));
									$CI->make->eDivCol();
								}
								// $CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
								// $CI->make->button('Sugar Level',array('class'=>'btn-block cpanel-btn-red double','id'=>'sugar-btn','style'=>'background-color:#b6151c!important'));
								// $CI->make->eDivCol();
								// $CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
								// $CI->make->button('Ice Level',array('class'=>'btn-block cpanel-btn-red double','id'=>'ice-btn','style'=>'background-color:#b6151c!important'));
								// $CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow(array("class"=>"display_sugar","id"=>"dis_sugar"));
							$btnss = array(
									"100% Sugar",
									"80% Sugar",
									"50% Sugar",
									"30% Sugar",
								);
								foreach ($btnss as $txt) {
									$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button($txt,array('class'=>'btn-block cpanel-btn-red double sugar-sub-btn','style'=>'background-color:#20877f!important'));
								$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->sDivRow(array("class"=>"display_ice hide","id"=>"dis_ice"));
							$btnss = array(
									"100% Ice",
									"80% Ice",
									"50% Ice",
									"30% Ice",
								);
								foreach ($btnss as $txt) {
									$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button($txt,array('class'=>'btn-block cpanel-btn-red double ice-sub-btn','style'=>'background-color:#20877f!important'));
								$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
								 	 $CI->make->sForm("",array('id'=>'remarks-form'));
								 	 	$CI->make->textarea(null,'line-remarks',null,null,array('class'=>'rOkay','ro-msg'=>'Add Remarks'));
								 	 	$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'add-remark-btn','class'=>'btn-block counter-btn-green'));
								 	 $CI->make->eForm();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
						
						$CI->make->eDiv();
						#RETAIL
						$CI->make->sDiv(array('class'=>'retail-div loads-div','style'=>'display:none'));
							// $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							//  	 $CI->make->sDivCol();
							//  	 	$CI->make->sDiv(array('id'=>'scan-div'));
							// 	 	 	$btn = $CI->make->button("SCAN CODE",array('id'=>'go-scan-code','return'=>true,'class'=>'btn-block counter-btn-orange'));
							// 	 	 	$CI->make->pwdWithBtn(null,'scan-code',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),$btn);
							// 	 	$CI->make->eDiv();
							//  	 $CI->make->eDivCol();
							// $CI->make->eDivRow();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH ITEM",array('id'=>'go-search-item','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-item',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->append('<hr>');
					 	 	$CI->make->H(4,null,array('class'=>'retail-title text-center title','style'=>'margin-bottom:10px;display:none;'));
					 	 	$CI->make->sDiv(array('class'=>'retail-loads-div listings','style'=>'height:400px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#CUSTOMER
						$CI->make->sDiv(array('class'=>'customers-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT CUSTOMER',array('class'=>'customers-title text-center title','style'=>'margin-bottom:10px;'));
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH",array('id'=>'go-search-customer','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-customer',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->sDiv(array('class'=>'customers-loads-div listings','style'=>'height:400px;overflow:auto;margin-top:10px;'));
							$CI->make->eDiv();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	$CI->make->button(fa('fa-times fa-lg fa-fw')." REMOVE CUSTOMER",array('id'=>'remove-customer','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#FOR ZERO PRICE
						$CI->make->sDiv(array('class'=>'price-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Input Price',array('class'=>'receipt text-center title','style'=>'margin-bottom:20px'));
							$CI->make->sDiv(array('class'=>'qty-lists'));
								
								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->hidden('menu-id-hidden',null);
									$CI->make->hidden('menu-cat-id-hidden',null);
									$CI->make->hidden('menu-cat-name-hidden',null);
									$CI->make->sDivCol(6);
										$CI->make->input(null,'menu-price',null,'Amount',array('class'=>'noletter','style'=>'height:50px;'),'P');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('Apply',array('id'=>'save-price','class'=>'btn-block counter-btn-orange','operator'=>'times'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
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
function settlePage($ord=null,$det=null,$discs=null,$totals=null,$charges=null,$trans_retake=false,$payment_types=null,$gc_types=null,$payment_group=null){
	$CI =& get_instance();
		$ret = 0;
		if($trans_retake)
			$ret = 1;
		// echo "<pre>",print_r($ord),"</pre>";die();
		$CI->make->sDiv(array('class'=>'media-settle-panel','id'=>'settle','retrack'=>$ret,'sales'=>$ord['gc_id'],'type'=>$ord['type'],'balance'=>num($ord['balance'])));
			$CI->make->sDivRow();
				$CI->make->sDivCol(5,'left',0,array('class'=>'settle-left settle-left-panel'));
					$CI->make->sBox('default',array('class'=>'box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-red'));
							$CI->make->boxTitle('BALANCE DUE',array('style'=>'float:left'));
							$CI->make->boxTitle('PHP <span id="balance-due-txt">'.num($ord['balance']).'</span>',array('class'=>'pull-right','style'=>'margin-right:10px;'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody();
							$CI->make->sDiv(array('class'=>'order-view-list sss'));
						        $waiter = "";
						        $waiter_name = trim($ord['waiter_username']);
						   //      if($waiter_name != "")
						   //      	$waiter = 'FS: '.$ord['waiter_username'];
						   //      if($ord['type']== 'dinein'){
									// $CI->make->H(3,strtoupper(DINEINTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
						   //      }elseif($ord['type']== 'counter'){
									// $CI->make->H(3,strtoupper(COUNTERTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));
						   //      }elseif($ord['type']== 'takeout'){
									// $CI->make->H(3,strtoupper(TAKEOUTTEXT)." #".$ord['sales_id'],array('class'=>'receipt text-center'));

						   //      }else{
									// $CI->make->H(3,strtoupper($ord['type'])." #".$ord['sales_id'],array('class'=>'receipt text-center'));
						        	
						   //      }
						   //      $CI->make->H(5,sql2DateTime($ord['datetime'])." ".$waiter,array('class'=>'receipt text-center'));
						        $CI->make->H(5,"",array('id'=>'loyalty-text','class'=>'receipt text-center'));
						            // $CI->make->H(5,'Food Server: '.$ord['waiter_name'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
						        $CI->make->append('<hr>');
						        $CI->make->sDiv(array('class'=>'body'));
						            $CI->make->sUl();
						                $total = 0;
						                foreach ($det as $menu_id => $opt) {
						                    $qty = $CI->make->span($opt['qty'],array('class'=>'qty','return'=>true));
						                    
						                    $line_price = "";
                        					$line_total = $opt['price'];
                        					
                        					if($opt['qty'] > 1){
					                            $line_price = " @".$opt['price'];
					                            $line_total = $opt['price'] * $opt['qty'];
					                        }

					                        foreach($discs as $line_item => $val){
					                        	if($val['items'] != ""){
						                            if($line_item == $menu_id){
						                                $line_disc = $val['amount'];
						                                $less_vat = 0;

						                                if($val['fix'] == 1){
						                                	$less_vat = 0;
						                                }else{
							                                if($val['no_tax'] == 1){
							                                    $less_vat = ($line_total / 1.12) * 0.12;
							                                }
						                                }

						                                $line_total = $line_total - $line_disc - $less_vat;
						                            }
					                        	}

					                        }

						                    if($opt['is_takeout'] == 1 && $ord['type']== 'dinein'){
						                    	$name = $CI->make->span(fa('fa-magnet').$opt['name'].$line_price,array('class'=>'name','return'=>true));
						                    }else{
						                    	$name = $CI->make->span($opt['name'].$line_price,array('class'=>'name','return'=>true));
						                    }


						                    // $cost = $CI->make->span($opt['price'],array('class'=>'cost','return'=>true));
						                    $cost = $CI->make->span(round($line_total,2),array('class'=>'cost','return'=>true));
						                    $price = $opt['price'];
						                    $CI->make->li($qty." ".$name." ".$cost,array('ref'=>$menu_id));
						                    $CI->make->li($cost,array('style'=>'display:none','id'=>'trans-row-'.$menu_id));
						                    if($opt['remarks'] != ""){
						                        $remarks = $CI->make->span(fa('fa-text-width').' '.ucwords($opt['remarks']),array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
						                        $CI->make->li($remarks);
						                    }
						                    if(isset($opt['modifiers']) && count($opt['modifiers']) > 0){
						                        foreach ($opt['modifiers'] as $mod_id => $mod) {
						                            $name = $CI->make->span($mod['qty'].' '.$mod['name'],array('class'=>'name','style'=>'margin-left:36px;','return'=>true));
						                            $cost = "";
						                            if($mod['price'] > 0 )
						                                $cost = $CI->make->span($mod['qty']*$mod['price'],array('class'=>'cost','return'=>true));
						                            $CI->make->li($name." ".$cost);
						                            $price += $mod['price'];


							                        if(isset($mod['submodifiers']) && count($mod['submodifiers']) > 0){
					                                    foreach ($mod['submodifiers'] as $sub_id => $submod) {
					                                        
					                                        if($submod['mod_line_id'] == $mod['mod_line_id']){
					                                            $sname = $CI->make->span('*'.$submod['qty'].' '.$submod['name'],array('class'=>'name','style'=>'margin-left:50px;','return'=>true));
					                                            $scost = "";
					                                            if($submod['price'] > 0 )
					                                                $scost = $CI->make->span($submod['qty']*$submod['price'],array('class'=>'cost','return'=>true));
					                                            $CI->make->li($sname." ".$scost);
					                                            $price += $submod['price'];
					                                        }
					                                    }
					                                }
						                        }



						                    }
						                    $total += $opt['qty'] * $price  ;
						                }
						                if(count($charges) > 0){
						                    foreach ($charges as $charge_id => $ch) {
						                        $qty = $CI->make->span(fa('fa fa-tag'),array('class'=>'qty','return'=>true));
						                        $name = $CI->make->span($ch['name'],array('class'=>'name','return'=>true));
						                        $tx = $ch['amount'];
						                        if($ch['absolute'] == 0)
						                            $tx = $ch['amount']."%";
						                        $cost = $CI->make->span($tx,array('class'=>'cost','return'=>true));
						                        $CI->make->li($qty." ".$name." ".$cost);
						                    }
						                }
						            $CI->make->eUl();
						        $CI->make->eDiv();
						        $CI->make->append('<hr>');
						        $CI->make->sDiv(array('class'=>'foot-det','style'=>'margin-left:5px;margin-right:5px'));
									$CI->make->sDivCol(6);
										$CI->make->span('SubTotal:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'subtotal-txt'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('VAT:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'vat-txt'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('VAT Sales:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'vat-sales-txt'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('Discount Amount:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'discount-amount-txt'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('Charges Amount:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'charge-amount-txt'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('Amount Due:');
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->span('0.00',array("style"=>"float:right",'id'=>'amount-due-txt'));
									$CI->make->eDivCol();
									// $CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
									// $lt_txt = "";
									// if($local_tax > 0){
									// 	$lt_txt = 'LOCAL TAX: <span id="local-tax-txt">0.00</span>';
									// }
									// $CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span> '.$lt_txt,array('class'=>'receipt text-center'));
								$CI->make->eDiv();
						        $CI->make->H(5,'TOTAL: PHP '.num($totals['total']),array('class'=>'receipt text-center'));
						        // $lt_txt = "";
						        // if($totals['local_tax'] > 0){
						        // 	$lt_txt = " LOCAL TAX: ".num($totals['local_tax']);
						        // }
						        // $CI->make->H(4,'DISCOUNT: '.num($totals['discount']).$lt_txt,array('class'=>'receipt text-center'));
							$CI->make->eDiv();
						$CI->make->eBoxBody();
						$CI->make->sBoxFoot();
							$CI->make->sDivRow();
								if($ord['balance'] == 0){
									$CI->make->sDivCol(4,'left');
									$CI->make->button(fa('fa-bars fa-lg fa-fw').' Transactions',array('id'=>'transactions-btn','class'=>'btn-block settle-btn double','disabled'=>'true'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-reply fa-lg fa-fw').' Recall',array('id'=>'recall-btn','type'=>$ord['type'],'sale'=>$ord['gc_id'],'class'=>'btn-block settle-btn-orange double','disabled'=>'true'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','type'=>$ord['type'],'class'=>'btn-block settle-btn-red double'));
									$CI->make->eDivCol();
								}else{
									// $CI->make->sDivCol(3,'left');
									// 	$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' Loyalty',array('id'=>'loyalty-btn','type'=>$ord['type'],'class'=>'btn-block settle-btn-green double'));
									// $CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-bars fa-lg fa-fw').' Transactions',array('id'=>'transactions-btn','class'=>'btn-block settle-btn-orange settle-btn double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-reply fa-lg fa-fw').' Recall',array('id'=>'recall-btn','type'=>$ord['type'],'sale'=>$ord['gc_id'],'class'=>'btn-block settle-btn-teal double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(4,'left');
										$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','type'=>$ord['type'],'class'=>'btn-block settle-btn-red double'));
									$CI->make->eDivCol();

									// $CI->make->sDivCol(3,'left');
									// if(round($ord['amount'],2) == round($ord['balance'],2)){
									// 	$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' Discount',array('id'=>'discount-btn','type'=>$ord['type'],'sale'=>$ord['gc_id'],'class'=>'btn-block settle-btn-green double'));
									// }else{
									// 	$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' Discount',array('id'=>'discount-btn','type'=>$ord['type'],'sale'=>$ord['gc_id'],'class'=>'btn-block settle-btn-green double','disabled'=>'disabled'));
									// }
									// $CI->make->eDivCol();
								}
							$CI->make->eDivRow();
						$CI->make->eBoxFoot();
					$CI->make->eBox();
				$CI->make->eDivCol();

				$CI->make->sDivCol(1,'left',0,array('class'=>'settle-mid-panel'));
					$CI->make->sDivRow(array('class'=>'settle-right h-scroll','style'=>'max-height:500px;overflow:auto;padding:0px'));					

						// $CI->make->sDiv(array('class'=>'pay-search hidden','style'=>''));
									// $CI->make->input(null,'search-payment',null,null,array(),fa('fa fa-search')."");

						foreach($payment_group as $pg){
							$CI->make->sDivCol('','left',0,array("style"=>'','id'=>'pg-div'));
							$params = array('id'=>$pg->payment_group_id.'-btn','class'=>'btn-block settle-btn-teal double pgbuttons');
							if($ord['balance'] == 0){
								$params['disabled']='disabled';
							}

							$CI->make->button($pg->code,$params);
							$CI->make->eDivCol();
						}

						$npayment_types = array();

						foreach ($payment_types as $id => $value) {
							$npayment_types[] = $value;
						}


						$npayment_types[] = (object) array('payment_group_id'=>'1','payment_code'=>'credit-card','description'=>'Credit Card');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'debit-card','description'=>'DEBIT CARD');
						$npayment_types[] = (object) array('payment_group_id'=>'2','payment_code'=>'gift-cheque','description'=>'GIFT CHEQUE');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'cust-deposits','description'=>'CUSTOMER DEPOSIT');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'sign-chit','description'=>'SIGN CHIT');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'check','description'=>'CHECK');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'gcash','description'=>'GCASH');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'paymaya','description'=>'PAYMAYA');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'wechat','description'=>'WeChat');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'alipay','description'=>'Alipay');
						$npayment_types[] = (object) array('payment_group_id'=>'6','payment_code'=>'foodpanda','description'=>'Food Panda');

						if(MALL_ENABLED && MALL == "megamall"){
							$npayment_types[] =  (object) array('payment_group_id'=>'6','payment_code'=>'smac-card','description'=>'SMAC');
							$npayment_types[] =  (object) array('payment_group_id'=>'6','payment_code'=>'eplus-card','description'=>'E-Plus');
							$npayment_types[] =  (object) array('payment_group_id'=>'6','payment_code'=>'online-deal','description'=>'Online');
						}

						if(round($ord['balance'],2)==round($ord['amount'],2)){
							$npayment_types[] =  (object) array('payment_group_id'=>'6','payment_code'=>'online-payment','description'=>'ONLINE PAYMENT');
						}

						foreach ($npayment_types as $id => $value) {
							$text = $value->description;
							$payment_session[$value->payment_code] = array('text'=>$text,'is_new'=>'new');
						}

						$CI->session->set_userData('payment_session',$payment_session);
						// print_r($payment_session);	
						// usort($npayment_types, build_sorter_object('description'));
						
						// foreach ($npayment_types as $id => $value) {
						// 	$pg=$value->payment_group_id.'-btn';

						// 	$text = $value->description;

						// 	$CI->make->sDivCol('','left',0,array("style"=>'display:none','class'=>'ptype '. $pg));
						// 		$CI->make->button($text,array('id'=>$value->payment_code.'-btn','class'=>'btn-block settle-btn-green double payment-btns pbuttons media-btn-height','ref'=>$value->payment_code));
						// 	$CI->make->eDivCol();
						// 	$payment_session[$value->payment_code] = array('text'=>$text,'is_new'=>'new');
						// }

						$CI->make->sDivCol('','left',0,array("style"=>'display:none','id'=>'ptype-div'));
						$CI->make->eDivCol();

	// exit;
						

					$CI->make->eDivRow();

					$CI->make->sDivRow();
						$CI->make->sDivCol('','left',0,array("style"=>'display:none','class'=>'nav-div'));
								$CI->make->button('PREV',array('id'=>'sprev-btn','class'=>'btn-block settle-btn-teal double'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow();
						$CI->make->sDivCol('','left',0,array("style"=>'display:none','class'=>'nav-div'));
								$CI->make->button('NEXT',array('id'=>'snext-btn','class'=>'btn-block settle-btn-teal double'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();

					$CI->make->sDivRow();
						$CI->make->sDivCol('','left',0,array("style"=>'display:none','class'=>'nav-div'));
								$CI->make->button('BACK',array('id'=>'prev-btn','class'=>'btn-block settle-btn-teal double'));
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->hidden('page_value','1');
					$CI->make->hidden('p_group_id','1');

				$CI->make->eDivCol();

				$CI->make->sDivCol(5,'left',0,array('class'=>'settle-right settle-right-panel'));
				if($ord['paid'] == 1){
					$CI->make->sBox('default',array('class'=>'loads-div select-payment-div box-solid bg-dark-green','style'=>'height:531px;overflow:auto;'));
					// $CI->make->sDiv(array('style'=>'height:440px;overflow:auto;'));
						// $CI->make->sBoxHead(array('class'=>'bg-dark-green'));
						// 	$CI->make->boxTitle('&nbsp;');
						// $CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-dark-green'));
								//PAYMENT BUTTONS
								$CI->make->sDiv(array('class'=>'pay-search hidden','style'=>''));
									$CI->make->input(null,'search-payment',null,null,array(),fa('fa fa-search')." Search Payment");
								$CI->make->eDiv();
								$buttons = array("cash"	=> "CASH",
												// "online-payment"	=> "ONLINE PAYMENT",
												 "credit-card"	=> "CREDIT CARD",
												 "debit-card"	=> "DEBIT CARD",
												 // "cod"	=> "CASH ON DELIVERY",
												 "gift-cheque"	=> "GIFT CHEQUE",
												 // "coupon"	=> fa('fa-tags fa-lg fa-fw')."<br> Coupon",
												 // "smac-card"		=> fa('fa-credit-card fa-lg fa-fw')."<br> SMAC",
												 // "eplus-card"		=> fa('fa-credit-card fa-lg fa-fw')."<br> E-Plus",
												 // "online-deal"		=> fa('fa-credit-card fa-lg fa-fw')."<br> Online",
												 "cust-deposits"	=> "CUSTOMER DEPOSIT",
												 "sign-chit"	=> "SIGN CHIT",
												 // "product-test"	=> fa('fa-map-marker fa-lg fa-fw')."<br> PRODUCT TEST",
												 "check"	=> "CHECK",
												 // "check"	=> fa('fa-check-square-o fa-lg fa-fw')."<br> CHECK"
												 // "loyalty-card"	=> fa('fa-credit-card fa-lg fa-fw')."<br> Loyalty Card",
												 "gcash"	=> "GCASH",
												 "paymaya"	=> "PAYMAYA",
												 // "pmayaewallet"	=> "PAYMAYA E-WALLET",
												 // "pmayacredcard"	=> fa('fa-credit-card fa-lg fa-fw')."<br> PAYMAYA CREDIT CARD",
												 "wechat"	=> "WeChat",
												 "alipay"	=> "Alipay",
												 //PICC
												 // "picc"	=> fa('fa-credit-card fa-lg fa-fw')."<br> PICC Charge",
												 //FOODPANDA
												 "foodpanda"	=> "Food Panda",
												 // "egift"	=> "E-GIFT",
												 // "paypal"	=> "Paypal",
												 // "grabfood"	=> "GRABFOOD",
												 // "lalafood"	=> "LALAFOOD",
												 // "grabmart"	=> "GRABMART",
												 // "pickaroo"	=> "PICK A ROO",
												 // "mofopaymongo"	=> "MOFO PAYMONGO",
												 // "grabpaybdo"	=> "GRABPAY BDO",
												 // "tmgbtransfer"	=> "TMG BANKTRANSFER",
												 // "fppickupcash"	=> "FP PICKUP CASH",
												 // "fppickonlne"	=> "FP PICKUP ONLINE",
												 // "gfselfpickup"	=> "GRABFOOD SELF PICKUP",
												 // "receivables"	=> "RECEIVABLES",
												 // "hlmogobt"	=> "HL MOGO BT",
												 // "hlmogocash"	=> "HL MOGO CASH",
												 // "hlmogogcash"	=> "HL MOGO GCASH",
												 // "mofopmaya"	=> "MOFO PAYMAYA",
												 // "fasttrack"	=> "FAST TRACK",
												 // "mofocash"	=> "MOFO CASH",
												 // "dineinpmayacrd"	=> "DINE IN PAYMAYA CARD",
												 // "dineoutpmayacrd"	=> "DINE OUT PAYMAYA CARD",
												 // "smmallonline"	=> "SM MALLS ONLINE",
												 // "mofopmayadinein"	=> "MOFO PAYMAYA DINEIN",
												 // "mofocashdinein"	=> "MOFO CASH DINEIN",
												 // "bdocard"	=> "BDO CARD",
												 // "mofogcash"	=> "MOFO GCASH",
												 // "sendbill"	=> "SEND BILL",
												 // "bdopay"	=> "BDO PAY",
												 // "reservation"	=> "RESERVATION",
												 );
								if(MALL_ENABLED && MALL == "megamall"){
									$buttons["smac-card"] =  fa('fa-credit-card fa-lg fa-fw')."<br> SMAC";
									$buttons["eplus-card"] =  fa('fa-credit-card fa-lg fa-fw')."<br> E-Plus";
									$buttons["online-deal"] =  fa('fa-credit-card fa-lg fa-fw')."<br> Online";
								}

								if($ord['balance']==$ord['amount']){
									$buttons["online-payment"] =  "ONLINE PAYMENT";
								}

								$CI->make->sDivRow();
									// $CI->make->sDivCol(6,'left',0,array("style"=>'margin-bottom:10px;'));
									// 	$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
									// 	if(count($discounts) > 0 ){
									// 		foreach ($discounts as $res) {
									// 			$CI->make->button(strtoupper($res->disc_code)." ".strtoupper($res->disc_name),array('ref'=>$res->disc_id,'opt'=>$res->disc_rate."-".$res->disc_code,'class'=>'disc-btns btn-block settle-btn-green double'));
									// 		}
									// 	}
									// $CI->make->eDivCol();
										// $CI->make->H(3,'SELECT PAYMENT METHOD',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
										
										// if($ord['paid'] == 1){
										// 	$CI->make->H(3,'ALREADY PAID',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
										// }else{
										// 	if(count($buttons) > 12){
										// 		foreach ($buttons as $id => $text) {
										// 			$CI->make->sDivCol(3,'left',0,array("style"=>'margin-bottom:10px;'));
										// 				$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block settle-btn-green double'));
										// 			$CI->make->eDivCol();
										// 		}
										// 	}else{
										// 		foreach ($buttons as $id => $text) {
										// 			$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										// 				$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block settle-btn-green double'));
										// 			$CI->make->eDivCol();
										// 		}
										// 	}
										// }
										// $CI->make->sDivRow(array('id'=>'search-btn-div'));
										// $CI->make->eDivRow();

										$CI->make->sDivCol(12,'left',0,array("style"=>'margin-bottom:10px;','id'=>'search-btn-div'));
										$CI->make->eDivCol();

										
											$CI->make->H(3,'ALREADY PAID',array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
										

											// $cbtns = count($buttons);
											// $cptypes = count($payment_types);

											// $cols = 4;
											// if($cbtns+$cptypes > 12){
											// 	$cols = 3;
											// }

											// $payment_session = array();

											// foreach ($buttons as $id => $text) {
											// 	$CI->make->sDivCol($cols,'left',0,array("style"=>'margin-bottom:10px;'));
											// 		$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block settle-btn-green double pbuttons'));
											// 	$CI->make->eDivCol();

											// 	$payment_session[$id] = array('text'=>$text,'is_new'=>'old');
											// }
											// foreach ($payment_types as $id => $value) {
											// 	$text = $value->description;

											// 	$CI->make->sDivCol($cols,'left',0,array("style"=>'margin-bottom:10px;'));
											// 		$CI->make->button($text,array('id'=>$value->payment_code.'-new-btn','class'=>'btn-block settle-btn-green double payment-btns pbuttons','ref'=>$value->payment_code));
											// 	$CI->make->eDivCol();
											// 	$payment_session[$value->payment_code] = array('text'=>$text,'is_new'=>'new');
											// }

											// $CI->session->set_userData('payment_session',$payment_session);

											

										


								$CI->make->eDivRow();

						$CI->make->eBoxBody();
					$CI->make->eBox();

					}else{
						$CI->make->input(null,'search-payment',null,null,array(),fa('fa fa-search')." Search Payment");
						$CI->make->sBox('default',array('class'=>'loads-div cash-payment-div box-solid media-settle-cash-payment'));
												$CI->make->sBoxHead(array('class'=>'bg-green'));
													$CI->make->boxTitle(' CASH PAYMENT',array('class'=>'text-center'));
												$CI->make->eBoxHead();
												$CI->make->sBoxBody(array('class'=>'bg-red-white'));
													$CI->make->sDivRow();
														$CI->make->sDivCol(3);
															$CI->make->sDiv(array('class'=>'shorcut-btns'));
																$buttons = array(
																			 "5"	=> 'PHP 5',
																			 "10"	=> 'PHP 10',
																			 "20"	=> 'PHP 20',
																			 "50"	=> 'PHP 50',
																			 "100"	=> 'PHP 100',
																			 "200"	=> 'PHP 200',
																			 "500"	=> 'PHP 500',
																			 "1000"	=> 'PHP 1000'
																			 );
																$CI->make->sDivRow(array('style'=>'margin-top:10px;margin-left:10px;'));
																	foreach ($buttons as $id => $text) {
																			$CI->make->sDivCol(12,'left',0);
																				$CI->make->button($text,array('val'=>$id,'class'=>'amounts-btn btn-block settle-btn-red-gray'));
																			$CI->make->eDivCol();
																	}
																$CI->make->eDivRow();
															$CI->make->eDiv();
														$CI->make->eDivCol();
														$CI->make->sDivCol(9);
															$CI->make->append(onScrNumDotPad('cash-input','cash-enter-btn'));
														$CI->make->eDivCol();
													$CI->make->eDivRow();
												$CI->make->eBoxBody();
												$CI->make->sBoxFoot();
													$CI->make->sDivRow();
														$CI->make->sDivCol(6,'left');
															$CI->make->button('Exact Amount',array('id'=>'cash-exact-btn','amount'=>num($ord['balance']),'class'=>'btn-block settle-btn double'));
														$CI->make->eDivCol();
														$CI->make->sDivCol(6,'left');
															$CI->make->button('Next Amount',array('id'=>'cash-next-btn','amount'=>num(round($ord['balance'])),'class'=>'btn-block settle-btn-red-gray double'));
														$CI->make->eDivCol();
														// $CI->make->sDivCol(4,'left');
														// 	$CI->make->button(fa('fa-reply fa-lg fa-fw').' Change Method',array('id'=>'cancel-cash-btn','class'=>'btn-block settle-btn-red double'));
														// $CI->make->eDivCol();
													$CI->make->eDivRow();
												$CI->make->eBoxFoot();
											$CI->make->eBox();
					}

					$CI->make->arCustomerDrop('','customers',null,null,array('class'=>'','style'=>'display:none;'));

					$paymaya_ref = '';
					$paymaya_display = 'none';
					$paymaya_error  = '';

					if($CI->session->userdata('online_payment_ref') && isset($_GET['status']) && $_GET['status']=='success'){
						$paymaya_ref = $CI->session->userdata('online_payment_ref');
					}else{
						$paymaya_ref = $CI->session->userdata('online_payment_ref');

						if(in_array($paymaya_ref, array('error1','error2'))){						
							$paymaya_error  = $paymaya_ref;							
						}
						$paymaya_ref = '';
					}					

					
					if($paymaya_ref != ''){
						$paymaya_display = 'block';
					}

					foreach ($payment_types as $id => $value) {

							$fields = $CI->settings_model->get_payment_type_fields(null,$value->payment_id);

						// if($value->payment_code != 'cash' && $value->payment_code != 'credit' && $value->payment_code != 'debit' && $value->payment_code != 'chit' && $value->payment_code != 'gc'){
							$CI->make->sBox('default',array('class'=>'loads-div '.$value->payment_code.'-payment-div box-solid new-payment-div','style'=> $value->payment_code=='paymaya' ? 'display:'.$paymaya_display :'display:none'));

							$CI->make->sBoxHead(array('class'=>'bg-green'));
								$CI->make->boxTitle($value->description.' PAYMENT');
							$CI->make->eBoxHead();
							$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
								$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
									$CI->make->sDivCol(6);
										$CI->make->hidden('new_payment','yes');
										if(in_array($value->payment_code, array('arclearingbilling','arclearingpromo'))){
											$CI->make->input('Customer','full_name',iSetObj($det,'full_name'),'Customer Name',array('class'=>'full_name rOkay key-ins'),fa('fa-user'));
										}										
										foreach($fields as $id => $val){
											if($val->inactive != 1){
												$CI->make->input($val->field_name,$value->payment_code.'-'.$val->field_id,'','',array('maxlength'=>'100',
													'value'=>$value->payment_code == 'paymaya' ? $paymaya_ref : '',
													'paymaya_ref'=>$value->payment_code == 'paymaya' ? $paymaya_ref : '',
													'paymaya_error'=>$value->payment_code == 'paymaya' ? $paymaya_error : '',
													'class'=>$value->payment_code.'-field',
													'style'=>
														'width:100%;
														height:100%;
														font-size:34px;
														font-weight:bold;
														text-align:right;
														border:none;
														border-radius:5px !important;
														box-shadow:none;
														',
													
													)
												);
											}
										}

										// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
										// 	'style'=>
										// 		'width:100%;
										// 		height:100%;
										// 		font-size:34px;
										// 		font-weight:bold;
										// 		text-align:right;
										// 		border:none;
										// 		border-radius:5px !important;
										// 		box-shadow:none;
										// 		',
										// 	)
										// );
										$CI->make->input('Amount',$value->payment_code.'-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
											'class'=>$value->payment_code.'-field amt',
											// 'readOnly'=>'readOnly',
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
											)
										);
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->append(onScrNumOnlyTarget(
											'tbl-'.$value->payment_code.'-new-target',
											'#'.$value->payment_code.'-amt',
											$value->payment_code.'-enter-new-btn',
											// 'cancel-'.$value->payment_code.'-btn',
											'cancel-'.$value->payment_code.'-new-btn',
											'Change method',
											true,
										    'new_payment',
										    '',
										    $value->payment_code));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eBoxBody();



							$CI->make->eBox();
						// }
					}

					

					$CI->make->sBox('default',array('class'=>'loads-div debit-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));									
							$CI->make->boxTitle(' DEBIT PAYMENT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$buttons = array(
									"Master Card"	=> fa('fa-cc-mastercard fa-2x')."<br/>Master Card",
									"VISA"	=> fa('fa-cc-visa fa-2x')."<br/>VISA",
									// "AmEx"	=> fa('fa-cc-amex fa-2x')."<br/>American Express",
									"Discover"	=> fa('fa-cc-discover fa-2x')."<br/>Discover",
									// "JCB"	=> fa('fa-cc-jcb fa-2x')."<br/>JCB",
									// "Diners"	=> fa('fa-cc-diners-club fa-2x')."<br/>Diners",
									"BPI"	=> fa('fa-credit-card fa-2x')."<br/>BPI",
									"BDO"	=> fa('fa-credit-card fa-2x')."<br/>BDO",
									"BDOEPS"	=> fa('fa-credit-card fa-2x')."<br/>BDO-EPS",

								);
								foreach ($buttons as $id => $text) {
									$CI->make->sDivCol(2,'left',0,array('style'=>'padding:0;margin:0'));
										$CI->make->button($text,array('value'=>$id,'class'=>'debit-type-btn double settle-btn-teal btn-block'));
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->hidden('debit-type-hidden','');
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDivCol(6);
									$CI->make->input('Card #','debit-card-num','','',array('maxlength'=>'30','class'=>'disable-input-enter',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','debit-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										// 'readOnly'=>'true',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Approval Code','debit-app-code','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-debit-target',
										'#debit-card-num',
										'debit-enter-btn',
										'cancel-debit-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div credit-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' CREDIT PAYMENT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$buttons = array(
									"Master Card"	=> fa('fa-cc-mastercard fa-2x')."<br/>Master Card",
									"VISA"	=> fa('fa-cc-visa fa-2x')."<br/>VISA",
									// "AmEx"	=> fa('fa-cc-amex fa-2x')."<br/>American Express",
									"Discover"	=> fa('fa-cc-discover fa-2x')."<br/>Discover",
									// "JCB"	=> fa('fa-cc-jcb fa-2x')."<br/>JCB",
									// "Diners"	=> fa('fa-cc-diners-club fa-2x')."<br/>Diners",
									"BPI"	=> fa('fa-credit-card fa-2x')."<br/>BPI",
									"BDO"	=> fa('fa-credit-card fa-2x')."<br/>BDO",
									"BDOEPS"	=> fa('fa-credit-card fa-2x')."<br/>BDO-EPS",

								);
								foreach ($buttons as $id => $text) {
									$CI->make->sDivCol(2,'left',0,array('style'=>'padding:0;margin:0'));
										$CI->make->button($text,array('value'=>$id,'class'=>'credit-type-btn double settle-btn-teal btn-block'));
									$CI->make->eDivCol();
								}
							$CI->make->eDivRow();
							$CI->make->sDivRow(array('style'=>'margin:auto 0;padding:10px 0 8px;'));
								$CI->make->sDivCol(6,'left');
									$CI->make->hidden('credit-type-hidden','');
									$CI->make->input('Card #','credit-card-num','','',array('maxlength'=>'30',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Approval Code','credit-app-code','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','credit-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										// 'readOnly'=>'true',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6,'left');
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-credit-target',
										'#credit-card-num',
										'credit-enter-btn',
										'cancel-credit-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div gc-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' GIFT CHEQUE',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;padding:10px 0 8px;'));
								$CI->make->sDivCol(6);
									$CI->make->hidden('hid-validation',GIFTCHEQUE_VALIDATION);
									$CI->make->hidden('hid-gc-id');
									$CI->make->hidden('hid-gc-id-to');

									$gc_list=array();
									$gc_list['Select Gift Cheque']='-1';
									if(count($gc_types) > 0){
										foreach($gc_types as $gc_type){
											$gc_list[$gc_type->description_id]=$gc_type->description_id;
										}
									}
									

									$CI->make->select('Gift Cheque Type','gc-type',$gc_list,null,array());

									if(GIFTCHEQUE_VALIDATION == true){
										$CI->make->input('Gift Cheque code From','gc-code','','',array(
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
											)
										);

										$CI->make->input('Gift Cheque code To','gc-code-to','','',array(
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
											)
										);

										$CI->make->input('Amount','gc-amount','','',array('readonly'=>'readonly',
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
												'class'=>'hidden'
											)
										);

										$CI->make->button(fa('fa-plus fa-lg fa-fw').' Add Series',array('id'=>'series-btn','class'=>'btn-block settle-btn-orange double'));
									}
									else{
										$CI->make->input('Gift Cheque code From','gc-code','','',array(
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
											)
										);

										$CI->make->input('Gift Cheque code To','gc-code-to','','',array(
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
												'class'=>'hidden'
											)
										);

										$CI->make->button(fa('fa-plus fa-lg fa-fw').' Add Series',array('id'=>'series-btn','class'=>'btn-block settle-btn-orange double'));

										$CI->make->input('Amount','gc-amount','','',array(
											'style'=>
												'width:100%;
												height:100%;
												font-size:34px;
												font-weight:bold;
												text-align:right;
												border:none;
												border-radius:5px !important;
												box-shadow:none;
												',
											)
										);										
									}
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-gc-target',
										'#gc-code',
										'gc-enter-btn',
										'cancel-gc-btn',
										'Change method',false));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div after-payment-div box-solid media-after-cash-payment'));
						$CI->make->sBoxHead(array('class'=>'bg-dark-green'));
							$CI->make->boxTitle('&nbsp;');
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-dark-green'));
							$CI->make->sDiv(array('class'=>'body after-payment-body'));
								$CI->make->H(3,'AMOUNT TENDERED: PHP '.strong('<span id="amount-tendered-txt"></span>'),array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
								$CI->make->H(3,'CHANGE DUE: PHP '.strong('<span id="change-due-txt"></span>'),array('class'=>'text-center receipt','style'=>'margin-top:0;margin-bottom:25px;padding:0;color:#fff'));
							$CI->make->eDiv();
							$CI->make->sDivRow();
								$CI->make->sDivCol(3,'left');
									$CI->make->button(fa('fa-plus fa-lg fa-fw').'&nbsp;Additional Payment',array('id'=>'add-payment-btn','class'=>'btn-block settle-btn-teal double','style'=>'height:70px'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3,'left');
									$CI->make->button(fa('fa-print fa-lg fa-fw').' Print Receipt',array('id'=>'print-btn','class'=>'btn-block settle-btn-orange double','style'=>'height:70px','ref'=>$ord['gc_id']));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3,'left');
									$CI->make->button(fa('fa-check fa-lg fa-fw').' Finished',array('id'=>'finished-btn','class'=>'btn-block settle-btn-green double','style'=>'height:70px'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3,'left');
									$CI->make->button(fa('fa-print fa-lg fa-fw').'Print PDF',array('id'=>'print-pdf-btn','class'=>'btn-block settle-btn-red double','style'=>'height:70px','ref'=>$ord['gc_id']));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div transactions-payment-div box-solid'));
						$CI->make->sBoxHead(array('class'=>'bg-dark-green'));
							$CI->make->boxTitle('Transactions',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-red-white'));
							$CI->make->sDiv(array('class'=>'body'));

							$CI->make->eDiv();
							$CI->make->sDivRow();
								$CI->make->sDivCol(12,'left');
									$CI->make->button(fa('fa-times fa-lg fa-fw').' CLOSE',array('id'=>'','class'=>'btn-block settle-btn-orange double transactions-close-btn'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div coupon-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle('COUPON',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(6);
									$CI->make->hidden('hid-coupon-id');
									$CI->make->input('Coupon code','coupon-code','','',array(
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','coupon-amount','','',array('readonly'=>'readonly',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-coupon-target',
										'#coupon-code',
										'coupon-enter-btn',
										'cancel-coupon-btn',
										'Change method',false));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div sign-chit-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle('Sign Chit',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->H(3,'Enter Manager PIN',array('style'=>'text-align:center;margin:0px;'));
							$CI->make->sDivRow();
								$CI->make->sDivCol(12);
									$pad = onScrNumPwdPad('manager-call-pin-login','manager-submit-btn');
									$CI->make->append($pad);
								$CI->make->eDivCol();
								$CI->make->sDivCol(3);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->button(fa('fa-reply').' Change Method',array('id'=>'cancel-signchit-btn','class'=>'btn-block settle-btn-orange double','style'=>''));
								$CI->make->eDivCol();
								$CI->make->sDivCol(3);
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					// $CI->make->sBox('default',array('class'=>'loads-div product-test-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle('Product Test',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->H(3,'Enter Manager PIN',array('style'=>'text-align:center;margin:0px;'));
					// 		$CI->make->sDivRow();
					// 			$CI->make->sDivCol(12);
					// 				$pad = onScrNumPwdPad('product-call-pin-login','product-submit-btn');
					// 				$CI->make->append($pad);
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();

					// $CI->make->sBox('default',array('class'=>'loads-div smac-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' SMAC PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Card #','smac-card-num','','',array('maxlength'=>'30',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','smac-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-smac-target',
					// 					'#smac-card-num',
					// 					'smac-enter-btn',
					// 					'cancel-smac-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					
					// $CI->make->sBox('default',array('class'=>'loads-div eplus-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' E-Plus PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Card #','eplus-card-num','','',array('maxlength'=>'30',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','eplus-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-eplus-target',
					// 					'#eplus-card-num',
					// 					'eplus-enter-btn',
					// 					'cancel-eplus-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();

					// $CI->make->sBox('default',array('class'=>'loads-div online-deal-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' Online Deal PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','online-deal-card-num','','',array('maxlength'=>'30',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','online-deal-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-online-deal-target',
					// 					'#online-deal-card-num',
					// 					'online-deal-enter-btn',
					// 					'cancel-online-deal-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div cust-deposits-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle('Customer Deposits PAYMENT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDivCol(6);
								// hide only for ipos_max
									// $CI->make->input('Search Customer','cust-deposits-search','','',array(
									// 	'style'=>
									// 		'width:100%;
									// 		height:100%;
									// 		font-size:34px;
									// 		font-weight:bold;
									// 		text-align:left;
									// 		border:none;
									// 		border-radius:5px !important;
									// 		box-shadow:none;
									// 		',
									// 	)
									// );
									// $CI->make->sDiv(array('class'=>'listings','style'=>'margin-bottom:10px;background-color:#fff'));
									// 	$CI->make->sUl(array('id'=>'cust-search-list','style'=>'height:260px;overflow:auto;'));
									// 	$CI->make->eUl();
									// $CI->make->eDiv();
									
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->input('Customer','cust-deposits-cust-name',$ord['cname'],'',array('readOnly'=>'readOnly','maxlength'=>'10',
										'style'=>
											'width:100%;
											height:100%;
											font-size:24px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Customer Deposit Amount','cust-deposits-amt',$ord['depo_amount'],'',array('readOnly'=>'readOnly','maxlength'=>'10',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->hidden('cust-deposits-cust-id',null);
									$CI->make->button('ENTER',array('id'=>'cust-deposits-submit-btn','class'=>'btn-block settle-btn-green double','style'=>'margin-bottom:10px;'));
									$CI->make->button(fa('fa-reply').' Change Method',array('id'=>'cancel-cust-deposits-btn','class'=>'btn-block settle-btn-red double'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					// $CI->make->sBox('default',array('class'=>'loads-div loyalty-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' LOYALTY CARD PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->pwd('Card #','loyalty-card-num','','',array('maxlength'=>'30','class'=>'disable-input-enter',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','loyalty-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-loyalty-target',
					// 					'#loyalty-card-num',
					// 					'loyalty-enter-btn',
					// 					'cancel-loyalty-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();

					// $CI->make->sBox('default',array('class'=>'loads-div foodpanda-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PANDA PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Amount','foodpanda-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Order Code','order-code-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-foodpanda-target',
					// 					'#foodpanda-amt',
					// 					'foodpanda-enter-btn',
					// 					'cancel-foodpanda-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();

					//CHECK
					$CI->make->sBox('default',array('class'=>'loads-div check-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' CHECK PAYMENT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDivCol(6);
									$CI->make->input('Check #','check-card-num','','',array('maxlength'=>'30','class'=>'disable-input-enter',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Amount','check-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Bank','check-bank','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:left;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									$CI->make->input('Check Date','check-date','','',array('maxlength'=>'15',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:left;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-check-target',
										'#check-card-num',
										'check-enter-btn',
										'cancel-debit-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					//PICC
					// $CI->make->sBox('default',array('class'=>'loads-div picc-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PICC CHARGE',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;padding:10px 0 8px;'));
					// 			$CI->make->sDivCol(6,'left');
					// 				// $CI->make->hidden('credit-type-hidden','Master Card');
					// 				$CI->make->input('Name','picc-name','','',array('maxlength'=>'50',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Company','picc-company','','',array('maxlength'=>'50',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','picc-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'true',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6,'left');
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-picc-target',
					// 					'#picc-name',
					// 					'picc-enter-btn',
					// 					'cancel-picc-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//GCASH
					// $CI->make->sBox('default',array('class'=>'loads-div gcash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' GCASH PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','gcash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','gcash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-gcash-target',
					// 					'#gcash-amt',
					// 					'gcash-enter-btn',
					// 					'cancel-gcash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PAYMAYA
					

					// $CI->make->sBox('default',array('class'=>'loads-div paymaya-payment-div box-solid','style'=>'display:'. $paymaya_display));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PAYMAYA PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);									
					// 				$CI->make->input('Reference #','paymaya-num',$paymaya_ref,'',array('paymaya_ref'=>$paymaya_ref,'paymaya_error'=>$paymaya_error,'maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','paymaya-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-paymaya-target',
					// 					'#paymaya-amt',
					// 					'paymaya-enter-btn',
					// 					'cancel-paymaya-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//WE CHAT
					// $CI->make->sBox('default',array('class'=>'loads-div wechat-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' WeChat PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','wechat-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','wechat-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-wechat-target',
					// 					'#wechat-amt',
					// 					'wechat-enter-btn',
					// 					'cancel-wechat-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//ALIPAY
					// $CI->make->sBox('default',array('class'=>'loads-div alipay-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' ALIPAY PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','alipay-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','alipay-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-alipay-target',
					// 					'#alipay-amt',
					// 					'alipay-enter-btn',
					// 					'cancel-alipay-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//E-GIFT
					// $CI->make->sBox('default',array('class'=>'loads-div egift-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' E-GIFT PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('E-Gift Code','egift-code-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('E-Gift Ref Amount','egift-ref-amt','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Amount','egift-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-egift-target',
					// 					'#egift-amt',
					// 					'egift-enter-btn',
					// 					'cancel-egift-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PAYPAL
					// $CI->make->sBox('default',array('class'=>'loads-div paypal-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PAYPAL PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','paypal-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','paypal-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-paypal-target',
					// 					'#paypal-amt',
					// 					'paypal-enter-btn',
					// 					'cancel-paypal-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//GRAB PAYMENT
					// $CI->make->sBox('default',array('class'=>'loads-div grabfood-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' GRABFOOD PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Customer Name','cust-name','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Booking #','booking-no','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				$CI->make->input('Driver Name','driver','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','grabfood-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-grabfood-target',
					// 					'#grabfood-amt',
					// 					'grabfood-enter-btn',
					// 					'cancel-grabfood-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//LALAFOOD
					// $CI->make->sBox('default',array('class'=>'loads-div lalafood-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' LALAFOOD PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','lalafood-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','lalafood-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-lalafood-target',
					// 					'#lalafood-amt',
					// 					'lalafood-enter-btn',
					// 					'cancel-lalafood-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//GRABMART
					// $CI->make->sBox('default',array('class'=>'loads-div grabmart-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' GRABMART PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','grabmart-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','grabmart-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-grabmart-target',
					// 					'#grabmart-amt',
					// 					'grabmart-enter-btn',
					// 					'cancel-grabmart-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PICKAROO
					// $CI->make->sBox('default',array('class'=>'loads-div pickaroo-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PICKAROO PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','pickaroo-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','pickaroo-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-pickaroo-target',
					// 					'#pickaroo-amt',
					// 					'pickaroo-enter-btn',
					// 					'cancel-pickaroo-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PAYMONGO MOFO
					// $CI->make->sBox('default',array('class'=>'loads-div mofopaymongo-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO PAYMONGO PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofopaymongo-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofopaymongo-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					// 'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofopaymongo-target',
					// 					'#mofopaymongo-amt',
					// 					'mofopaymongo-enter-btn',
					// 					'cancel-mofopaymongo-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//GRABPAY BDO
					// $CI->make->sBox('default',array('class'=>'loads-div grabpaybdo-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' GRABPAY BDO PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','grabpaybdo-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','grabpaybdo-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-grabpaybdo-target',
					// 					'#grabpaybdo-amt',
					// 					'grabpaybdo-enter-btn',
					// 					'cancel-grabpaybdo-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PAYMAYA E-WALLET
					// $CI->make->sBox('default',array('class'=>'loads-div pmayaewallet-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PAYMAYA E-WALLET PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','pmayaewallet-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','pmayaewallet-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-pmayaewallet-target',
					// 					'#pmayaewallet-amt',
					// 					'pmayaewallet-enter-btn',
					// 					'cancel-pmayaewallet-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//PAYMAYA CREDIT CARD
					// $CI->make->sBox('default',array('class'=>'loads-div pmayacredcard-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' PAYMAYA CREDIT CARD PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','pmayacredcard-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','pmayacredcard-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-pmayacredcard-target',
					// 					'#pmayacredcard-amt',
					// 					'pmayacredcard-enter-btn',
					// 					'cancel-pmayacredcard-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//TMG BANKTRANSFER
					// $CI->make->sBox('default',array('class'=>'loads-div tmgbtransfer-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' TMG BANK TRANSFER PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','tmgbtransfer-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','tmgbtransfer-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-tmgbtransfer-target',
					// 					'#tmgbtransfer-amt',
					// 					'tmgbtransfer-enter-btn',
					// 					'cancel-tmgbtransfer-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//FP PICKUP CASH
					// $CI->make->sBox('default',array('class'=>'loads-div fppickupcash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' FP PICKUP CASH PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','fppickupcash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','fppickupcash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-fppickupcash-target',
					// 					'#fppickupcash-amt',
					// 					'fppickupcash-enter-btn',
					// 					'cancel-fppickupcash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//FP PICKUP ONLINE
					// $CI->make->sBox('default',array('class'=>'loads-div fppickonlne-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' FP PICKUP ONLINE PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','fppickonlne-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','fppickonlne-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-fppickonlne-target',
					// 					'#fppickonlne-amt',
					// 					'fppickonlne-enter-btn',
					// 					'cancel-fppickonlne-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//GF SELF PICKUP
					// $CI->make->sBox('default',array('class'=>'loads-div gfselfpickup-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' GF SELF PICKUP PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','gfselfpickup-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','gfselfpickup-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-gfselfpickup-target',
					// 					'#gfselfpickup-amt',
					// 					'gfselfpickup-enter-btn',
					// 					'cancel-gfselfpickup-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//RECEIVABLES
					// $CI->make->sBox('default',array('class'=>'loads-div receivables-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' RECEIVABLES',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','receivables-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','receivables-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-receivables-target',
					// 					'#receivables-amt',
					// 					'receivables-enter-btn',
					// 					'cancel-receivables-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//HL MOGO
					// $CI->make->sBox('default',array('class'=>'loads-div hlmogobt-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' HOTLINE MOGO BANK TRANSFER',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','hlmogobt-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','hlmogobt-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-hlmogobt-target',
					// 					'#hlmogobt-amt',
					// 					'hlmogobt-enter-btn',
					// 					'cancel-hlmogobt-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//HL MOGO CASH
					// $CI->make->sBox('default',array('class'=>'loads-div hlmogocash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' HOTLINE MOGO CASH',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','hlmogocash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','hlmogocash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-hlmogocash-target',
					// 					'#hlmogocash-amt',
					// 					'hlmogocash-enter-btn',
					// 					'cancel-hlmogocash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//HL MOGO GCASH
					// $CI->make->sBox('default',array('class'=>'loads-div hlmogogcash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' HOTLINE MOGO GCASH',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','hlmogogcash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','hlmogogcash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-hlmogogcash-target',
					// 					'#hlmogogcash-amt',
					// 					'hlmogogcash-enter-btn',
					// 					'cancel-hlmogogcash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//COD
					// $CI->make->sBox('default',array('class'=>'loads-div cod-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' COD PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('class'=>'bg-red-white'));
					// 		$CI->make->sDivRow();
					// 			$CI->make->sDivCol(3);
					// 				$CI->make->sDiv(array('class'=>'shorcut-btns'));
					// 					$buttons = array(
					// 								 "5"	=> 'PHP 5',
					// 								 "10"	=> 'PHP 10',
					// 								 "20"	=> 'PHP 20',
					// 								 "50"	=> 'PHP 50',
					// 								 "100"	=> 'PHP 100',
					// 								 "200"	=> 'PHP 200',
					// 								 "500"	=> 'PHP 500',
					// 								 "1000"	=> 'PHP 1000'
					// 								 );
					// 					$CI->make->sDivRow(array('style'=>'margin-top:10px;margin-left:10px;'));
					// 						foreach ($buttons as $id => $text) {
					// 								$CI->make->sDivCol(12,'left',0);
					// 									$CI->make->button($text,array('val'=>$id,'class'=>'amounts-btn-cod btn-block settle-btn-red-gray'));
					// 								$CI->make->eDivCol();
					// 						}
					// 					$CI->make->eDivRow();
					// 				$CI->make->eDiv();
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(9);
					// 				$CI->make->append(onScrNumDotPad('cod-input','cod-enter-btn'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// 	$CI->make->sBoxFoot();
					// 		$CI->make->sDivRow();
					// 			$CI->make->sDivCol(4,'left');
					// 				$CI->make->button('Exact Amount',array('id'=>'cod-exact-btn','amount'=>num($ord['balance']),'class'=>'btn-block settle-btn double'));
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(4,'left');
					// 				$CI->make->button('Next Amount',array('id'=>'cod-next-btn','amount'=>num(round($ord['balance'])),'class'=>'btn-block settle-btn-red-gray double'));
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(4,'left');
					// 				$CI->make->button(fa('fa-reply fa-lg fa-fw').' Change Method',array('id'=>'cancel-cod-btn','class'=>'btn-block settle-btn-red double'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxFoot();
					// $CI->make->eBox();
					//MOFO PAYMAYA
					// $CI->make->sBox('default',array('class'=>'loads-div mofopmaya-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO PAYMAYA PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofopmaya-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofopmaya-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofopmaya-target',
					// 					'#mofopmaya-amt',
					// 					'mofopmaya-enter-btn',
					// 					'cancel-mofopmaya-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//FAST TRACK
					// $CI->make->sBox('default',array('class'=>'loads-div fasttrack-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' FAST TRACK PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','fasttrack-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','fasttrack-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-fasttrack-target',
					// 					'#fasttrack-amt',
					// 					'fasttrack-enter-btn',
					// 					'cancel-fasttrack-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//MOFO CASH
					// $CI->make->sBox('default',array('class'=>'loads-div mofocash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO CASH PAYMENT',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofocash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofocash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofocash-target',
					// 					'#mofocash-amt',
					// 					'mofocash-enter-btn',
					// 					'cancel-mofocash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//DINE IN PAYMAYA CARD
					// $CI->make->sBox('default',array('class'=>'loads-div dineinpmayacrd-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' DINE IN PAYMAYA CARD',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','dineinpmayacrd-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','dineinpmayacrd-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-dineinpmayacrd-target',
					// 					'#dineinpmayacrd-amt',
					// 					'dineinpmayacrd-enter-btn',
					// 					'cancel-dineinpmayacrd-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//DINE OUT PAYMAYA CARD
					// $CI->make->sBox('default',array('class'=>'loads-div dineoutpmayacrd-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' DINE OUT PAYMAYA CARD',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','dineoutpmayacrd-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','dineoutpmayacrd-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-dineoutpmayacrd-target',
					// 					'#dineoutpmayacrd-amt',
					// 					'dineoutpmayacrd-enter-btn',
					// 					'cancel-dineoutpmayacrd-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//SM MALLLS ONLINE
					// $CI->make->sBox('default',array('class'=>'loads-div smmallonline-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' SM MALLS ONLINE',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','smmallonline-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','smmallonline-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-smmallonline-target',
					// 					'#smmallonline-amt',
					// 					'smmallonline-enter-btn',
					// 					'cancel-smmallonline-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//MOFO PAYMAYA DINEIN
					// $CI->make->sBox('default',array('class'=>'loads-div mofopmayadinein-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO PAYMAYA DINEIN',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofopmayadinein-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofopmayadinein-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofopmayadinein-target',
					// 					'#mofopmayadinein-amt',
					// 					'mofopmayadinein-enter-btn',
					// 					'cancel-mofopmayadinein-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//MOFO CASH DINEIN
					// $CI->make->sBox('default',array('class'=>'loads-div mofocashdinein-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO CASH DINEIN',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofocashdinein-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofocashdinein-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofocashdinein-target',
					// 					'#mofocashdinein-amt',
					// 					'mofocashdinein-enter-btn',
					// 					'cancel-mofocashdinein-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//BDO CARD
					// $CI->make->sBox('default',array('class'=>'loads-div bdocard-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' BDO CARD',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','bdocard-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','bdocard-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-bdocard-target',
					// 					'#bdocard-amt',
					// 					'bdocard-enter-btn',
					// 					'cancel-bdocard-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//MOFO GCASH
					// $CI->make->sBox('default',array('class'=>'loads-div mofogcash-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' MOFO GCASH',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','mofogcash-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','mofogcash-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-mofogcash-target',
					// 					'#mofogcash-amt',
					// 					'mofogcash-enter-btn',
					// 					'cancel-mofogcash-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//SEND BILL
					// $CI->make->sBox('default',array('class'=>'loads-div sendbill-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' SEND BILL',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','sendbill-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','sendbill-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-sendbill-target',
					// 					'#sendbill-amt',
					// 					'sendbill-enter-btn',
					// 					'cancel-sendbill-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//BDO PAY
					// $CI->make->sBox('default',array('class'=>'loads-div bdopay-payment-div box-solid','style'=>'display:none;'));
					// 	$CI->make->sBoxHead(array('class'=>'bg-green'));
					// 		$CI->make->boxTitle(' BDO PAY',array('class'=>'text-center'));
					// 	$CI->make->eBoxHead();
					// 	$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
					// 		$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->input('Reference #','bdopay-num','','',array('maxlength'=>'100',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 				// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
					// 				// 	'style'=>
					// 				// 		'width:100%;
					// 				// 		height:100%;
					// 				// 		font-size:34px;
					// 				// 		font-weight:bold;
					// 				// 		text-align:right;
					// 				// 		border:none;
					// 				// 		border-radius:5px !important;
					// 				// 		box-shadow:none;
					// 				// 		',
					// 				// 	)
					// 				// );
					// 				$CI->make->input('Amount','bdopay-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
					// 					'readOnly'=>'readOnly',
					// 					'style'=>
					// 						'width:100%;
					// 						height:100%;
					// 						font-size:34px;
					// 						font-weight:bold;
					// 						text-align:right;
					// 						border:none;
					// 						border-radius:5px !important;
					// 						box-shadow:none;
					// 						',
					// 					)
					// 				);
					// 			$CI->make->eDivCol();
					// 			$CI->make->sDivCol(6);
					// 				$CI->make->append(onScrNumOnlyTarget(
					// 					'tbl-bdopay-target',
					// 					'#bdopay-amt',
					// 					'bdopay-enter-btn',
					// 					'cancel-bdopay-btn',
					// 					'Change method'));
					// 			$CI->make->eDivCol();
					// 		$CI->make->eDivRow();
					// 	$CI->make->eBoxBody();
					// $CI->make->eBox();
					//Loyalty
					$CI->make->sBox('default',array('class'=>'loads-div loyalty-payment-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' LOYALTY PAYMENT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDivCol(6);
									$CI->make->input('Reference #','loyalty-num','','',array('maxlength'=>'100',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
									// $CI->make->input('E-Gift Code Approval','egift-code-approval','','',array('maxlength'=>'100',
									// 	'style'=>
									// 		'width:100%;
									// 		height:100%;
									// 		font-size:34px;
									// 		font-weight:bold;
									// 		text-align:right;
									// 		border:none;
									// 		border-radius:5px !important;
									// 		box-shadow:none;
									// 		',
									// 	)
									// );
									$CI->make->input('Amount','loyalty-amt',number_format($ord['balance'],2),'',array('maxlength'=>'10',
										// 'readOnly'=>'readOnly',
										'style'=>
											'width:100%;
											height:100%;
											font-size:34px;
											font-weight:bold;
											text-align:right;
											border:none;
											border-radius:5px !important;
											box-shadow:none;
											',
										)
									);
								$CI->make->eDivCol();
								$CI->make->sDivCol(6);
									$CI->make->append(onScrNumOnlyTarget(
										'tbl-loyalty-target',
										'#loyalty-amt',
										'loyalty-enter-btn',
										'cancel-loyalty-btn',
										'Change method'));
								$CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div choosedisc-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' SELECT DISCOUNT TYPE',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDiv(array('class'=>'option-lists'));
								$CI->make->sDivCol(12);
		                            $CI->make->button('TOTAL DISCOUNT',array('id'=>'add-discount-btn','class'=>'btn-block settle-btn-green'));
		                        $CI->make->eDivCol();
		                        $CI->make->sDivCol(12);
		                            $CI->make->button('LINE DISCOUNT',array('id'=>'add-discount-line-btn','class'=>'btn-block settle-btn-green'));
		                        $CI->make->eDivCol();

		                        $CI->make->sDivCol(12,'left');
									$CI->make->button(fa('fa-times fa-lg fa-fw').' CLOSE',array('id'=>'transactions-close-btn','class'=>'btn-block settle-btn-orange double'));
								$CI->make->eDivCol();
							$CI->make->eDiv();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div sel-discount-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' SELECT DISCOUNT',array('class'=>'text-center'));							
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDiv(array('class'=>'select-discounts-lists','style'=>'height:500px;overflow:auto'));
							$CI->make->eDiv();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div discount-div box-solid','style'=>'display:none;'));
						$CI->make->sBoxHead(array('class'=>'bg-green'));
							$CI->make->boxTitle(' SELECT DISCOUNT',array('class'=>'text-center'));
						$CI->make->eBoxHead();
						$CI->make->sBoxBody(array('style'=>'background-color:#F4EDE0;'));
							$CI->make->sDivRow(array('style'=>'margin:auto 0;'));
								$CI->make->sDiv();
									$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
									$CI->make->H(3,'RATE: % <span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
									$CI->make->sDiv(array('class'=>'discounts-lists'));
										 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
											 $CI->make->sDivCol(12);
										    	$guestN = null;
											    if(isset($typeCN[0]['guest']))
											    	$guestN = $typeCN[0]['guest'];
										 	 	$CI->make->input(null,'disc-guests',$guestN,'Total No. Of Guests',array('readOnly'=>'true'),fa('fa-user'));
										 	 $CI->make->eDivCol();
										 $CI->make->eDivRow();
										 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 // 	 $CI->make->sDivCol(4);
										 // 	 	$CI->make->button('ALL ITEMS',array('ref'=>'all','class'=>'disc-btn-row btn-block counter-btn-teal'));
										 // 	 $CI->make->eDivCol();
										 // 	 $CI->make->sDivCol(4);
										 // 	 	$CI->make->button('EQUALLY DIVIDED',array('ref'=>'equal','class'=>'disc-btn-row btn-block counter-btn-orange'));
										 // 	 $CI->make->eDivCol();
										 // 	 $CI->make->sDivCol(4);
										 // 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
										 // 	 $CI->make->eDivCol();
										 // $CI->make->eDivRow();
										 $CI->make->sForm("",array('id'=>'disc-form'));
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
											 	 $CI->make->sDivCol(12);
											 	 	$CI->make->input(null,'disc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
											 	 	$CI->make->hidden('disc-disc-id',null);
											 	 	$CI->make->hidden('disc-disc-rate',null);
											 	 	$CI->make->hidden('disc-disc-code',null);
											 	 	$CI->make->hidden('disc-no-tax',null);
											 	 	$CI->make->hidden('disc-fix',null);
											 	 $CI->make->eDivCol();
											 	 // $CI->make->sDivCol(6);
											 	 	// $CI->make->input(null,'disc-cust-guest',null,'No. Of Guest',array('class'=>'rOkay','ro-msg'=>'Add No. Of Guest for Discount'),fa('fa-male'));
											 	 // $CI->make->eDivCol();
											 $CI->make->eDivRow();
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
												 $CI->make->sDivCol(12);
											 	 	$CI->make->input(null,'disc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
											 	 $CI->make->eDivCol();
											 $CI->make->eDivRow();
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
												 $CI->make->sDivCol(12);
											 	 	$CI->make->input(null,'disc-cust-bday',null,'Birthday',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
											 	 $CI->make->eDivCol();
											 $CI->make->eDivRow();
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
												 $CI->make->sDivCol(12);
											 	 	$CI->make->input(null,'disc-cust-remarks',null,'Remarks',array('ro-msg'=>'Add Remarks'),fa('fa-calendar'));
											 	 $CI->make->eDivCol();
											 $CI->make->eDivRow();
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
												 $CI->make->sDivCol(12);
												 	// $CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
												 	$CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-line-btn','class'=>'btn-block settle-btn-green'));
											 	 $CI->make->eDivCol();
												 // $CI->make->sDivCol(6);
											 	//  	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
											 	//  $CI->make->eDivCol();
											 $CI->make->eDivRow();
											 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
												 $CI->make->sDivCol(12);
												 	// $CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc','class'=>'btn-block counter-btn-green'));
												 	$CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc-line','class'=>'btn-block settle-btn-green'));
											 	 $CI->make->eDivCol();
											 	 // 'ref'=>'equal',
											 $CI->make->eDivRow();
											 $CI->make->sDivRow();
												 $CI->make->sDivCol(12);
												 	$CI->make->sDiv(array('class'=>'disc-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
													$CI->make->eDiv();
											 	 $CI->make->eDivCol();
											 $CI->make->eDivRow();
										 $CI->make->eForm();
										 // $CI->make->sDivRow(array('style'=>'margin-top:20px;'));
										 // 	 $CI->make->sDivCol(12);
										 // 	 	$CI->make->button(fa('fa fa-plus fa-lg fa-fw').' SELECTED ITEM ONLY',array('ref'=>'item','class'=>'disc-btn-row btn-block counter-btn-green'));
										 // 	 	$CI->make->sUl(array('class'=>'item-disc-list','style'=>'margin-top:10px;'));
										 // 	 	$CI->make->eUl();
										 // 	 $CI->make->eDivCol();
										 // $CI->make->eDivRow();
									$CI->make->eDiv();
								$CI->make->eDiv();
							$CI->make->eDivRow();
						$CI->make->eBoxBody();
					$CI->make->eBox();

					$CI->make->sBox('default',array('class'=>'loads-div online-payment-message-div box-solid bg-dark-green','style'=>'overflow:auto; display:none'));
					// $CI->make->sDiv(array('style'=>'height:440px;overflow:auto;'));
						// $CI->make->sBoxHead(array('class'=>'bg-dark-green'));
						// 	$CI->make->boxTitle('&nbsp;');
						// $CI->make->eBoxHead();
						$CI->make->sBoxBody(array('class'=>'bg-dark-green'));
							$CI->make->H(3,'CONNECTING TO ONLINE PAYMENT. PLEASE WAIT.',array('class'=>'text-center receipt','style'=>'padding-top:25px;padding-bottom:25px;color:#fff'));
						$CI->make->eBoxBody();
					$CI->make->eBox();

				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function splitPage($type=null,$time=null,$sales_id=null,$ord=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter','sale'=>$sales_id));
			$CI->make->sDivRow();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;margin-top:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'bg-red','style'=>'padding:15px;'));
								$CI->make->H(5,'SPLIT ORDER',array('class'=>'headline text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
								if($type == 'dinein'){
									$CI->make->H(3,strtoupper(DINEINTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}elseif($type == 'takeout'){
									$CI->make->H(3,strtoupper(COUNTERTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}elseif($type == 'takeout'){
									$CI->make->H(3,strtoupper(TAKEOUTTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}else{
									$CI->make->H(3,strtoupper($type),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
									
								}
								$CI->make->H(5,$time,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$waiter_name = trim($ord['waiter_username']);
						        if($waiter_name != "")
						            $CI->make->H(5,'Food Server: '.$ord['waiter_username'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body split_order_div'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
								$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span>',array('class'=>'receipt text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(8,'left',0);
					$CI->make->sDiv(array('class'=>'counter-split-right','ref'=>''));
						$CI->make->sDivRow();
							$CI->make->sDivCol(4);
								$CI->make->button(fa('fa-flask fa-lg fa-fw').'<br> Item Split',array('id'=>'select-items-btn','ref'=>'select-items','class'=>'split-bys btn-block counter-btn-red-gray double'));
							$CI->make->eDivCol();
							$CI->make->sDivCol(4);
								$CI->make->button(fa('fa-bars fa-lg fa-fw').'<br> Number of Guest',array('id'=>'even-split-btn','ref'=>'even-split','class'=>'split-bys btn-block counter-btn-red-gray double'));
							$CI->make->eDivCol();
							// $CI->make->sDivCol(3);
							// 	$CI->make->button(fa('fa-users fa-lg fa-fw').'<br> Split By Guest',array('id'=>'split-by-guest-btn','ref'=>'split-by-guest','class'=>'split-bys btn-block counter-btn-red-gray double'));
							// $CI->make->eDivCol();
							if(LOCAVORE){
								$CI->make->sDivCol(2);
									$CI->make->button(fa('fa-save fa-lg fa-fw').'<br> Save',array('id'=>'save-split-btn','class'=>'btn-block counter-btn-green double','locavore'=>'yes'));
								$CI->make->eDivCol();
							}else{
								$CI->make->sDivCol(2);
									$CI->make->button(fa('fa-save fa-lg fa-fw').'<br> Save',array('id'=>'save-split-btn','class'=>'btn-block counter-btn-green double','locavore'=>'no'));
								$CI->make->eDivCol();
							}
							$CI->make->sDivCol(2);
								$CI->make->button(fa('fa-retweet fa-lg fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block counter-btn-orange double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDiv(array('class'=>'actions-div'));
							#SPLIT BY ITEMS
							$CI->make->sDiv(array('class'=>'select-items-div loads-div','style'=>'display:none;'));
								$CI->make->sDivRow();
									$CI->make->sDivCol(4,'left',0,array('id'=>'add-btn-div'));
										$CI->make->sDiv(array('style'=>'margin:50px;'));
											$CI->make->button(fa('fa-plus fa-lg fa-fw').'<br> Add Partition',array('id'=>'add-sel-block-btn','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDiv();
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'even-split-div loads-div','style'=>'display:none;'));
								$CI->make->sDivRow(array('style'=>'margin-top:20px;'));
									$CI->make->sDivCol(4,'left');
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'left');
										$CI->make->H(1,'2',array('style'=>'margin-top:25px;font-size:78px;','id'=>'even-spit-num'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(2,'left');
										$CI->make->button(fa('fa-caret-square-o-up fa-3x fa-fw'),array('id'=>'even-up-btn','num'=>'up','class'=>'btn-block counter-btn-red-gray double'));
										$CI->make->button(fa('fa-caret-square-o-down fa-3x fa-fw'),array('id'=>'even-down-btn','num'=>'down','class'=>'btn-block counter-btn-red-gray double'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function combinePage($type=null,$time=null,$ord=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
								if($type=='dinein'){
									$CI->make->H(3,strtoupper(DINEINTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}elseif($type=='counter'){
									$CI->make->H(3,strtoupper(COUNTERTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}elseif($type=='takeout'){
									$CI->make->H(3,strtoupper(TAKEOUTTEXT),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}else{
									$CI->make->H(3,strtoupper($type),array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}
								$CI->make->H(5,$time,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$waiter_name = trim($ord['waiter_username']);
						        if($waiter_name != "")
						            $CI->make->H(5,'Food Server: '.$ord['waiter_username'],array('class'=>'receipt text-center','style'=>'margin-top:5px;'));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body body-taller'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
								$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span>',array('class'=>'receipt text-center'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div','style'=>"margin-left:10px;"));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12);
								$CI->make->button(fa('fa-times fa-lg fa-fw').' Cancel',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(8,'left',0,array('style'=>'margin-top:10px;'));
					$CI->make->sDiv(array('class'=>'counter-combine-right'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(2,'left',0,array('style'=>'margin-top:70px;'));
								$CI->make->sDiv(array('class'=>'type-container','style'=>'margin-right:10px;'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
							$CI->make->sDivCol(10,'left');
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('class'=>'orders-list-combine-div'));
												$CI->make->sDivRow();
													$CI->make->sDivCol(5);
														$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> MY ORDERS',array('ref'=>'my','class'=>'my-all-btns btn-block counter-btn-red-gray double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(5);
														$CI->make->button(fa('fa-users fa-lg fa-fw').'<br> ALL ORDERS',array('id'=>'all','class'=>'my-all-btns btn-block counter-btn-red-gray double'));
													$CI->make->eDivCol();
													$CI->make->sDivCol(2);
														$CI->make->button(fa('fa-refresh fa-lg fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block counter-btn-orange double'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
												$CI->make->sDivRow();
													$CI->make->sDivCol(12,'left',0,array('class'=>'orders-list-combine','terminal'=>'my','types'=>'all'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('class'=>'orders-to-combine-div'));
												$CI->make->sDivRow();
													if(LOCAVORE){
														$CI->make->sDivCol(8);
															$CI->make->button(fa('fa-compress fa-lg fa-fw').'<br> GO COMBINE',array('id'=>'combine-btn','class'=>'btn-block counter-btn-green double','locavore'=>'yes'));
														$CI->make->eDivCol();
													}else{
														$CI->make->sDivCol(8);
															$CI->make->button(fa('fa-compress fa-lg fa-fw').'<br> GO COMBINE',array('id'=>'combine-btn','class'=>'btn-block counter-btn-green double','locavore'=>'no'));
														$CI->make->eDivCol();
													}
													$CI->make->sDivCol(4);
														$CI->make->button(fa('fa-times fa-lg fa-fw').'<br> CLEAR',array('id'=>'clear-btn','class'=>'btn-block counter-btn-red double'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
												$CI->make->sDivRow();
													$CI->make->sDivCol(12,'left',0,array('class'=>'orders-to-combine'));
													$CI->make->eDivCol();
												$CI->make->eDivRow();
											$CI->make->eDiv();
										$CI->make->eDivCol();
									$CI->make->eDivRow();
							$CI->make->eDivCol();
						$CI->make->eDivRow();

					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				// $CI->make->sDivCol(2,'left',0,array('style'=>'margin-top:10px;'));
				// 	$CI->make->sDiv(array('class'=>'type-container','style'=>'margin-right:10px;'));
				// 	$CI->make->eDiv();
				// $CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}
function tablesPage($type){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->hidden('dine_type',$type);
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));

				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'SELECT A TABLE',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
				$CI->make->sDiv(array('id'=>'image-con'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'no-guest-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'No. Of Guest',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->append(onScrNumDotPad('guest-input','guest-enter-btn'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'occupied-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'<span id="occ-num"></span> Is In Use',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-occ-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('class'=>'bg-orange'));
								$CI->make->H(3,'Table is currently in use. Choose from the following options to continue.',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-search fa-lg fa-fw').' RECALL',array('id'=>'exit-btn','class'=>'btn-block tables-btn-red double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->button(fa('fa-file fa-lg fa-fw').' Start New',array('id'=>'start-new-btn','class'=>'btn-block tables-btn-green double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-check-square-o fa-lg fa-fw').' Settle',array('id'=>'exit-btn','class'=>'btn-block tables-btn-orange double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDiv(array('class'=>'occ-orders-div'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function deliveryPage($det=null,$type='delivery'){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'Enter Customer Information',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();

				$CI->make->sDiv(array('style'=>'margin:10px;'));
				$CI->make->sDivRow();
					$CI->make->sDivCol(4);
						$CI->make->sBox('default',array('class'=>'box-solid'));
							$CI->make->sBoxBody();
								$CI->make->sDiv(array('style'=>'height:250px;'));
									$CI->make->input(null,'search-customer',null,'Search number or Customer Name',array(),fa('fa-search'));
									$CI->make->sDiv(array('class'=>'listings'));
										$CI->make->sUl(array('id'=>'cust-search-list'));
										$CI->make->eUl();
									$CI->make->eDiv();
								$CI->make->eDiv();
							$CI->make->eBoxBody();
						$CI->make->eBox();
					$CI->make->eDivCol();
					$CI->make->sDivCol(8);
						$CI->make->sBox('default',array('class'=>'box-solid'));
							$CI->make->sBoxBody();
								$CI->make->sDiv(array('class'=>'cust-form'));
									$CI->make->sForm('customers/customer_details_db/true',array('id'=>'customer-form'));
									$CI->make->hidden('trans_type',$type);
									$CI->make->sDivRow();
										$CI->make->sDivCol(3);
											$CI->make->hidden('cust_id',iSetObj($det,'cust_id'));
											$CI->make->input('First Name *','fname',iSetObj($det,'fname'),'Type First Name',array('class'=>'1 rOkay key-ins','ref'=>'1'));
											$CI->make->input('Phone *','phone',iSetObj($det,'phone'),'Type Phone No.',array('class'=>'5 rOkay key-ins','ref'=>'5'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Middle Name','mname',iSetObj($det,'mname'),'Type Middle Name',array('class'=>'2 key-ins','ref'=>'2'));
											$CI->make->input('Email Address','email',iSetObj($det,'email'),'Type Email Address',array('class'=>'6 key-ins','ref'=>'6'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Last Name *','lname',iSetObj($det,'lname'),'Type Last Name',array('class'=>'3 rOkay key-ins','ref'=>'3'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Suffix','suffix',iSetObj($det,'suffix'),'Type Suffix',array('class'=>'4 key-ins','ref'=>'4'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
									$CI->make->sDivRow();
										$CI->make->sDivCol(3);
											$CI->make->input('Street No.','street_no',iSetObj($det,'street_no'),'Type Street No.',array('class'=>'7 key-ins','ref'=>'7'));
											$CI->make->input('Zip Code','zip',iSetObj($det,'zip'),'Type Zip Code',array('class'=>'11  key-ins','ref'=>'11'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Street Address','street_address',iSetObj($det,'street_address'),'Type Street Address',array('class'=>'8  key-ins','ref'=>'8'));
											$CI->make->sDiv(array('style'=>'margin:10px;'));
												$CI->make->button('Continue',array('id'=>'continue-btn','class'=>'btn-block tables-btn-green','style'=>'margin-top:18px;'),'primary');
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('City','city',iSetObj($det,'city'),'Type City',array('class'=>'9 key-ins ','ref'=>'9'));
											$CI->make->sDiv(array('style'=>'margin:10px;'));
												$CI->make->button('Clear',array('id'=>'clear-btn','class'=>'btn-block tables-btn-red','style'=>'margin-top:18px;'),'primary');
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(3);
											$CI->make->input('Region','region',iSetObj($det,'region'),'Type Region',array('class'=>'10  key-ins','ref'=>'10'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eBoxBody();
						$CI->make->eBox();
					$CI->make->eDivCol();
				$CI->make->eDivRow();
				$CI->make->eDiv();

			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}
function tableTransfer($tables=array(),$tbl_id=null){
	$CI =& get_instance();
	$CI->make->sDiv(array('id'=>'cashier-panel','style'=>'margin-top:10px;','class'=>'div-no-spaces'));
		$CI->make->hidden('to-table',null);
		$CI->make->sDivRow();

		ksort($tables);
			foreach ($tables as $id => $opt) {

				$occ_tbls = $CI->cashier_model->get_occupied_tables_other($id,'dinein');
		        // echo "<pre>",print_r($tbl),"</pre>";die();
		        // $billed = array();
		        // foreach ($occ_tbls as $det) {
		        //   $occ[] = $det->table_id;
		        // }


				if($id == $tbl_id){
					$CI->make->sDivCol(3,'left',0,array("style"=>'margin:0px;'));
						$CI->make->button($opt['name'],array('ref'=>$id,'class'=>'btn-block cpanel-btn-orange reason-btns double','stat'=>'from'));
					$CI->make->eDivCol();
				}else{
					if($occ_tbls){
						$CI->make->sDivCol(3,'left',0,array("style"=>'margin:0px;'));
							$CI->make->button($opt['name'],array('ref'=>$id,'class'=>'btn-block cpanel-btn-red reason-btns double','stat'=>'occ'));
						$CI->make->eDivCol();
					}else{
						$CI->make->sDivCol(3,'left',0,array("style"=>'margin:0px;'));
							$CI->make->button($opt['name'],array('ref'=>$id,'class'=>'btn-block cpanel-btn-green reason-btns double','stat'=>'vac'));
						$CI->make->eDivCol();
					}
				}

			}
		$CI->make->eDivRow();
	$CI->make->eDiv();
	return $CI->make->code();
}
function counterPage2($type=null,$time=null,$loaded=null,$order=array(),$typeCN=array(),$local_tax=0,$kitchen_printer=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				#LEFT
				$CI->make->sDivCol(1,'left',0,array('class'=>'counter-left'));
					$CI->make->button(fa('fa-barcode fa-lg fa-fw').'<br>RETAIL',array('id'=>'retail-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-tags fa-lg fa-fw').'<br> Quantity',array('id'=>'qty-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Discount',array('id'=>'add-discount-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Zero-Rated',array('id'=>'zero-rated-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-tag fa-lg fa-fw').'<br> Add Charges',array('id'=>'charges-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-text-width fa-lg fa-fw').'<br> Add Remarks',array('id'=>'remarks-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-times fa-lg fa-fw').'<br>REMOVE',array('id'=>'remove-btn','class'=>'btn-block counter-btn-red double '.$loaded));
					// $CI->make->button(fa('fa-keyboard-o fa-lg fa-fw').'<br>MISC',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-file fa-lg fa-fw').'<br>RECALL',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-circle-o fa-lg fa-fw').'<br> ORDER TAX EXEMPT',array('id'=>'tax-exempt-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-magnet fa-lg fa-fw').'<br>HOLD ALL',array('id'=>'hold-all-btn','class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-power-off fa-lg fa-fw').'<br>LOGOUT',array('id'=>'logout-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-reply fa-lg fa-fw').'<br>Back',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
				$CI->make->eDivCol();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
						    	$tableN = null;
						    	$guestN = null;

							    if(isset($typeCN[0]['table_name']))
							    	$tableN = " - ".$typeCN[0]['table_name'];
							    if(isset($order['table_name']) && $order['table_name'] != "")
							    	$tableN = " - ".$order['table_name'];

							    if(isset($typeCN[0]['guest']))
							    	$guestN = $typeCN[0]['guest'];
							    if(isset($order['guest']) && $order['guest'] != "")
							    	$guestN = $order['guest'];

								$waiter = '';

								if(isset($order['waiter_name'])){
									$waiter_name = trim($order['waiter_name']);
									$display = '';
									if($waiter_name != '')
										$waiter = 'FS: '.$order['waiter_name'];
								}
								if($type=='dinein'){
									$CI->make->H(3,strtoupper(DINEINTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}elseif($type=='counter'){
									$CI->make->H(3,strtoupper(COUNTERTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($type=='takeout'){
									$CI->make->H(3,strtoupper(TAKEOUTTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}else{

									$CI->make->H(3,strtoupper($type).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
								}
								$guestNtxt="";
								if($guestN != null){
									if($guestN  == 0){
										$guestN = 1;
									}
									$guestNtxt = 'Guest #<span id="ord-guest-no">'.$guestN.'</span> <a href="#" id="edit-order-guest-no">'.fa('fa-edit fa-lg').'</a>';
								}
								
								$CI->make->H(5,$time." ".$guestNtxt,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$display='display:none;';
								// $CI->make->H(5,$waiter,array('id'=>'trans-server-txt','class'=>'addon-texts receipt text-center','style'=>'margin-top:5px;'.$display));
								$rand = mt_rand(11,21).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT);
								$CI->make->sDivRow();
									$CI->make->sDivCol(6);
										$CI->make->input('','must_ref','0000','Trans Ref',array('class'=>'input-xs rOkay'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->input('','must_trans_date','01/11/2016 '.$rand,null,array('class'=>'input-xs rOkay'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->append('<hr>');

							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->sDiv(array('class'=>'foot-det'));
									$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
									// $CI->make->H(5,'LOCAL TAX: <span id="local-tax-txt">0.00</span>',array('class'=>'receipt text-center'));
									$lt_txt = "";
									if($local_tax > 0){
										$lt_txt = 'LOCAL TAX: <span id="local-tax-txt">0.00</span>';
									}
									$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span> '.$lt_txt,array('class'=>'receipt text-center'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div'));
						$CI->make->sDivRow();
							
							if($kitchen_printer != null){
								$CI->make->sDivCol(3);
									$CI->make->button(fa('fa-ban fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'false'));
								$CI->make->eDivCol();
								if(ORDERING_STATION){
									$CI->make->sDivCol(6);
										$CI->make->button(fa('fa-check fa-lg fa-fw').' Send',array('id'=>'send-trans-btn','class'=>'btn-block counter-btn-green double check_fs'));
									$CI->make->eDivCol();
								}
								else{
									$CI->make->sDivCol(6);
										$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double'));
									$CI->make->eDivCol();
								}
								if(ORDERING_STATION){
									$CI->make->sDivCol(3);
										$CI->make->button(fa('fa-ban fa-lg fa-fw').'<br>ORDER SLIP',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'false'));
									$CI->make->eDivCol();
								}
								else{
									$CI->make->sDivCol(3);
										$CI->make->button(fa('fa-print fa-lg fa-fw').'<br>ORDER SLIP',array('id'=>'print-os-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true'));
									$CI->make->eDivCol();
								}

							}
							else{
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-print fa-lg fa-fw').' <br>Billing',array('id'=>'print-btn','class'=>'btn-block counter-btn-orange double','doprint'=>'true'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(8);
									$CI->make->button(fa('fa-check fa-lg fa-fw').' SUBMIT',array('id'=>'submit-btn','class'=>'btn-block counter-btn-green double'));
								$CI->make->eDivCol();
							}


						$CI->make->eDivRow();
						$CI->make->sDivRow();
							if(ORDERING_STATION){
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double disabled'));
								$CI->make->eDivCol();
							}
							else{
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-money fa-lg fa-fw').' CASH',array('id'=>'cash-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-credit-card fa-lg fa-fw').' CARD',array('id'=>'credit-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
								$CI->make->sDivCol(4);
									$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
							}
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(2,'left');
					$CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-cat-scroll-up','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'menu-cat-container'));
					$CI->make->eDiv();
					$CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-cat-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(5,'left',0);
					// $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
							// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'counter-right','style'=>'height:630px;overflow:hidden;'));
						#MENU
						$CI->make->sDiv(array('class'=>'menus-div loads-div','style'=>'display:none'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;'));
								$CI->make->input(null,'search-menu',null,null,array(),fa('fa fa-search')." Menu");
							$CI->make->eDiv();
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'items-lists','style'=>'height:462px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#MODS
						$CI->make->sDiv(array('class'=>'mods-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'MODIFIERS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'mods-lists','style'=>'height:521px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#QTY
						$CI->make->sDiv(array('class'=>'qty-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Quantity',array('class'=>'receipt text-center title','style'=>'margin-bottom:20px'));
							$CI->make->sDiv(array('class'=>'qty-lists'));
								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->sDivCol(6);
										$CI->make->button('+1',array('value'=>'1','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('-1',array('value'=>'1','operator'=>'minus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+5',array('value'=>'5','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+10',array('value'=>'10','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x2',array('value'=>'2','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x10',array('value'=>'10','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDiv(array('class'=>'counter-center-btns'));
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->button('Reset',array('value'=>'1','operator'=>'equal','class'=>'btn-block edit-qty-btn counter-btn-red double'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->button('Finished',array('id'=>'qty-btn-done','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#DISCOUNT
						$CI->make->sDiv(array('class'=>'sel-discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'select-discounts-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->H(3,'RATE: % <span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'discounts-lists'));
								 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 $CI->make->sDivCol(12);
								    	$guestN = null;
									    if(isset($typeCN[0]['guest']))
									    	$guestN = $typeCN[0]['guest'];
								 	 	$CI->make->input(null,'disc-guests',$guestN,'Total No. Of Guests',array(),fa('fa-user'));
								 	 $CI->make->eDivCol();
								 $CI->make->eDivRow();
								 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('ALL ITEMS',array('ref'=>'all','class'=>'disc-btn-row btn-block counter-btn-teal'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('EQUALLY DIVIDED',array('ref'=>'equal','class'=>'disc-btn-row btn-block counter-btn-orange'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
								 $CI->make->sForm("",array('id'=>'disc-form'));
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 	$CI->make->hidden('disc-disc-id',null);
									 	 	$CI->make->hidden('disc-disc-rate',null);
									 	 	$CI->make->hidden('disc-disc-code',null);
									 	 	$CI->make->hidden('disc-no-tax',null);
									 	 	$CI->make->hidden('disc-fix',null);
									 	 $CI->make->eDivCol();
									 	 // $CI->make->sDivCol(6);
									 	 	// $CI->make->input(null,'disc-cust-guest',null,'No. Of Guest',array('class'=>'rOkay','ro-msg'=>'Add No. Of Guest for Discount'),fa('fa-male'));
									 	 // $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-bday',null,'MM/DD/YYYY',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(6);
										 	$CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
										 $CI->make->sDivCol(6);
									 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	$CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 	 // 'ref'=>'equal',
									 $CI->make->eDivRow();
									 $CI->make->sDivRow();
										 $CI->make->sDivCol(12);
										 	$CI->make->sDiv(array('class'=>'disc-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
											$CI->make->eDiv();
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 $CI->make->eForm();
								 // $CI->make->sDivRow(array('style'=>'margin-top:20px;'));
								 // 	 $CI->make->sDivCol(12);
								 // 	 	$CI->make->button(fa('fa fa-plus fa-lg fa-fw').' SELECTED ITEM ONLY',array('ref'=>'item','class'=>'disc-btn-row btn-block counter-btn-green'));
								 // 	 	$CI->make->sUl(array('class'=>'item-disc-list','style'=>'margin-top:10px;'));
								 // 	 	$CI->make->eUl();
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#CHARGES
						$CI->make->sDiv(array('class'=>'charges-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Charges',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'charges-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#WAITER
						$CI->make->sDiv(array('class'=>'waiter-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Food Server',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							 $CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
							 	 	$CI->make->button(fa('fa-times fa-lg fa-fw').' REMOVE FOOD SERVER',array('id'=>'remove-waiter-btn','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
							 $CI->make->eDivRow();
							$CI->make->sDiv(array('class'=>'waiters-lists','style'=>"overflow:auto;height:460px;"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#REMARKS
						$CI->make->sDiv(array('class'=>'remarks-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'REMARKS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
								 	 $CI->make->sForm("",array('id'=>'remarks-form'));
								 	 	$CI->make->textarea(null,'line-remarks',null,null,array('class'=>'rOkay','ro-msg'=>'Add Remarks'));
								 	 	$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'add-remark-btn','class'=>'btn-block counter-btn-green'));
								 	 $CI->make->eForm();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						#RETAIL
						$CI->make->sDiv(array('class'=>'retail-div loads-div','style'=>'display:none'));
							$CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							 	 $CI->make->sDivCol();
							 	 	$CI->make->sDiv(array('id'=>'scan-div'));
								 	 	$btn = $CI->make->button("SCAN CODE",array('id'=>'go-scan-code','return'=>true,'class'=>'btn-block counter-btn-orange'));
								 	 	$CI->make->pwdWithBtn(null,'scan-code',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),$btn);
								 	$CI->make->eDiv();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH ITEM",array('id'=>'go-search-item','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-item',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->append('<hr>');
					 	 	$CI->make->H(4,null,array('class'=>'retail-title text-center title','style'=>'margin-bottom:10px;display:none;'));
					 	 	$CI->make->sDiv(array('class'=>'retail-loads-div listings','style'=>'height:400px;overflow:auto;'));
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
function loyaltyAddPage(){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(10);
				$CI->make->input(null,'loyalty-code','','',array(
					'style'=>
						'width:100%;
						height:100%;
						font-size:34px;
						font-weight:bold;
						text-align:left;
						border:none;
						border-radius:5px !important;
						box-shadow:none;
						',
					)
				);
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function retrackPage(){
	$CI =& get_instance();
		$CI->make->sBox('solid');
			$CI->make->sBoxBody();
				$CI->make->sForm("",array('id'=>'search-form'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(2);
							$CI->make->date('Read Date','read_date',null,null,array());
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->button(fa('fa-search').' Search',array('id'=>'search-btn','style'=>'margin-top:23px;'),'primary');
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eForm();
			$CI->make->eBoxBody();
		$CI->make->eBox();
		$CI->make->sDivRow();
			$CI->make->sDivCol(6);
				$CI->make->sBox('solid');
					$CI->make->sBoxBody();
						$CI->make->H(4,'SHIFTS');
						$CI->make->append('<hr style="margin-top:0px;margin-bottom:10px;">');
						$CI->make->sTable(array('id'=>'shifts-tbl','class'=>'table'));
							$CI->make->sTableHead();
								$CI->make->sRow();
									$CI->make->th('USERNAME');
									$CI->make->th('DATE');
									$CI->make->th('IN');
									$CI->make->th('OUT');
									$CI->make->th('CASHCOUNT');
									$CI->make->th('XREAD');
									$CI->make->th('');
								$CI->make->eRow();
							$CI->make->eTableHead();
							$CI->make->sTableBody();
							$CI->make->eTableBody();
						$CI->make->eTable();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDivCol();
			$CI->make->sDivCol(6);
				$CI->make->sBox('solid');
					$CI->make->sBoxBody();
						$CI->make->H(4,'ORDERS');
						$CI->make->append('<hr style="margin-top:0px;margin-bottom:10px;">');
						$CI->make->sTable(array('id'=>'orders-tbl','class'=>'table'));
							$CI->make->sTableHead();
								$CI->make->sRow();
									$CI->make->th('ORDER NO.');
									$CI->make->th('RECEIPT');
									$CI->make->th('DATE');
									$CI->make->th('TIME');
									$CI->make->th('TOTAL');
									$CI->make->th('CASHIER');
									$CI->make->th('STATUS');
								$CI->make->eRow();
							$CI->make->eTableHead();
							$CI->make->sTableBody();
							$CI->make->eTableBody();
						$CI->make->eTable();
					$CI->make->eBoxBody();
				$CI->make->eBox();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function counterRetrackPage($type=null,$time=null,$loaded=null,$order=array(),$typeCN=array(),$local_tax=0,$kitchen_printer=null){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'counter'));
			$CI->make->sDivRow();
				#LEFT
				$CI->make->sDivCol(1,'left',0,array('class'=>'counter-left'));
					$CI->make->button(fa('fa-barcode fa-lg fa-fw').'<br>RETAIL',array('id'=>'retail-btn','class'=>'btn-block counter-btn-red double'));
					if($type == 'retail'){
						$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double'));
					}
					else{
						if(isset($typeCN[0]['type']) && $typeCN[0]['type'] == 'retail'){
							$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Customer',array('id'=>'customer-btn','class'=>'btn-block counter-btn-red double'));
						}
						else	
							$CI->make->button(fa('fa-user fa-lg fa-fw').'<br> Food Server',array('id'=>'waiter-btn','class'=>'btn-block counter-btn-red double'));
					}
						
					$CI->make->button(fa('fa-tags fa-lg fa-fw').'<br> Quantity',array('id'=>'qty-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-certificate fa-lg fa-fw').'<br> Add Discount',array('id'=>'add-discount-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-dot-circle-o fa-lg fa-fw').'<br>Zero-Rated',array('id'=>'zero-rated-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-tag fa-lg fa-fw').'<br> Add Charges',array('id'=>'charges-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-asterisk fa-lg fa-fw').'<br> Free',array('id'=>'free-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-text-width fa-lg fa-fw').'<br> Add Remarks',array('id'=>'remarks-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-times fa-lg fa-fw').'<br>REMOVE',array('id'=>'remove-btn','class'=>'btn-block counter-btn-red double '.$loaded));
					// $CI->make->button(fa('fa-keyboard-o fa-lg fa-fw').'<br>MISC',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-file fa-lg fa-fw').'<br>RECALL',array('class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-circle-o fa-lg fa-fw').'<br> ORDER TAX EXEMPT',array('id'=>'tax-exempt-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-magnet fa-lg fa-fw').'<br>HOLD ALL',array('id'=>'hold-all-btn','class'=>'btn-block counter-btn-red double'));
					// $CI->make->button(fa('fa-power-off fa-lg fa-fw').'<br>LOGOUT',array('id'=>'logout-btn','class'=>'btn-block counter-btn-red double'));
					$CI->make->button(fa('fa-reply fa-lg fa-fw').'<br>Back',array('id'=>'cancel-btn','class'=>'btn-block counter-btn-red double'));
				$CI->make->eDivCol();
				#CENTER
				$CI->make->sDivCol(4);
					$CI->make->sDiv(array('class'=>'center-div counter-center list-div'));
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'title'));
						    	$tableN = null;
						    	$guestN = null;

							    if(isset($typeCN[0]['table_name']))
							    	$tableN = " - ".$typeCN[0]['table_name'];
							    if(isset($order['table_name']) && $order['table_name'] != "")
							    	$tableN = " - ".$order['table_name'];

							    if(isset($typeCN[0]['guest']))
							    	$guestN = $typeCN[0]['guest'];
							    if(isset($order['guest']) && $order['guest'] != "")
							    	$guestN = $order['guest'];

								$waiter = '';

								if(isset($order['waiter_name'])){
									$waiter_name = trim($order['waiter_name']);
									$display = '';
									if($waiter_name != '')
										$waiter = 'FS: '.$order['waiter_name'];
								}

								if($type=='dinein'){
									$CI->make->H(3,strtoupper(DINEINTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($type=='counter'){
									$CI->make->H(3,strtoupper(COUNTERTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}elseif($type=='takeout'){
									$CI->make->H(3,strtoupper(TAKEOUTTEXT).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));

								}else{
									$CI->make->H(3,strtoupper($type).$tableN." <span id='trans-server-txt'>".$waiter."</span>",array('id'=>'trans-header','class'=>'receipt text-center text-uppercase'));
									
								}
								$guestNtxt="";
								if($guestN != null){
									if($guestN  == 0){
										$guestN = 1;
									}
									$guestNtxt = 'Guest #<span id="ord-guest-no">'.$guestN.'</span> <a href="#" id="edit-order-guest-no">'.fa('fa-edit fa-lg').'</a>';
								}
								if(isset($typeCN[0]['customer_id'])){
									$CI->make->H(5,"CUSTOMER ID: ".$typeCN[0]['customer_id'],array('id'=>'trans-customer','class'=>'receipt text-center text-uppercase'));
								}
								else{
									$CI->make->H(5,"",array('id'=>'trans-customer','class'=>'receipt text-center text-uppercase','style'=>'display:none;'));
								}
								$time_txt = '<span id="trans-datetime-txt">'.$time.'</span><a href="#" id="edit-datetime">'.fa('fa-edit fa-lg').'</a>';	
								$CI->make->H(5,$time_txt." ".$guestNtxt,array('id'=>'trans-datetime','class'=>'receipt text-center'));
								$display='display:none;';
								$CI->make->H(3,'ADD PROMO <span id="promo-qty"></span> QTY',array('id'=>'promo-txt','class'=>'receipt text-center','style'=>'color:red;display:none;'));
								// $CI->make->H(5,$waiter,array('id'=>'trans-server-txt','class'=>'addon-texts receipt text-center','style'=>'margin-top:5px;'.$display));
								$CI->make->append('<hr>');
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							#LISTS
							$CI->make->sDivCol(12,'left',0,array('class'=>'body'));
								$CI->make->sUl(array('class'=>'trans-lists'));
								$CI->make->eUl();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
						$CI->make->sDivRow();
							$CI->make->sDivCol(12,'left',0,array('class'=>'foot'));
								$CI->make->append('<hr>');
								$CI->make->sDiv(array('class'=>'foot-det'));
									$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center'));
									// $CI->make->H(5,'LOCAL TAX: <span id="local-tax-txt">0.00</span>',array('class'=>'receipt text-center'));
									$lt_txt = "";
									if($local_tax > 0){
										$lt_txt = 'LOCAL TAX: <span id="local-tax-txt">0.00</span>';
									}
									$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span> '.$lt_txt,array('class'=>'receipt text-center'));
								$CI->make->eDiv();
							$CI->make->eDivCol();
						$CI->make->eDivRow();
					$CI->make->eDiv();
					$CI->make->sDiv(array('class'=>'counter-center-btns center-div list-div'));
						$CI->make->sDivRow();
							
								$CI->make->sDivCol();
									$CI->make->button(fa('fa-arrow-right fa-lg fa-fw').' SETTLE',array('id'=>'settle-btn','class'=>'btn-block counter-btn-teal double'));
								$CI->make->eDivCol();
						
						$CI->make->eDivRow();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				#CATEGORIES
				$CI->make->sDivCol(2,'left');
					$CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-cat-scroll-up','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'menu-cat-container'));
					$CI->make->eDiv();
					$CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-cat-scroll-down','class'=>'btn-block counter-btn double'));
				$CI->make->eDivCol();
				#ITEMS
				$CI->make->sDivCol(5,'left',0);
					// $CI->make->button(fa('fa-chevron-circle-up fa-2x fa-fw'),array('id'=>'menu-item-scroll-up','class'=>'btn-block counter-btn double'));
							// $CI->make->button(fa('fa-chevron-circle-down fa-2x fa-fw'),array('id'=>'menu-item-scroll-down','class'=>'btn-block counter-btn double'));
					$CI->make->sDiv(array('class'=>'counter-right','style'=>'height:693px;overflow:hidden;'));
						#MENU
						$CI->make->sDiv(array('class'=>'menus-div loads-div','style'=>'display:none'));
							$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;'));
								$CI->make->input(null,'search-menu',null,null,array(),fa('fa fa-search')." Menu");
							$CI->make->eDiv();
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'items-lists','style'=>'height:520px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#MODS
						$CI->make->sDiv(array('class'=>'mods-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'MODIFIERS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'mods-lists','style'=>'height:525px;overflow:hidden;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#QTY
						$CI->make->sDiv(array('class'=>'qty-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Quantity',array('class'=>'receipt text-center title','style'=>'margin-bottom:20px'));
							$CI->make->sDiv(array('class'=>'qty-lists'));
								$CI->make->sDivRow(array('style'=>'margin-bottom:20px;'));
									$CI->make->sDivCol(6);
										$CI->make->button('+1',array('value'=>'1','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('-1',array('value'=>'1','operator'=>'minus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+5',array('value'=>'5','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('+10',array('value'=>'10','operator'=>'plus','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x2',array('value'=>'2','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
									$CI->make->sDivCol(6);
										$CI->make->button('x10',array('value'=>'10','operator'=>'times','class'=>'btn-block edit-qty-btn counter-btn-silver double'));
									$CI->make->eDivCol();
								$CI->make->eDivRow();
								$CI->make->sDiv(array('class'=>'counter-center-btns'));
									$CI->make->sDivRow();
										$CI->make->sDivCol(6);
											$CI->make->button('Reset',array('value'=>'1','operator'=>'equal','class'=>'btn-block edit-qty-btn counter-btn-red double'));
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->button('Finished',array('id'=>'qty-btn-done','class'=>'btn-block counter-btn-green double'));
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#DISCOUNT
						$CI->make->sDiv(array('class'=>'sel-discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT DISCOUNT',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'select-discounts-lists','style'=>'height:550px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						$CI->make->sDiv(array('class'=>'discount-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'&nbsp;',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->H(3,'RATE: % <span id="rate-txt"></span>',array('class'=>'receipt text-center','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'discounts-lists'));
								 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 $CI->make->sDivCol(12);
								    	$guestN = null;
									    if(isset($typeCN[0]['guest']))
									    	$guestN = $typeCN[0]['guest'];
								 	 	$CI->make->input(null,'disc-guests',$guestN,'Total No. Of Guests',array(),fa('fa-user'));
								 	 $CI->make->eDivCol();
								 $CI->make->eDivRow();
								 // $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('ALL ITEMS',array('ref'=>'all','class'=>'disc-btn-row btn-block counter-btn-teal'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button('EQUALLY DIVIDED',array('ref'=>'equal','class'=>'disc-btn-row btn-block counter-btn-orange'));
								 // 	 $CI->make->eDivCol();
								 // 	 $CI->make->sDivCol(4);
								 // 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
								 $CI->make->sForm("",array('id'=>'disc-form'));
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
									 	 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-name',null,'Customer Name',array('class'=>'rOkay','ro-msg'=>'Add Customer Name for Discount'),fa('fa-user'));
									 	 	$CI->make->hidden('disc-disc-id',null);
									 	 	$CI->make->hidden('disc-disc-rate',null);
									 	 	$CI->make->hidden('disc-disc-code',null);
									 	 	$CI->make->hidden('disc-no-tax',null);
									 	 	$CI->make->hidden('disc-fix',null);
									 	 $CI->make->eDivCol();
									 	 // $CI->make->sDivCol(6);
									 	 	// $CI->make->input(null,'disc-cust-guest',null,'No. Of Guest',array('class'=>'rOkay','ro-msg'=>'Add No. Of Guest for Discount'),fa('fa-male'));
									 	 // $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-code',null,'Card Number',array('class'=>'','ro-msg'=>'Add Customer Code for Discount'),fa('fa-credit-card'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
									 	 	$CI->make->input(null,'disc-cust-bday',null,'MM/DD/YYYY',array('ro-msg'=>'Add Customer Birthdate for Discount'),fa('fa-calendar'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(6);
										 	$CI->make->button(fa('fa-plus fa-lg fa-fw').' ADD ',array('id'=>'add-disc-person-btn','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
										 $CI->make->sDivCol(6);
									 	 	$CI->make->button(fa('fa fa-times fa-lg fa-fw').'REMOVE',array('id'=>'remove-disc-btn','class'=>'btn-block counter-btn-red'));
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
									 $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
										 $CI->make->sDivCol(12);
										 	$CI->make->button(' PROCESS DISCOUNT ',array('id'=>'prcss-disc','class'=>'btn-block counter-btn-green'));
									 	 $CI->make->eDivCol();
									 	 // 'ref'=>'equal',
									 $CI->make->eDivRow();
									 $CI->make->sDivRow();
										 $CI->make->sDivCol(12);
										 	$CI->make->sDiv(array('class'=>'disc-persons-list-div listings','style'=>'height:280px;overflow:auto;'));
											$CI->make->eDiv();
									 	 $CI->make->eDivCol();
									 $CI->make->eDivRow();
								 $CI->make->eForm();
								 // $CI->make->sDivRow(array('style'=>'margin-top:20px;'));
								 // 	 $CI->make->sDivCol(12);
								 // 	 	$CI->make->button(fa('fa fa-plus fa-lg fa-fw').' SELECTED ITEM ONLY',array('ref'=>'item','class'=>'disc-btn-row btn-block counter-btn-green'));
								 // 	 	$CI->make->sUl(array('class'=>'item-disc-list','style'=>'margin-top:10px;'));
								 // 	 	$CI->make->eUl();
								 // 	 $CI->make->eDivCol();
								 // $CI->make->eDivRow();
							$CI->make->eDiv();
						$CI->make->eDiv();
						#CHARGES
						$CI->make->sDiv(array('class'=>'charges-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Charges',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDiv(array('class'=>'charges-lists'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#WAITER
						$CI->make->sDiv(array('class'=>'waiter-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'Select Food Server',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							 $CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
							 	 	$CI->make->button(fa('fa-times fa-lg fa-fw').' REMOVE FOOD SERVER',array('id'=>'remove-waiter-btn','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
							 $CI->make->eDivRow();
							$CI->make->sDiv(array('class'=>'waiters-lists','style'=>"overflow:auto;height:460px;"));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#REMARKS
						$CI->make->sDiv(array('class'=>'remarks-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'REMARKS',array('class'=>'receipt text-center title','style'=>'margin-bottom:10px'));
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol(12);
								 	 $CI->make->sForm("",array('id'=>'remarks-form'));
								 	 	$CI->make->textarea(null,'line-remarks',null,null,array('class'=>'rOkay','ro-msg'=>'Add Remarks'));
								 	 	$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'add-remark-btn','class'=>'btn-block counter-btn-green'));
								 	 $CI->make->eForm();
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
						$CI->make->eDiv();
						#RETAIL
						$CI->make->sDiv(array('class'=>'retail-div loads-div','style'=>'display:none'));
							// $CI->make->sDivRow(array('style'=>'margin-bottom:10px;'));
							//  	 $CI->make->sDivCol();
							//  	 	$CI->make->sDiv(array('id'=>'scan-div'));
							// 	 	 	$btn = $CI->make->button("SCAN CODE",array('id'=>'go-scan-code','return'=>true,'class'=>'btn-block counter-btn-orange'));
							// 	 	 	$CI->make->pwdWithBtn(null,'scan-code',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),$btn);
							// 	 	$CI->make->eDiv();
							//  	 $CI->make->eDivCol();
							// $CI->make->eDivRow();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH ITEM",array('id'=>'go-search-item','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-item',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->append('<hr>');
					 	 	$CI->make->H(4,null,array('class'=>'retail-title text-center title','style'=>'margin-bottom:10px;display:none;'));
					 	 	$CI->make->sDiv(array('class'=>'retail-loads-div listings','style'=>'height:400px;overflow:auto;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
						#CUSTOMER
						$CI->make->sDiv(array('class'=>'customers-div loads-div','style'=>'display:none'));
							$CI->make->H(3,'SELECT CUSTOMER',array('class'=>'customers-title text-center title','style'=>'margin-bottom:10px;'));
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	 	$btn = $CI->make->button(fa('fa-search fa-lg fa-fw')." SEARCH",array('id'=>'go-search-customer','return'=>true,'class'=>'btn-block counter-btn-teal'));
							 	 	$CI->make->inputWithBtn(null,'search-customer',null,null,array('class'=>'','style'=>'height:50px;font-size:20px;'),null,$btn);
							 	 $CI->make->eDivCol();
							$CI->make->eDivRow();
					 	 	$CI->make->sDiv(array('class'=>'customers-loads-div listings','style'=>'height:400px;overflow:auto;margin-top:10px;'));
							$CI->make->eDiv();
							$CI->make->sDivRow();
							 	 $CI->make->sDivCol();
							 	$CI->make->button(fa('fa-times fa-lg fa-fw')." REMOVE CUSTOMER",array('id'=>'remove-customer','class'=>'btn-block counter-btn-red'));
							 	 $CI->make->eDivCol();
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
function tablesPageOther($type){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->hidden('dine_type',$type);
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));

				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'SELECT A NUMBER',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
				$CI->make->sDiv(array('id'=>'image-con'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'no-guest-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'No. Of Guest',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->append(onScrNumDotPad('guest-input','guest-enter-btn'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'occupied-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'<span id="occ-num"></span> Is In Use',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-occ-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('class'=>'bg-orange'));
								$CI->make->H(3,'Table is currently in use. Choose from the following options to continue.',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-search fa-lg fa-fw').' RECALL',array('id'=>'exit-btn','class'=>'btn-block tables-btn-red double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->button(fa('fa-file fa-lg fa-fw').' Start New',array('id'=>'start-new-btn','class'=>'btn-block tables-btn-green double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-check-square-o fa-lg fa-fw').' Settle',array('id'=>'exit-btn','class'=>'btn-block tables-btn-orange double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDiv(array('class'=>'occ-orders-div'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function tablesOtherPage($type){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->hidden('dine_type',$type);
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));

				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'SELECT A TABLE '.strtoupper($type),array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
				$CI->make->sDiv(array('id'=>'image-con'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'no-guest-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'No. Of Guest',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->append(onScrNumDotPad('guest-input','guest-enter-btn'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'occupied-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'<span id="occ-num"></span> Is In Use',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-occ-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('class'=>'bg-orange'));
								$CI->make->H(3,'Table is currently in use. Choose from the following options to continue.',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-search fa-lg fa-fw').' RECALL',array('id'=>'exit-btn','class'=>'btn-block tables-btn-red double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						// $CI->make->sDivCol(4);
						// 	$CI->make->sDiv(array('style'=>'margin:10px;'));
						// 		$CI->make->button(fa('fa-file fa-lg fa-fw').' Start New',array('id'=>'start-new-btn','class'=>'btn-block tables-btn-green double'));
						// 	$CI->make->eDiv();
						// $CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-check-square-o fa-lg fa-fw').' Settle',array('id'=>'exit-btn','class'=>'btn-block tables-btn-orange double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDiv(array('class'=>'occ-orders-div'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function makeViewInvCheck(){
	$CI =& get_instance();
	// $CI->make->sForm("dine/menu/subcategories_form_db",array('id'=>'subcategories_form'));
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDivCol('8');
				$CI->make->sDiv(array('class'=>'items-search','style'=>'background-color:#fff;height:50px;overflow:hidden;width:600px;'));
					$CI->make->input(null,'search-inv',null,null,array('id'=>'search-inv'),fa('fa fa-search')." Menu");
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol('4','right');
				$CI->make->H(4,'For the date '.date('m/d/Y'),array('class'=>'text-uppercase','style'=>'padding:12px;'));
			$CI->make->eDivCol();
    	$CI->make->eDivRow();
		$CI->make->sDivRow(array('style'=>'margin:10px;'));
			$CI->make->sDiv(array('id'=>'menu-div'));
			$CI->make->eDiv();
    	$CI->make->eDivRow();
	// $CI->make->eForm
	return $CI->make->code();
}
function set_calendar($get_tbl_id = array()){
	$CI =& get_instance();
		$CI->make->sDivRow();
			// $CI->make->sDivCol(4,'left',0,array('class'=>'no-padding no-margin','style'=>'padding-left:14px !important;'));
			// 	$CI->make->sDiv(array('style'=>'margin:5px'));
			// 	$CI->make->sBox('default',array('class'=>'box-solid'));
			// 		$CI->make->sBoxBody();
			// 			$CI->make->sDiv(array('id'=>'search-div','style'=>'min-height:200px;'));
			// 				$CI->make->input(null,'search-customer',null,'Search number or Customer Name',array(),fa('fa-search'));
			// 				$CI->make->sDiv(array('class'=>'listings'));
			// 					$CI->make->sUl(array('id'=>'cust-search-list','style'=>'height:250px;overflow:auto;'));
			// 					$CI->make->eUl();
			// 				$CI->make->eDiv();
			// 			$CI->make->eDiv();
			// 		$CI->make->eBoxBody();
			// 	$CI->make->eBox();
			// 	$CI->make->eDiv();
			// $CI->make->eDivCol();
			$CI->make->sDivCol(12,'left',0,array());
				$CI->make->sDiv(array('style'=>'margin:5px'));
				$CI->make->sBox('default',array('class'=>'box-solid'));
					$CI->make->sBoxBody();
						$CI->make->sDiv(array('id'=>'calendar_','style'=>''));
						$CI->make->eDiv();
					$CI->make->eBoxBody();
				$CI->make->eBox();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(6,'left',0,array());
				$CI->make->sDiv(array('style'=>'margin:5px','class'=>'modal fade','id'=>'pickTimeModal','tabindex'=>'-1','role'=>'dialog','aria-labelledby'=>'staticBackdropLabel','aria-hidden'=>'true'));
					$CI->make->sDiv(array('class'=>'modal-dialog','role'=>'document'));
						$CI->make->sDiv(array('class'=>'modal-content','style'=>'border: 2%;'));
							$CI->make->sDiv(array('class'=>'modal-header'));
								$CI->make->H(3,'PREFERRED TIME',array('class'=>'modal-title','id'=>'staticBackdropLabel'));
								$CI->make->span('X',array('class'=>'close modal_close sched-modal-close','data-dismiss'=>'modal','aria-hidden'=>'true'));
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'modal-body'));
								$CI->make->sDivCol(12,'left',0,array());
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->input('Date Selected','date-selected',null,null,array('class'=>'form-control health-center-input','id'=>"date-selected",'readonly'=>'readonly','data-date-format'=>"dd-mm-yyyy"));
									$CI->make->eDiv();
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									// $CI->make->input("Select Time",'time-select',null,null,array('class'=>'form-control health-center-input','id'=>"time-select"));
									$CI->make->time('Select Time','time-select',null,null,array('id'=>'time-select'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'modal-footer','style'=>'border-top:0!important'));
								$CI->make->button(' Cancel',array('data-dismiss'=>'modal'));
								$CI->make->button(' Save',array('id'=>'set-schedule-submit'));
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			$CI->make->sDivCol(6,'left',0,array());
				$CI->make->sDiv(array('style'=>'margin:5px','class'=>'modal fade','id'=>'pickTimeModal2','tabindex'=>'-1','role'=>'dialog','aria-labelledby'=>'staticBackdropLabel2','aria-hidden'=>'true'));
					$CI->make->sDiv(array('class'=>'modal-dialog','role'=>'document'));
						$CI->make->sDiv(array('class'=>'modal-content','style'=>'width: 70%;margin: 0 auto;'));
							$CI->make->sDiv(array('class'=>'modal-header'));
								$CI->make->span('X',array('class'=>'close modal_close sched-modal-close','data-dismiss'=>'modal','aria-hidden'=>'true'));
								$CI->make->H(3,' ',array('class'=>'modal-title','id'=>'staticBackdropLabel2'));
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'modal-body'));
							$CI->make->hidden('tsales_id','');
							$CI->make->hidden('ttype','');
							$CI->make->hidden('status','');
								$CI->make->sDivCol(12,'left',0,array());
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->input('Add Deposit Amount','add_deposit',null,null,array('class'=>'form-control','style'=>'width:55%'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(12,'left',0,array());
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->input('Reservation Date','date-selected2',null,null,array('class'=>'form-control health-center-input daterangepicker','id'=>"date-selected2",'data-date-format'=>"dd-mm-yyyy",'style'=>'margin-top: -75px;width:50%'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(12,'left',0,array('style'=>'margin-top:25px'));
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->time('Reservation Time','time-select2',null,null,array('id'=>'time-select2','style'=>'width:50%!important'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(12,'left',0,array('style'=>''));
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->select("Tbl/Room #",'tbl_id',$get_tbl_id,'',array('style'=>'width:55%'));
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(12,'left',0,array('style'=>''));
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->button(fa('fa-plus fa-lg fa-fw').' Add Advance Order',array('id'=>'advance_order_btns','ref'=>'','class'=>'btn'),'success');
									$CI->make->eDiv();
								$CI->make->eDivCol();
								$CI->make->sDivCol(12,'left',0,array('style'=>''));
									$CI->make->sDiv(array('class'=>'form-group paddingsides'));
									$CI->make->button(fa('fa fa-fw fa-ban fa-lg fa-fw').' Void',array('id'=>'void-btn','ref'=>'','class'=>'btn'),'danger');
									$CI->make->eDiv();
								$CI->make->eDivCol();
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'modal-footer','style'=>'border-top:0!important;'));
								$CI->make->button(' Cancel',array('data-dismiss'=>'modal'));
								$CI->make->button(' Save',array('id'=>'set-schedule-submit2'));
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDivCol();
			// $CI->make->sDiv(array('class'=>'reasons-div center-loads-div'));
			// 	$CI->make->sDivRow();
			// 		$buttons = array(
			// 			"Wrong Items Ordered",
			// 			"No Show Pick up",
			// 			"No Show Delivery",
			// 			"Took Too long",
			// 			"Customer Did Not Like it",
			// 			"Manager Comp",
			// 			"Employee Training",
			// 		);
			// 		foreach ($buttons as $text) {
			// 			$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
			// 				$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red reason-btns double'));
			// 			$CI->make->eDivCol();
			// 		}
			// 		$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
			// 			$CI->make->button("Other Reason",array('class'=>'btn-block cpanel-btn-teal double','id'=>'cancel-other-reason-btn'));
			// 		$CI->make->eDivCol();
			// 		$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
			// 			$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('class'=>'btn-block cpanel-btn-orange cancel-reason-btn double'));
			// 		$CI->make->eDivCol();
			// 	$CI->make->eDivRow();
			// $CI->make->eDiv();
			$CI->make->sDivCol(6,'left',0,array());
				$CI->make->sDiv(array('style'=>'margin:5px','class'=>'modal fade','id'=>'reasons_div','tabindex'=>'-1','role'=>'dialog','aria-labelledby'=>'staticBackdropLabel2','aria-hidden'=>'true'));
					$CI->make->sDiv(array('class'=>'modal-dialog','role'=>'document'));
						$CI->make->sDiv(array('class'=>'modal-content','style'=>'width: 100%;margin: 0 auto;'));
							$CI->make->sDiv(array('class'=>'modal-header'));
								$CI->make->span('X',array('class'=>'close modal_close sched-modal-close','data-dismiss'=>'modal','aria-hidden'=>'true'));
								$CI->make->H(3,' ',array('class'=>'modal-title','id'=>'staticBackdropLabel2'));
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'modal-body'));
							$CI->make->sDivRow();
								$buttons = array(
									"Wrong Items Ordered",
									"No Show Pick up",
									"No Show Delivery",
									"Took Too long",
									"Customer Did Not Like it",
									"Manager Comp",
									"Employee Training",
								);
								foreach ($buttons as $text) {
									$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
										$CI->make->button($text,array('class'=>'btn-block cpanel-btn-red reason-btns double'),'danger');
									$CI->make->eDivCol();
								}
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button("Other Reason",array('class'=>'btn-block cpanel-btn-teal double','id'=>'cancel-other-reason-btn'),'danger');
								$CI->make->eDivCol();
								$CI->make->sDivCol(4,'left',0,array("style"=>'margin-bottom:10px;'));
									$CI->make->button(fa('fa-reply fa-lg fa-fw')." Back",array('class'=>'btn-block cpanel-btn-orange cancel-reason-btn double'),'primary');
								$CI->make->eDivCol();
								$CI->make->sDivCol(12);
								$CI->make->button(fa('fa-refresh fa-2x fa-fw'),array('id'=>'refresh-btn','class'=>'btn-block no-raduis cpanel-btn-orange double hidden'),'danger');
							$CI->make->eDivCol();
							$CI->make->eDivRow();

							// $CI->make->hidden('tsales_id','');
							// $CI->make->hidden('ttype','');
							// $CI->make->hidden('status','');
							// 	$CI->make->sDivCol(12,'left',0,array());
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->input('Add Deposit Amount','add_deposit',null,null,array('class'=>'form-control','style'=>'width:55%'));
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// 	$CI->make->sDivCol(12,'left',0,array());
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->input('Reservation Date','date-selected2',null,null,array('class'=>'form-control health-center-input daterangepicker','id'=>"date-selected2",'data-date-format'=>"dd-mm-yyyy",'style'=>'margin-top: -75px;width:50%'));
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// 	$CI->make->sDivCol(12,'left',0,array('style'=>'margin-top:25px'));
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->time('Reservation Time','time-select2',null,null,array('id'=>'time-select2','style'=>'width:50%!important'));
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// 	$CI->make->sDivCol(12,'left',0,array('style'=>''));
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->select("Tbl/Room #",'tbl_id',$get_tbl_id,'',array('style'=>'width:55%'));
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// 	$CI->make->sDivCol(12,'left',0,array('style'=>''));
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->button(fa('fa-plus fa-lg fa-fw').' Add Advance Order',array('id'=>'advance_order_btns','ref'=>'','class'=>'btn'),'success');
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// 	$CI->make->sDivCol(12,'left',0,array('style'=>''));
							// 		$CI->make->sDiv(array('class'=>'form-group paddingsides'));
							// 		$CI->make->button(fa('fa fa-fw fa-ban fa-lg fa-fw').' Void',array('id'=>'void-btn','ref'=>'','class'=>'btn'),'danger');
							// 		$CI->make->eDiv();
							// 	$CI->make->eDivCol();
							// $CI->make->eDiv();
							// $CI->make->sDiv(array('class'=>'modal-footer','style'=>'border-top:0!important;'));
							// 	$CI->make->button(' Cancel',array('data-dismiss'=>'modal'));
							// 	$CI->make->button(' Save',array('id'=>'set-schedule-submit2'));
							// $CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();
	return $CI->make->code();
}
function reserveTablesPage($type,$date_selected="",$time_selected="",$cust_id=""){
	$CI =& get_instance();
		$CI->make->sDiv(array('id'=>'tables'));
			#NAVBAR
			$CI->make->hidden('dine_type',$type);
			$CI->make->hidden('date_selected',$date_selected);
			$CI->make->hidden('time_selected',$time_selected);
			$CI->make->hidden('cust_id',$cust_id);
			$CI->make->sDiv(array('class'=>'select-table-div loads-div','id'=>'select-table'));

				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'SELECT A Function Room',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-sign-out fa-lg fa-fw').' EXIT',array('id'=>'exit-btn','class'=>'btn-block tables-btn'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
				$CI->make->eDiv();
				$CI->make->sDiv(array('id'=>'image-con'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'no-guest-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								$CI->make->H(3,'No. Of Guest',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->append(onScrNumDotPad('guest-input','guest-enter-btn'));
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->sDiv(array('class'=>'occupied-div loads-div','style'=>'display:none;'));
				$CI->make->sDiv(array('class'=>'nav-btns-con'));
					$CI->make->sDivRow();
						$CI->make->sDivCol(10);
							$CI->make->sDiv(array('class'=>'title bg-red'));
								// $CI->make->H(3,'<span id="occ-num"></span> Is In Use',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
								$CI->make->H(3,'RESERVATION',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(2);
							$CI->make->sDiv(array('class'=>'exit'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' BACK',array('id'=>'back-occ-btn','class'=>'btn-block tables-btn-red'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(12);
							$CI->make->sDiv(array('class'=>'bg-orange'));
								// $CI->make->H(3,'Table is currently in use. Choose from the following options to continue.',array('class'=>'headline text-center text-uppercase','style'=>'padding:12px;'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDivRow(array('style'=>'margin-top:10px;'));
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-search fa-lg fa-fw').' RECALL',array('id'=>'exit-btn','class'=>'btn-block tables-btn-red double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								$CI->make->button(fa('fa-file fa-lg fa-fw').' Start New',array('id'=>'start-new-btn','class'=>'btn-block tables-btn-green double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
						$CI->make->sDivCol(4);
							$CI->make->sDiv(array('style'=>'margin:10px;'));
								// $CI->make->button(fa('fa-check-square-o fa-lg fa-fw').' Settle',array('id'=>'exit-btn','class'=>'btn-block tables-btn-orange double'));
							$CI->make->eDiv();
						$CI->make->eDivCol();
					$CI->make->eDivRow();
					$CI->make->sDiv(array('class'=>'occ-orders-div'));
					$CI->make->eDiv();
				$CI->make->eDiv();
			$CI->make->eDiv();

		$CI->make->eDiv();
	return $CI->make->code();
}
function printerSetupPage(){
	$CI =& get_instance();
	$user = $CI->session->userdata('user');
		$CI->make->sDiv(array('id'=>'cashier-panel','class'=>''));
			$CI->make->sDivRow();
				$CI->make->sDivCol(12);
					$CI->make->sDiv(array('class'=>'cpanel-center','style'=>'min-height:420px;'));
							$CI->make->sForm("",array('id'=>'printer-setup-form'));
								$CI->make->printerSetupDrop('KITCHEN PRINTER','kitchen_printer','KITCHEN_PRINTER',null,array('class'=>''));
								$CI->make->printerSetupDrop('BEVERAGE PRINTER','beverage_printer','BEVERAGE_PRINTER',null,array('class'=>''));
								$CI->make->printerSetupDrop('EXTRA PRINTER','extra_printer','PRINT1_PRINTER',null,array('class'=>''));
								$CI->make->button(fa('fa-check fa-lg fa-fw').' Submit',array('id'=>'print-submit-btn','class'=>'btn-block cpanel-btn-green'));
								$CI->make->button(fa('fa-reply fa-lg fa-fw').' Go Back',array('id'=>'back-btn','class'=>'btn-block cpanel-btn-red'));
							$CI->make->eForm();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivRow();
		$CI->make->eDiv();
	return $CI->make->code();
}

function build_sorter_object($key) {
    return function ($a, $b) use ($key) {;
        return strnatcmp($a->$key, $b->$key);
    };
}
?>