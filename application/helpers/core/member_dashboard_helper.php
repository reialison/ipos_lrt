<?php
function dashboardMain($total_sales=0,$collected=0,$balance=0,$dl_trans=0,$avg_spend=0,$total_members=0,$curr_member_month=0,$inactive_member){
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
						$col_num = 3;
						// $CI->make->eDiv();
					}else{
						$col_num = 3;
					}

					$CI->make->sDivCol($col_num);
				    	$CI->make->sDiv(array('class'=>'dashboard-stat blue'));
				        	$CI->make->tdiv(fa('fa-desktop'),array('class'=>'visual'));
				        	$CI->make->sDiv(array('class'=>'details','id'=>'last-gt-box'));
				        	        // echo num($lastGT);die();
				        		$CI->make->tdiv('GRAND TOTAL SALES',array('class'=>'desc'));
				        		$CI->make->tdiv(num($total_sales),array( 'class'=>'number','id'=>""));
				        		

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
					    		$CI->make->tdiv('TOTAL COLLECTED <br />PAYMENTS',array('class'=>'desc'));
					    		$CI->make->tdiv(num($collected),array('class'=>'number','style'=>'line-height:5px'));					    		
					    	$CI->make->eDiv();
					    	$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
					    $CI->make->eDiv();
					$CI->make->eDivCol();
					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat  green'));
							$CI->make->tdiv(fa('fa-users'),array('class'=>'visual '));
							$CI->make->sDiv(array('class'=>'details'));
								$CI->make->tdiv('TOTAL AMOUNT BALANCE',array('class'=>'desc'));
								$CI->make->tdiv(num($balance),array('class'=>'number'));								
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDivCol();

					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat  yellow'));
							$CI->make->tdiv(fa('fa-users'),array('class'=>'visual '));
							$CI->make->sDiv(array('class'=>'details'));
								$CI->make->tdiv('TOTAL OVERDUE TRANSACTIONS',array('class'=>'desc'));
								$CI->make->tdiv($dl_trans,array('class'=>'number','style'=>'line-height:5px'));								
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDivCol();
					if(DASHBOARD_TIME){
						$CI->make->sDivCol($col_num);
							$CI->make->sDiv(array('class'=>'dashboard-stat purple'));
								$CI->make->tdiv(fa('fa-calendar'),array('class'=>'visual'));
								$CI->make->sDiv(array('class'=>'details'));
									$CI->make->tdiv('9:00 PM',array('class'=>'number','id'=>'box-time'));
									$CI->make->tdiv(null,array('class'=>'desc','id'=>'box-day',));
									$CI->make->tdiv(null,array('class'=>'desc','id'=>'box-date'));
								$CI->make->eDiv();
								$CI->make->sDiv(array('class'=>'more','id'=>''));
					        	$CI->make->eDiv();
							$CI->make->eDiv();
						$CI->make->eDivCol();
					}

				$CI->make->eDivRow();

				$CI->make->sDivRow();
					$CI->make->sDivCol($col_num);
				    	$CI->make->sDiv(array('class'=>'dashboard-stat blue'));
				        	$CI->make->tdiv(fa('fa-desktop'),array('class'=>'visual'));
				        	$CI->make->sDiv(array('class'=>'details','id'=>'last-gt-box'));
				        	        // echo num($lastGT);die();
				        		$CI->make->tdiv('MEMBERS AVERAGE SPENDING',array('class'=>'desc'));
				        		$CI->make->tdiv(num($avg_spend),array( 'class'=>'number','id'=>"",'style'=>'line-height:5px'));
				        		

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
					    		$CI->make->tdiv('TOTAL ACTIVE MEMBERS',array('class'=>'desc'));
					    		$CI->make->tdiv($total_members,array('class'=>'number'));					    		
					    	$CI->make->eDiv();
					    	$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
					    $CI->make->eDiv();
					$CI->make->eDivCol();
					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat  green'));
							$CI->make->tdiv(fa('fa-users'),array('class'=>'visual '));
							$CI->make->sDiv(array('class'=>'details'));
								$CI->make->tdiv('JOINED THIS MONTH',array('class'=>'desc'));
								$CI->make->tdiv($curr_member_month,array('class'=>'number'));								
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDivCol();

					$CI->make->sDivCol($col_num);
						$CI->make->sDiv(array('class'=>'dashboard-stat  yellow'));
							$CI->make->tdiv(fa('fa-users'),array('class'=>'visual '));
							$CI->make->sDiv(array('class'=>'details'));
								$CI->make->tdiv('INACTIVE MEMBERS',array('class'=>'desc'));
								$CI->make->tdiv($inactive_member,array('class'=>'number'));								
							$CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'more','id'=>''));
				        	$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDivCol();

				$CI->make->eDivRow();
			################################################
			########## GRAPHS
			################################################
				$CI->make->sDivRow();
					$CI->make->sDivCol(12);
							$CI->make->sBox('solid');
								// $CI->make->sBoxHead();
								// 	$CI->make->boxTitle(fa('fa-money fa-fw').' Transactions Sales');
								// $CI->make->eBoxHead();
								$CI->make->sBoxHead(array('class'=>''));
									$CI->make->h(3,fa('icon-calculator').' Earnings Overview ('. date('Y').')',array('class'=>'caption-subject font-red bold'));
								$CI->make->eBoxHead();
								$CI->make->sBoxBody();
									$CI->make->sDivRow(array('class'=>'chart-responsive no-padding'));
										// $CI->make->sDivCol(8);
										// 	$CI->make->sDiv(array('class'=>'chart','id'=>'bar-chart','style'=>'height:300px;'));
										// 	$CI->make->eDiv();
										// $CI->make->eDivCol();
										$CI->make->sDivCol(12);
											$CI->make->sDiv(array('id'=>'sales-chart','class'=>'chart','style'=>'height: 300px; position: relative;'));
											$CI->make->eDiv();
										$CI->make->eDivCol();
									$CI->make->eDivRow();
								$CI->make->eBoxBody();
							$CI->make->eBox();
					$CI->make->eDivCol();		
						
				$CI->make->eDivRow();
		$CI->make->sDiv();
	$CI->make->eDiv();

	return $CI->make->code();
}
?>