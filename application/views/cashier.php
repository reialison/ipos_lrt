<?php
	$this->load->view('cashier/head');
	$this->load->view('cashier/body');
	if(isset($load_js))
		$this->load->view('js/'.$load_js);
	$this->load->view('cashier/foot');
?>

<script src="<?= base_url()?>js/min/moment.min.js"></script>
<script src="<?= base_url()?>js/fullcalendar/fullcalendar.min.js"></script>
<script src="<?= base_url()?>js/calendar.init.js"></script>