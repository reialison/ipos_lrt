<script>
$(document).ready(function(){
	<?php if($use_js == 'customerFormContainerJs'): ?>
		$('.tab_link').click(function(event)
		{
			event.preventDefault();
			var id = $(this).attr('id');
			loader('#'+id);
		});
		loader('#details_link');
		function loader(btn)
		{
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var selected = $('#cust_idx').val();
			if (selected == '') {
				selected = 'add';
				disableTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			} else {
				disableTabs('.load-tab',true);
			}
			var item_id = $('#cust_idx').val();
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+item_id});
		}
		function disableTabs(id,enable)
		{
			if (enable) {
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			} else {
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}
	<?php elseif($use_js == 'customerDetailsJs'): ?>
		$('#save-btn').click(function(){
			$("#customer_details_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										rMsg(data.msg,'success');
									}
			});
			
			setTimeout(function(){
				window.location.reload();
			},1500);
			
			return false;
		});
		
		$('#fname, #mname, #lname, #suffix, #phone, #email, #street_no, #street_address, #city, #region, #zip')
		.keyboard({
			alwaysOpen: false,
			usePreview: false
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
	<?php elseif($use_js == 'customersJs'): ?>
		$('#new-customer-btn').click(function(){
			// alert('New Customer Button');
			window.location = baseUrl+'customers/cashier_customers';
			return false;
		});
		
		$('#look-up-btn').click(function(){
			// alert('Look up');
			var this_url =  baseUrl+'customers/customers_list';
			$.post(this_url, {},function(data){
				$('.customer_content_div').html(data);
				// $('#telno').attr({'value' : ''}).val('');
				
				$('.edit-line').click(function(){
					var line_id = $(this).attr('ref');
					var phone = $(this).attr('phoneref');
					var thisurl = baseUrl+'customers/load_customer_details';
					// alert('edit to : '+line_id);
					
					$.post(thisurl, {'telno' : phone}, function(data1){
						$('.customer_content_div').html(data1);
					});
					
					return false;
				});
				
			});
			return false;
		});
	
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		
		$('#pin')
		.keyboard({
			alwaysOpen: false,
			usePreview: false
		})
		.addNavigation({
			position   : [0,0],     
			toggleMode : false,     
			focusClass : 'hasFocus' 
		});
		
		$('#telno').focus(function(){
			$('#telno').attr({'value' : ''}).val('');
			return false;
		});
		
		$('#telno-login').click(function(){
			var telno = $('#telno').val();
			var this_url =  baseUrl+'customers/validate_phone_number';
			// alert('asdfg---'+telno);
			
			$.post(this_url, {'telno' : telno}, function(data){
				// alert(data);
				var parts = data.split('||');
				// alert('eto:'+parts[1]);
				if(parts[0] == 'empty'){
					rMsg('Text field is empty!','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'none'){
					rMsg('Customer does not exist.','error');
					// setTimeout(function(){
						// window.location.reload();
					// },1500);
				}else if(parts[0] == 'success'){
					rMsg('Loading customer details...','success');
					
					setTimeout(function(){
						var this_url2 =  baseUrl+'customers/load_customer_details';
						$.post(this_url2, {'telno' : parts[1]},function(data){
							$('.customer_content_div').html(data);
							// $('#telno').attr({'value' : ''}).val('');
							return false;
						});
					},1500);
						
				}
			});
			
			return false;
		});
		
		function loadCustomerDetails(){
			var this_url =  baseUrl+'customers/customer_load';
			
			$.post(this_url, {},function(data){
				$('.customer_content_div').html(data);
				$('#telno').focus();
				return false;
			});
		}
		
		loadCustomerDetails();
	
	<?php elseif($use_js == 'customerInquiryJS'): ?>
		$('#customer_id').selectpicker();
		// load_table();

		// $.noConflict();
	 //    jQuery(document).ready(function() {
	 //        jQuery('#customer_id').select2();
	 //    });
	 	// alert($('#customer_id').length);
   //  	$(document).ready(function(){
			// $('#customer_id').select2();
   //  	});

		
		$('#search-btn').click(function(){
			load_table();
			return false;
		});
		// var doc = new jsPDF();
		// var specialElementHandlers = {
		//     '#editor': function (element, renderer) {
		//         return true;
		//     }
		// };
		// $('#print-btn').click(function(){
		// 	doc.fromHTML($('#general-div').html(), 15, 15, {
		//         'width': 170,
	 //            'elementHandlers': specialElementHandlers
		//     });
		//     doc.save('menu_move.pdf');
		// 	return false;
		// });

      $('#cust-print-btn-pdf').click(function(){
			// doc.fromHTML($('#general-div').html(), 15, 15, {
		 //        'width': 170,
	  //           'elementHandlers': specialElementHandlers
		 //    });
		 //    doc.save('inv_move.pdf');
			// return false;

			var this_url = baseUrl+'customers/print_customers_inquiry_pdf';
			customer_id = $('#customer_id').val();

			// // summary = $('#summary').val();


			// // item_id = $('#item_id').val();
			// // dr = $('#daterange').val();
			formData = 'customer_id='+customer_id;
			// alert(formData);
			window.open(this_url+'?'+formData, '_blank');
			event.preventDefault();


		});

		$('#cust-print-btn').on('click',function(event)
		{
			var this_url = baseUrl+'customers/print_customer_inquiry_excel';			
			customer_id = $('#customer_id').val();

			formData = 'customer_id='+customer_id;
			// alert(formData);
			window.location = this_url+'?'+formData;
			event.preventDefault();
			return false;
		});

		function load_table(){

			// var st = $('#menu-search').find("option:selected").text();
			// if(st == ""){
			// 	rMsg('Please select a menu first.','error');
			// }else{
				var noError = $('#general-form').rOkay({
					goSubmit	: 	false,
				});
				if(noError){
					var getUrl = $('#general-form').attr('action');
					var formData = $('#general-form').serialize();
					$('body').goLoad2();
					$('#general-tbl tbody').html('');
					$.post(baseUrl+getUrl,formData,function(data){
						// alert(data);
						// console.log(data);
						$('#general-tbl tbody').html(data.html);
						$('body').goLoad2({load:false});

						$('.print-receipt').click(function(){
							var ref = $(this).attr('ref');
							var link = baseUrl + 'reprint/view/'+ref;

							$.post(link,function(data){
								$('#receipt_modal').find('.modal-body').html(data);
							      // $('#accept_modal').find('.modal-footer').find('.accept_btn').attr('ref',ref);
							      $('#receipt_modal').modal("show");
							  });							 

							return false;
						});
					},'json').fail( function(xhr, textStatus, errorThrown) {
					 	alert(xhr.responseText);
						$('body').goLoad2({load:false});
					});
				}
			// }
		}
		
 	
		
	<?php endif; ?>
});
</script>