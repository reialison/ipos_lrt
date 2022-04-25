<script>
$(document).ready(function(){
	<?php if($use_js == 'printReceiptJs'): ?>
		$('#search-btn').click(function(){
			$("#search-form").rOkay({
				btn_load		: 	$('#search-btn'),
				btn_load_remove	: 	true,
				addData			: 	'change_db=main',
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$("#results-div").html('');
										$("#results-div").html(data.code);
										$.each(data.ids,function(key,id){
											view_div(id);
										});
									}
			});
			return false;
		});
		$('#print-btn').click(function(){
			var id = $('#print-div').attr('ref-id');
			var btn = $(this);
			btn.goLoad();
			if(id != ""){
				$.post(baseUrl+'reprint/view/'+id+'/0',function(data){
					// $('#print-div').html(data);
					btn.goLoad({load:false});
				});
			}
			return false;
		});
		function view_div(id){
			$('#rec-'+id).click(function(){
				var btn = $(this);
				btn.goLoad();
				$('#print-div').html('');
				$('#print-div').attr('ref-id',id);
				$.post(baseUrl+'reprint/view/'+id,function(data){
					$('#print-div').html(data);
					btn.goLoad({load:false});
				});
				return false;
			});
		}

		<?php elseif($use_js == 'printReceiptAllJs'): ?>
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});

 		$('#gen-rep').click(function(){
			$("#search-form").rOkay({
				btn_load		: 	$('#gen-rep'),
				btn_load_remove	: 	true,
				// addData			: 	'change_db=main',
				// asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$("#print-div").html('');
										$("#print-div").html(data);
										// $.each(data.ids,function(key,id){
										// 	view_div(id);
										// });
									}
			});
			return false;
		});

		$('#print-btn').click(function(){
			// var id = $('#print-div').attr('ref-id');
			var btn = $(this);
			btn.goLoad();
			// if(id != ""){
			formData = 'calendar_range='+$('#calendar_range').val();
			$.post(baseUrl+'reprint/printAll',formData,function(data){
				// $('#print-div').html(data);
				console.log(data);
				rMsg('Receipts has been regenerated on C:/RECEIPT','success');
				btn.goLoad({load:false});
			});
			// }
			return false;
		});

	<?php elseif($use_js == 'printReceipt2Js'): ?>
		$(".timepicker").timepicker({
		    showInputs: false
		});
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});
		$('#search-btn').click(function(){
			$("#search-form").rOkay({
				btn_load		: 	$('#search-btn'),
				btn_load_remove	: 	true,
				addData			: 	'change_db=main',
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$("#results-div").html('');
										$("#results-div").html(data.code);
										$.each(data.ids,function(key,id){
											view_div(id);
										});
									}
			});
			return false;
		});
		$('#print-btn').click(function(){
			var id = $('#print-div').attr('ref-id');
			var btn = $(this);
			btn.goLoad();
			if(id != ""){
				$.post(baseUrl+'reprint/view/'+id+'/0',function(data){
					// $('#print-div').html(data);
					btn.goLoad({load:false});
				});
			}
			return false;
		});
		function view_div(id){
			$('#rec-'+id).click(function(){
				var btn = $(this);
				btn.goLoad();
				$('#print-div').html('');
				$('#print-div').attr('ref-id',id);
				$.post(baseUrl+'reprint/view/'+id,function(data){
					$('#print-div').html(data);
					btn.goLoad({load:false});
				});
				return false;
			});
		}

		<?php elseif($use_js == 'printReceiptAll2Js'): ?>
		$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});

 		$('#gen-rep').click(function(){
			$("#search-form").rOkay({
				btn_load		: 	$('#gen-rep'),
				btn_load_remove	: 	true,
				// addData			: 	'change_db=main',
				// asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										$("#print-div").html('');
										$("#print-div").html(data);
										// $.each(data.ids,function(key,id){
										// 	view_div(id);
										// });
									}
			});
			return false;
		});

		$('#print-btn').click(function(){
			// var id = $('#print-div').attr('ref-id');
			var btn = $(this);
			btn.goLoad();
			// if(id != ""){
			formData = 'calendar_range='+$('#calendar_range').val();
			$.post(baseUrl+'reprint/printAll',formData,function(data){
				// $('#print-div').html(data);
				console.log(data);
				rMsg('Receipts has been regenerated on C:/RECEIPT','success');
				btn.goLoad({load:false});
			});
			// }
			return false;
		});

	<?php elseif($use_js == 'viewReceiptJS'): ?>
		// alert('sss');
		view_receipt();

		function view_receipt(){
			// $('#rec-'+id).click(function(){
			// 	var btn = $(this);
			// 	btn.goLoad();
				id = 1;
				// alert(id);

				$('#print-div').html('');
				$('#print-div').attr('ref-id',id);
				$.post(baseUrl+'reprint/view_receipt_paid/'+id,function(data){
					$('#print-div').html(data);

					$('#print-btn-paid').click(function(){
						// alert('aaaa');
						var id = $('#print-div').attr('ref-id');
						// alert(id);
						var btn = $(this);
						btn.goLoad();
						if(id != ""){
							$.post(baseUrl+'reprint/view_receipt_paid/'+id+'/0',function(data){
								// $('#print-div').html(data);
								btn.goLoad({load:false});
							});
						}
						return false;
					});

					$('#print-btn-email').click(function(){
						// alert('aaaa');
						// var id = $('#print-div').attr('ref-id');
						// // alert(id);
						// var btn = $(this);
						// btn.goLoad();
						// if(id != ""){
						// 	$.post(baseUrl+'reprint/view_receipt_paid/'+id+'/0',function(data){
						// 		// $('#print-div').html(data);
						// 		btn.goLoad({load:false});
						// 	});
						// }

						$.rPopForm({
							loadUrl : 'email/email_form_receipt',
							passTo	: '',
							title	: '<center>Please put an email address.</center>',
							noButton: 1,
							// rform 	: 'guide_form',
							onComplete : function(){
											// goTo('menu/subcategories');
										 }
						});


						return false;
					});


					// btn.goLoad({load:false});
				});
				// return false;
			// });
		}

	<?php endif; ?>
});
</script>