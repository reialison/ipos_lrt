<?php
	$this->load->view('parts/load');

	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	
?>