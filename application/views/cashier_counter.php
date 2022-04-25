<?php
	$this->load->view('cashier/head');
	$this->load->view('cashier/body_counter');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('cashier/foot');
?>