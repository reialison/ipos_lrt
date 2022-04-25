<?php
function splashPage($now=null){
	$CI =& get_instance();
		$CI->make->span('',array('id'=>'test'));
		$CI->make->sDiv(array('id'=>'manager'));		
			$CI->make->sDiv(array('id'=>'splashLoad'));		
	
			$CI->make->eDiv();
		$CI->make->eDiv();
	return $CI->make->code();
}
function commercialPage($splashes=array()){
	$CI =& get_instance();
		foreach ($splashes as $res) {
			$src = base_url().$res->img_path;
		    $file_type = strtolower(end(explode('.',$res->img_path)));
		    if($file_type == "mp4"){
				// die();
				$CI->make->hidden("video-splash",$src,array('class'=>'splash-vid'));
		    }else{
				$CI->make->hidden('splash-'.$res->img_id,$src,array('class'=>'splash-imgs'));
		    }
		}
		$CI->make->sDiv(array('class'=>'splash-img-div' ));	
		$CI->make->eDiv();		
	return $CI->make->code();
}
function commercialPage2($splashes=array()){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(12);
				$CI->make->sDiv(array('style'=>'margin:30px;'));	
					$CI->make->sBox('default',array('class'=>'box-solid'));
						$CI->make->sBoxBody();
							// $img = array(
							// 		array('url'=>base_url().'img/splashPages/splashPage1.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage2.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage3.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage4.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage5.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage6.png','params'=>array('style'=>'height:500px;width:750px;'))
							// );
							// $CI->make->carousel('carousel',$img);
							foreach ($splashes as $res) {
								$src ="data:image/jpeg;base64,".base64_encode($res->img_blob);
								$img[] = array(
									'url'=>$src,'params'=>array('style'=>'height:500px;width:750px;')
								);
							}
							if(count($splashes) > 0){
								$CI->make->carousel('carousel',$img);
							}
						$CI->make->eBoxBody();
					$CI->make->eBox();
				$CI->make->eDiv();
			$CI->make->eDiv();
			$CI->make->eDivCol();
		$CI->make->eDivRow();		
	return $CI->make->code();
}
function transactionPage($splashes=array(),$user,$set){
	$CI =& get_instance();
		$CI->make->sDivRow();
			$CI->make->sDivCol(12);	

				$CI->make->sDivCol(12);	
					$CI->make->hidden('img_vid',iSetObj($set,'img_vid'));
					$CI->make->sDiv(array('class'=>'panel','style'=>'background-color:#FC2400;'));
							$CI->make->h(3,"<center><b>Hello I'm ".ucwords($user['username'])."!</b></center>",array('style'=>'color:#fff;font-style:bold;'));
							$CI->make->eDiv();
					$CI->make->eDiv();
					$set = iSetObj($set,'img_vid');
					// echo "<pre>",print_r($set),"</pre>";die();
				$CI->make->sDivCol(7);
					$CI->make->sDiv(array('style'=>'margin:10px;margin-top:10px;'));
					if($set == "0"){
						$CI->make->sBox('default',array('class'=>'box-solid','style'=>'height:580px;'));					
					}
						$CI->make->sBoxBody();
						
							// $img = array(
							// 		array('url'=>base_url().'img/splashPages/splashPage1.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage2.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage3.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage4.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage5.png','params'=>array('style'=>'height:500px;width:750px;')),
							// 		array('url'=>base_url().'img/splashPages/splashPage6.png','params'=>array('style'=>'height:500px;width:750px;'))
							// );
							// $CI->make->carousel('carousel',$img);
							// foreach ($splashes as $res) {
							// 	$src = base_url().$res->img_path;
							// 	$img[] = array(
							// 		'url'=>$src,'params'=>array('style'=>'height:auto;width:100%;')
							// 	);
							// }
							// if(count($splashes) > 0){
							// 	$CI->make->carousel('carousel',$img);
							// }
							$vid_src = "";
							foreach ($splashes as $res) {
								$src = base_url().$res->img_path;
							    $file_type = strtolower(end(explode('.',$res->img_path)));
							    if($file_type == "mp4"){
									// die();
									$CI->make->hidden("video-splash",$src,array('class'=>'splash-vid'));
									$vid_src= $res->img_path;
							    }else{
									$CI->make->hidden('splash-'.$res->img_id,$src,array('class'=>'splash-imgs'));
							    }
							}
						    if($set == "0"){
								$CI->make->sDiv(array('class'=>'splash-img-div' ));
								$CI->make->eDiv();
						    }else{
								$CI->make->sDiv(array('class'=>'splash-vid-div col-md-12','style'=>'background: #000;height: 580px;'));
								$CI->make->append('<center><video width="838" class="col-md-12" loop autoplay style="margin-top:80px;right: 0;bottom: 0;min-width: 100%; min-height: 100%;"><source src="'.base_url().$vid_src.'" type="video/mp4"></video></center>');
								$CI->make->eDiv();
						    }

						$CI->make->eBoxBody();
					if($set == "0"){
						$CI->make->eBox();
					}
					$CI->make->eDiv();

				$CI->make->eDivCol();
				$CI->make->sDivCol(5);
					$CI->make->sDiv(array('style'=>'margin:10px;padding:0px;margin-left:0px;'));
						$CI->make->sDiv(array('style'=>'background-color:#FFF;height:500px;'));
							$CI->make->H(3,'Type',array('id'=>'trans-header','class'=>'receipt text-center text-uppercase','style'=>'padding-top:10px;font-size:28px;'));
							$CI->make->H(5,'TIME',array('id'=>'trans-datetime','class'=>'receipt text-center','style'=>'padding-top:5px;'));
							$CI->make->sDiv(array('style'=>'margin-left:10px;margin-right:10px;'));
								$CI->make->append('<hr>');
							$CI->make->eDiv();
							$CI->make->sDiv(array('id'=>'transBody','class'=>'listings','style'=>'height:380px;font-size:16px;'));

							$CI->make->eDiv();
							// $CI->make->sDiv(array('style'=>'margin-left:10px;margin-right:10px;'));
							// 	$CI->make->append('<hr>');
							// $CI->make->eDiv();
							$CI->make->sDiv(array('class'=>'foot-det','style'=>'height:99px;background-color:#FC2400;padding:15px;'));
								$CI->make->H(3,'TOTAL: <span id="total-txt">0.00</span>',array('class'=>'receipt text-center','style'=>'color:#fff;font-size:50px;'));
								$CI->make->H(5,'DISCOUNTS: <span id="discount-txt">0.00</span>',array('class'=>'receipt text-center','style'=>'color:#fff;'));
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
			$CI->make->eDivCol();
		$CI->make->eDivRow();		
	return $CI->make->code();
}
function new_splashview($splashes=array(),$user,$set){
	$CI =& get_instance();

		$CI->make->sDivRow();
			$CI->make->sDivCol(12);	

				$CI->make->sDivCol(12);	
					$CI->make->hidden('img_vid',iSetObj($set,'img_vid'));
					
					$set = iSetObj($set,'img_vid');

				$CI->make->sDivCol(7,"",0,array('style'=>'padding:55px;width:548.55px;'));
					$CI->make->sDiv(array());
						$CI->make->sDiv(array('style'=>'background-color:#FFF;height:500px;'));
							$CI->make->H(3,'Type',array('id'=>'trans-header','class'=>'receipt splash-header-title'));
							// $CI->make->H(5,'',array('id'=>'trans-datetime','class'=>'receipt text-center','style'=>'padding-top:5px;'));
							$CI->make->sDiv(array('style'=>'margin-left:10px;margin-right:10px;'));
								// $CI->make->append('<hr>');
							$CI->make->eDiv();
							$CI->make->sDiv(array('id'=>'transBody','class'=>'splash-item-breakdown','style'=>'height:374px;margin-top:30px;'));

							$CI->make->eDiv();
							// $CI->make->sDiv(array('style'=>'margin-left:10px;margin-right:10px;'));
							// $CI->make->eDiv();
								// $CI->make->append('<hr>');
							$CI->make->sDiv(array('class'=>'foot-det','style'=>'height:99px;'));
								$CI->make->H(3,'Your Bill',array('class'=>'receipt splash-header-title','style'=>'margin-top:5px;'));
								$CI->make->H(3,'SUB-TOTAL <span id="sub-total-txt" class="float-right">0.00</span>',array('class'=>'splash-header-sub-title','style'=>'margin-top:30px;'));
								$CI->make->H(5,'DISCOUNTS <span id="discount-txt" class="float-right">0.00</span>',array('class'=>'splash-header-sub-title'));
								$CI->make->H(5,'ITEM TOTAL <span id="item-total-txt" class="float-right">0.00</span>',array('class'=>'splash-item-total','style'=>'margin-top:23px;'));
								$CI->make->H(3,'CASH <span id="cash-txt" class="float-right">0.00</span>',array('class'=>'splash-header-sub-title','style'=>'margin-top:10px;'));
								$CI->make->H(5,'CHANGE <span id="change-txt" class="float-right">0.00</span>',array('class'=>'splash-header-sub-title'));
							$CI->make->eDiv();
						$CI->make->eDiv();
					$CI->make->eDiv();
				$CI->make->eDivCol();
				$CI->make->sDivCol(5);
					$CI->make->sDiv(array('style'=>''));
						$CI->make->sBoxBody();
							$vid_src = "";
							foreach ($splashes as $res) {
								$src = base_url().$res->img_path;
							    $file_type = strtolower(end(explode('.',$res->img_path)));
								$CI->make->hidden('splash-'.$res->img_id,$src,array('class'=>'splash-imgs'));
							}
				               $thumb = base_url().'uploads/splash/slider2.jpg';
								$CI->make->sDiv(array('class'=>'splash-img-div' ));
								$CI->make->eDiv();

						$CI->make->eBoxBody();
					$CI->make->eDiv();

				$CI->make->eDivCol();


			$CI->make->eDivCol();
		$CI->make->eDivRow();		
	return $CI->make->code();
}