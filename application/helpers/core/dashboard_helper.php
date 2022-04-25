<?php
function dashboardMain($lastGT=0,$todaySales=0,$todayTransNo=0){
	$CI =& get_instance();
	// $CI->make->sDiv(array('style'=>'width:100%;background-color:#fff;padding-top:15px;padding-left:10px;padding-right:10px;'));
	// 	$opts = array('Today'=>'today','This Month'=>'monthly','This Year'=>'yearly');
	// 	$CI->make->sDivRow();
	// 		$CI->make->sDivCol(3,'right',9);
	// 			$CI->make->select(null,'show-drop',$opts);
	// 		$CI->make->eDivCol();
	// 	$CI->make->eDivRow();
	// $CI->make->eDiv();
	$CI->make->sDiv(array('style'=>'width:100%;background-color:#ffffff'));
		$CI->make->sDiv(array('style'=>'padding:10px;'));
			################################################
			########## BOXES
			################################################
				$CI->make->sDivRow();
					if(!DASHBOARD_TIME){
						$col_num = 4;
						// $CI->make->eDiv();
					}else{
						$col_num = 3;
					}

					$CI->make->sDivCol($col_num);
				    	$CI->make->sDiv(array('class'=>'dashboard-stat blue'));
				        	$CI->make->tdiv(fa('fa-desktop'),array('class'=>'visual'));
				        	$CI->make->sDiv(array('class'=>'details','id'=>'last-gt-box'));
				        	        // echo num($lastGT);die();

				        		$CI->make->tdiv(num($lastGT),array( 'class'=>'number','id'=>"last-gt"));
				        		$CI->make->tdiv('Last Grand Total',array('class'=>'desc'));

				        	$CI->make->eDiv();
				        	$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
				        $CI->make->eDiv();
					$CI->make->eDivCol();
					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat red'));
					    	$CI->make->tdiv(fa('icon-calculator'),array('class'=>'visual'));
					    	$CI->make->sDiv(array('class'=>'details'));
					     // var_dump($todaySales);die();
					    		$CI->make->tdiv(num($todaySales),array('class'=>'number'));
					    		$CI->make->tdiv('Today Sales',array('class'=>'desc'));
					    	$CI->make->eDiv();
					    	$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
					    $CI->make->eDiv();
					$CI->make->eDivCol();
					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat  green'));
							$CI->make->tdiv(fa('fa-users'),array('class'=>'visual '));
							$CI->make->sDiv(array('class'=>'details'));
								$CI->make->tdiv(num($todayTransNo),array('class'=>'number'));
								$CI->make->tdiv('Today Transactions',array('class'=>'desc'));
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDivCol();
					// $CI->make->sDivCol(3);
				 //    	$CI->make->sDiv(array('class'=>'info-box'));
				 //        	$CI->make->span(fa('fa-cutlery'),array('class'=>'info-box-icon  bg-blue'));
				 //        	$CI->make->sDiv(array('class'=>'info-box-content'));
				 //        		$CI->make->span('Today\'s Top Menu',array('class'=>'info-box-text'));
				 //        		$CI->make->span('Congee',array('class'=>'info-box-number'));
				 //        	$CI->make->eDiv();
				 //        $CI->make->eDiv();
					// $CI->make->eDivCol();
					if(DASHBOARD_TIME){
						$CI->make->sDivCol($col_num);
							$CI->make->sDiv(array('class'=>'dashboard-stat purple'));
								$CI->make->tdiv(fa('fa-calendar'),array('class'=>'visual'));
								$CI->make->sDiv(array('class'=>'details'));
									$CI->make->tdiv('9:00 PM',array('class'=>'number','id'=>'box-time'));
									$CI->make->tdiv(null,array('class'=>'desc','id'=>'box-day',));
									//$CI->make->sDiv(array('class'=>'progress'));
									//	$CI->make->sDiv(array('class'=>'progress-bar','style'=>'width:100%'));
									//	$CI->make->eDiv();
									//$CI->make->eDiv();
									$CI->make->tdiv(null,array('class'=>'desc','id'=>'box-date'));
								$CI->make->eDiv();
								$CI->make->sDiv(array('class'=>'more','id'=>''));
					        	$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					}

				$CI->make->eDivRow();
			################################################
			########## GRAPHS
			################################################
				$CI->make->sDivRow();
					$CI->make->sDivCol(9);
							$CI->make->sBox('solid');
								// $CI->make->sBoxHead();
								// 	$CI->make->boxTitle(fa('fa-money fa-fw').' Transactions Sales');
								// $CI->make->eBoxHead();
								$CI->make->sBoxHead(array('class'=>''));
									$CI->make->h(3,fa('icon-calculator').' Today\'s Sales',array('class'=>'caption-subject font-red bold'));
								$CI->make->eBoxHead();
								$CI->make->sBoxBody();
									$CI->make->sDivRow(array('class'=>'chart-responsive no-padding'));
										// $CI->make->sDivCol(8);
										// 	$CI->make->sDiv(array('class'=>'chart','id'=>'bar-chart','style'=>'height:300px;'));
										// 	$CI->make->eDiv();
										// $CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('id'=>'sales-chart','class'=>'chart','style'=>'height: 300px; position: relative;'));
											$CI->make->eDiv();
										$CI->make->eDivCol();
										$CI->make->sDivCol(6);
											$CI->make->sDiv(array('id'=>'bars-div'));
											$CI->make->eDiv();
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eBoxBody();
							$CI->make->eBox();
					$CI->make->eDivCol();		
					$CI->make->sDivCol(3);
						$CI->make->sBox('default',array('class'=>''));
							$CI->make->sBoxHead(array('class'=>''));
								$CI->make->h(4,fa('icon-pin').' Today\'s Top Menu',array('class'=>'caption-subject font-green bold '));
							$CI->make->eBoxHead();
// 									$CI->make->append('<div class="overlay">
//   <i class="fa fa-refresh fa-spin"></i>
// </div>');
							$CI->make->sBoxBody(array('class'=>'chart-responsive no-padding'));
								$CI->make->sDiv(array('id'=>'top-menu-box','style'=>'min-height:218px;'));
								$CI->make->eDiv();
							$CI->make->eBoxBody();
						$CI->make->eBox();
					$CI->make->eDivCol();		
				$CI->make->eDivRow();
		$CI->make->sDiv();
	$CI->make->eDiv();

	return $CI->make->code();
}
?>