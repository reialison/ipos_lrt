<style>
    #print-rcp{display: none;}
</style>
<div id="print-rcp"></div>

<?php
	$this->load->view('parts/head');
	$this->load->view('parts/topNav');

	$this->load->view('parts/body_guide');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('parts/foot');
?>