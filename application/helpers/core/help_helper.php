<?php
function makeHelp($data){
	$CI =& get_instance();
	$CI->load->library('make');	

			// echo "<pre>",print_r($data),"</pre>";die();
	$CI->make->append("<div class='help_div'>");
	$CI->make->startForm("","POST",array('id'=>''));
		$CI->make->startDiv("",false,array("class"=>"col-md-12 col-lg-12"));
			$CI->make->startRow();
				$CI->make->startDiv("",false,array("class"=>"col-md-8 col-lg-8"));
					$CI->make->append("<a href='".base_url()."cashier'><button class='btn btn-grey' type='button'><i class='glyphicon glyphicon-arrow-left'></i>&nbsp; Go back</button></a>");
				$CI->make->endDiv();									
				$CI->make->startDiv("",false,array("class"=>"col-md-4 col-lg-4"));
					$CI->make->input("","search","","Search",array("id"=>"search"));
			// echo "<pre>",print_r($data),"</pre>";die();
				$CI->make->endDiv();									
			$CI->make->endRow();
		$CI->make->br(2);
			$CI->make->startDiv("", false, array("id"=>"help_data"));
				if(count($data) == 0)
				{
					$CI->make->startDiv("",false,array("class"=>"col-md-12 col-lg-12"));					
						$CI->make->append("<center><p><h3>Did not match any documents.</h3></p></center>");
					$CI->make->endDiv();									
				}

			foreach ($data as $key => $v) {
				// if($v->enabled == '1' && $role == '16'){	
                   // continue;
				// }	
				    $CI->make->startDiv("",false,array("class"=>"col-md-4 col-lg-4"));					
								$CI->make->append("<video id='help-".$v->id."' controls='controls' width='280' height='210'>");
								$CI->make->append("<source src='".base_url().$v->path."'/>");
								$CI->make->append("</video>");
								$CI->make->append("<p><a href='#' ref=".$v->id." class='play_tut'><b>".$v->description."</b><a/></p>");					
						$CI->make->endDiv();									
				
			}
			

			$CI->make->endDiv();
		$CI->make->endDiv();

		
	$CI->make->endForm();	
	
$CI->make->append("</div>");
	$code = $CI->make->code();
	return $code;
}
?>