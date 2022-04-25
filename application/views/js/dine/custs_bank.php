<script>
$(document).ready(function(){
	<?php if($use_js == 'indexJs'): ?>
		var height = $(document).height();
		$('.div-content').height(height - 63);
		$('.div-content').rLoad({url:baseUrl+'custs_bank/deposit'});
		$('#deposit-money-btn').click(function(){
			$('.div-content').rLoad({url:baseUrl+'custs_bank/deposit'});
			$('.ui-keyboard').remove();
			return false;
		});
		$('#deposit-lists-btn').click(function(){
			$('.div-content').rLoad({url:baseUrl+'custs_bank/customers_money'});
			return false;
		});
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
	<?php elseif($use_js == 'customersMoneyList'): ?>
		$('#customers-tbl').rTable({
			loadFrom	: 	 'custs_bank/get_custs_deposits',
			noEdit		: 	 true,			 	
			noAdd		: 	 true,			 	
		});
		
		// $(".cancel-deposit-reason-btn").click(function(){
		$(document).on('click',".cancel-deposit-reason-btn",function(){		
			// var id = $('.order-view-list').attr('ref');
			// var type = $('.order-view-list').attr('type');
			// var id = $("input[type=hidden]#tsales_id").val();
			// var type = $("input[type=hidden]#ttype").val();
			// var approver = $('#reasons_div').attr('approver');
			var id = $(this).attr('ref');
			var amount = $(this).attr('amount');
			var type = $('.order-view-list').attr('type');
			var approver = $('.order-view-list').attr('approver');
			var prev = $('#now-btn').attr('type');
			// alert(approver);
			// return false;
			var prev = $('#now-btn').attr('type');
			var old = 0;
			if(prev == 'all_trans'){
				old = 1;
			}
			var reason = $(this).text();
			var btn = $(this);
			btn.goLoad();
			formData = 'ref_no='+id+'&approver='+approver;
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/void_deposit_reason_pop/'+amount,
			  title: 'Void Customer Deposit',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "Submit",
			      className: "btn btn-guest-submit pop-manage pop-manage-green",
			      callback: function() {
			      	formData += '&reason='+$('#other-reason-txt').val()+'&credit_amount='+$('#credit_amount').val()+'&credit_note_no='+$('#credit_note_no').val();
						// alert(id);
						// return false;
						$.post(baseUrl+'cashier/void_cust_deposit/',formData,function(data){
							      		// console.log(data);
							      		// return false;
							      		// alert(JSON.stringify(data));
							      		if(data.error == ""){
							      			if(data.js_rcps){
							      				html_print(data.js_rcps);
							      			}

							      			$("#reasons_div").modal('hide');
											// $("#pickTimeModal2").modal('hide');
							      			// $("#refresh-btn").trigger('click');
							      			rMsg('Success!  Voided Customer Deposist #'+id,'success');
							      			// btn.goLoad({load:true});
							      			// location.reload();
							      			$('#customers-tbl').rTable({
												loadFrom	: 	 'custs_bank/get_custs_deposits',
												noEdit		: 	 true,			 	
												noAdd		: 	 true,			 	
											});
							      			return false;


							      		}
							      		else{
							      			rMsg(data.error,'error');
							      			btn.goLoad({load:false});
							      		}
							      	// });
							      	},'json');
			        return true;
			      }
			    },
			    cancel: {
			      label: "CANCEL",
			      className: "btn pop-manage pop-manage-red",
			      callback: function() {
			        // Example.show("uh oh, look out!");
			        btn.goLoad({load:false});
			        return true;
			      }
			    }
			  }
			});
			return false;
		});

		function html_print(js_rcps){ 
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){
					$('#print-rcp').html(v.value);
				});
			});
        	
        }
	<?php elseif($use_js == 'depositJs'): ?>
		var height = $(document).height();
		$('#search-div').height(height - 63 -265);
		$('#details-div').height(height - 63 -327);
		// $('.listings').perfectScrollbar({suppressScrollX: true});
		// $('#search-customer,.key-ins')
		// 	.keyboard({
		// 		alwaysOpen: true,
		// 		usePreview: false,
		// 		autoAccept : true
		// 	});
		$('#search-customer').focus();	
		$('#search-customer').on('keyup',function(){
			show_search();
		});	
		// $('#search-customer').keypress(function (e) {
		// 	var key = e.which;
		// 	if(key == 13){
		// 		$('#search-customer').blur();
		// 	}
		// });
		$('#add-cust-btn').click(function(){
			window.location = baseUrl+'pos_customers/customer_terminal';
		});
		$('#submit-btn').click(function(){
			// alert('ere');
			$('#deposit-form').rOkay({
				btn_load 		: 	$('#submit-btn'),
				bnt_load_remove : 	false,
				asJson 			: 	true,
				onComplete 		: 	function(data){
										// var data = JSON.parse(data);
										if(data.js_rcps){
											html_print(data.js_rcps);
										}
										location.reload();
										// alert(data);
										// var id = data.id;
										// var type = $('#trans_type').val();
										// var formData = 'type='+type+'&customer_id='+id;
										// $.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
										// 	window.location = baseUrl+'cashier/counter/'+type;
										// },'json');
									}
			});
			return false;
		});

		$('#search-customer')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
			autoAccept : true,
			change   : function(e, keyboard, el) { 
				show_search();
				
				return false;
			},

        })
        .addNavigation({
            position   : [0,0],
            toggleMode : false,
            focusClass : 'hasFocus'
        });

		$('#contact_no,#email,#remarks')
		    .keyboard({
		        alwaysOpen: false,
		        usePreview: false,
				autoAccept : true
		    })
		    .addNavigation({
		        position   : [0,0],
		        toggleMode : false,
		        focusClass : 'hasFocus'
		    });

	   $('#amount').keyboard({
                // layout: 'num',
                layout: 'custom',
                customLayout: {
                    'default': [
                    '7 8 9 {b}',
                    '4 5 6 {clear}',
                    '1 2 3 {c}',
                    '0 {dec} {empty} {a}'
                  ]
                } ,
                alwaysOpen: false,
                usePreview: false,
                autoAccept : true
            })
            .addNavigation({
                position   : [0,0],
                toggleMode : false,
                focusClass : 'hasFocus'
            });

		function show_search(){
			var txt = $('#search-customer').val();
			var ul = $('#cust-search-list');
			ul.find('li').remove();
			ul.goLoad();
			$.post(baseUrl+'cashier/search_customers/'+txt,function(data){
				if(!$.isEmptyObject(data)){
					$.each(data,function(cust_id,val){
						var li = $('<li/>')
									.attr({'class':'cust-row','id':'cust-row-'+cust_id})
									.css({'cursor':'pointer','border-bottom':'1px solid #ddd'})
									.click(function(){
										var li = $(this);
										$.post(baseUrl+'cashier/get_customers/'+cust_id,function(cust){
											$.each(cust,function(key,val){
												var name = val.fname+' '+val.mname+' '+val.lname+' '+val.suffix
												$('#full_name').val(name);
												$('#cust_id').val(val.cust_id);
												$('#contact_no').val(val.phone);
												$('#email').val(val.email);
												selDeSel(li);
											});
										},'json');
									});
						$('<h4/>').css({'font-size':'14px','padding':'3px','margin':'3px'}).html(val.name).appendTo(li);
						$('<h4/>').css({'font-size':'12px','padding':'3px','margin':'3px'}).html(val.email).appendTo(li);
						li.appendTo(ul);
					});
					// $('.listings').perfectScrollbar('update');
				}
				ul.goLoad({load:false});
			},'json');
		}
		function selDeSel(li){
			var par = li.parent();
			par.find('li').removeClass('selected');
			li.addClass('selected');
		}	
		disEnCard();
		$('#amount_type').change(function(){
			disEnCard();			
		});
		function disEnCard(){
			var type = $('#amount_type').val();
			if(type == 'cash'){
				// $('.for-cards').attr('readOnly','readOnly');
				$('.for-cards').parent().hide();
				$('.for-cards').removeClass('rOkay');
			}
			else{
				$('.for-cards').parent().show();
				$('.for-cards').addClass('rOkay');
				// $('.for-cards').removeAttr('readOnly');
			}
			$('.for-cards').val('');
		}

		function html_print(js_rcps){ 
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){
					$('#print-rcp').html(v.value);
				});
			});
        	
        }
	<?php elseif($use_js == 'reservationIndexJs'): ?>
		var height = $(document).height();
		// alert('haha');
		$('.div-content').height(height - 63);
		$('.div-content').rLoad({url:baseUrl+'custs_bank/deposit_reservation'});
		$('#deposit-money-btn').click(function(){
			$('.div-content').rLoad({url:baseUrl+'custs_bank/deposit_reservation'});
			$('.ui-keyboard').remove();
			return false;
		});
		$('#deposit-lists-btn').click(function(){
			$('.div-content').rLoad({url:baseUrl+'custs_bank/customers_money'});
			return false;
		});
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
	<?php elseif($use_js == 'rdepositJs'): ?>
		var height = $(document).height();
		$('#search-div').height(height - 63 -265);
		$('#details-div').height(height - 63 -327);
		// $('.listings').perfectScrollbar({suppressScrollX: true});
		// $('#search-customer,.key-ins')
		// 	.keyboard({
		// 		alwaysOpen: true,
		// 		usePreview: false,
		// 		autoAccept : true
		// 	});
		$('#search-customer').focus();	
		$('#search-customer').on('keyup',function(){
			show_search();
		});	
		// $('#search-customer').keypress(function (e) {
		// 	var key = e.which;
		// 	if(key == 13){
		// 		$('#search-customer').blur();
		// 	}
		// });
		$('#add-cust-btn').click(function(){
			window.location = baseUrl+'pos_customers/customer_terminal';
		});
		$('#submit-btn').click(function(){
			// var txtname = $(this).closest("rdeposit-form").find('#cust_id').val();
			// var time_schedule = $('#time_schedule').val();
			// alert(time_schedule);
			// swal({
   //              title: " ", 
   //              html: true,
   //              text: 'Do you want to reserve a table?',  
   //              // confirmButtonText: "V redu", 
   //              allowOutsideClick: "true" ,
   //              showDenyButton: true,
   //              showCancelButton: true,
   //              confirmButtonText: `Yes`,
   //              cancelButtonText: `No`,
   //          }, function (result) {
   //            // Your code
   //            if (result) {
                $('#rdeposit-form').rOkay({
				btn_load 		: 	$('#submit-btn'),
				bnt_load_remove : 	false,
				asJson 			: 	false,
				onComplete 		: 	function(data){
										// location.reload();
										var date_schedule = $('#date_schedule').val();
										var time_schedule = $('#time_schedule').val();
										var cust_id = $('#cust_id').val();
										// alert(cust_id);
										// return false;
										// var type = $('#trans_type').val();
										// var formData = 'cust_id='+cust_id+'&time_schedule='+time_schedule+'&date_schedule='+date_schedule;
										// $.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
											// alert(data);
											// var t = JSON.parse(data);
									  //       var antigen = parseFloat(t.cust_id);
									  //       alert(antigen);
									  	swal({
							                title: " ", 
							                html: true,
							                text: 'Do you want to reserve table/room now?',  
							                // confirmButtonText: "V redu", 
							                allowOutsideClick: "true" ,
							                showDenyButton: true,
							                showCancelButton: true,
							                confirmButtonText: `Yes`,
							                cancelButtonText: `No`,
							            }, function (result) {
							              // Your code
							              if (result) {
							              	var no_btn = 0;
							              	var formData = 'cust_id='+cust_id+'&time_schedule='+time_schedule+'&date_schedule='+date_schedule+'&no_btn='+no_btn;
							              	$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
											window.location = baseUrl+'cashier/tables_reservation/reservation';
											},'json');
										// });
											} else{
												var no_btn = 1;
												var formData = 'cust_id='+cust_id+'&time_schedule='+time_schedule+'&date_schedule='+date_schedule+'&no_btn='+no_btn;
												$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
												window.location = baseUrl+'cashier';
												},'json');
											}
        								});

										// },'json');
									}
				});
                // alert('haha');
                // return false;
                // swal('Saved!', '', 'success')
    //           } else{
    //             $('#rdeposit-form').rOkay({
				// btn_load 		: 	$('#submit-btn'),
				// bnt_load_remove : 	false,
				// asJson 			: 	false,
				// onComplete 		: 	function(data){
				// 						var date_schedule = $('#date_schedule').val();
				// 						var time_schedule = $('#time_schedule').val();
				// 						var cust_id = $('#cust_id').val();
				// 						var no_btn = 1;
				// 						// alert(cust_id);
				// 						// return false;
				// 						// var type = $('#trans_type').val();
				// 						var formData = 'cust_id='+cust_id+'&time_schedule='+time_schedule+'&date_schedule='+date_schedule+'&no_btn='+no_btn;
				// 						$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
				// 						// location.reload();
				// 							// alert(data);
				// 							// var t = JSON.parse(data);
				// 					        // var antigen = parseFloat(t.cust_id);
				// 					        // alert(antigen);
				// 					        // return false;
				// 							window.location = baseUrl+'cashier';
				// 						// });
				// 						},'json');
				// 					}
				// });
    //           }
    //         });
			
			return false;
		});   	
		function show_search(){
			var txt = $('#search-customer').val();
			var ul = $('#cust-search-list');
			ul.find('li').remove();
			ul.goLoad();
			$.post(baseUrl+'cashier/search_customers/'+txt,function(data){
				if(!$.isEmptyObject(data)){
					$.each(data,function(cust_id,val){
						var li = $('<li/>')
									.attr({'class':'cust-row','id':'cust-row-'+cust_id})
									.css({'cursor':'pointer','border-bottom':'1px solid #ddd'})
									.click(function(){
										var li = $(this);
										$.post(baseUrl+'cashier/get_customers/'+cust_id,function(cust){
											$.each(cust,function(key,val){
												var name = val.fname+' '+val.mname+' '+val.lname+' '+val.suffix
												$('#full_name').val(name);
												$('#cust_id').val(val.cust_id);
												$('#contact_no').val(val.phone);
												$('#email').val(val.email);
												selDeSel(li);
											});
										},'json');
									});
						$('<h4/>').css({'font-size':'14px','padding':'3px','margin':'3px'}).html(val.name).appendTo(li);
						$('<h4/>').css({'font-size':'12px','padding':'3px','margin':'3px'}).html(val.email).appendTo(li);
						li.appendTo(ul);
					});
					// $('.listings').perfectScrollbar('update');
				}
				ul.goLoad({load:false});
			},'json');
		}
		function selDeSel(li){
			var par = li.parent();
			par.find('li').removeClass('selected');
			li.addClass('selected');
		}	
		disEnCard();
		$('#amount_type').change(function(){
			disEnCard();			
		});
		function disEnCard(){
			var type = $('#amount_type').val();
			if(type == 'cash'){
				// $('.for-cards').attr('readOnly','readOnly');
				$('.for-cards').parent().hide();
				$('.for-cards').removeClass('rOkay');
			}
			else{
				$('.for-cards').parent().show();
				$('.for-cards').addClass('rOkay');
				// $('.for-cards').removeAttr('readOnly');
			}
			$('.for-cards').val('');
		}	
	<?php endif; ?>
});
</script>