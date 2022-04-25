<script>
$(document).ready(function(){
	<?php if($use_js == 'managerJs'): ?>
		var fullname = $('#user_fullname').val();
		$(document).on("click", "#end_shift_g", function(){
			window.location.replace(baseUrl+"guide/guide_cash_count");
		});
		$(document).on("click", "#end_day_g", function(){
			window.location.replace(baseUrl+"guide/guide_end_day");
		});
		$.rPopForm({
			loadUrl : 'guide/guide_form',
			passTo	: '',
			title	: '<center>Hello '+fullname+',</br>What task would you like me to do for you?</center>',
			noButton: 1,
			// rform 	: 'guide_form',
			onComplete : function(){
							// goTo('menu/subcategories');
						 }
		});
		$(document).on("click", "#m-cash-dept", function(){
			$("#deposit-btn").click();
		});
		$(document).on("click", "#m-withdraw", function(){
			$("#withdraw-btn").click();
		});
		$(document).on("click", "#m-reports", function(){
			$("#printings-btn").click();
		});
		$(document).on("click", "#m-textfile", function(){
			$("#mall-btn").click();
		});
		$('#cash-drawer-btn').attr('class','btn-block manager-btn-orange double');
		var this_url = baseUrl+'drawer';
		$('.manager-content').rLoad({url:this_url});
		$('#cash-drawer-btn').click(function(){

			$('#cash-drawer-btn').attr('class','btn-block manager-btn-orange double');
			$('#end-of-day-btn').attr('class','btn-block manager-btn-red double');
			$('#printings-btn').attr('class','btn-block manager-btn-red double');

			var this_url = baseUrl+'drawer';
			$('.manager-content').rLoad({url:this_url});
			return false;
		});
		$('#end-of-day-btn').click(function(){
			$('#cash-drawer-btn').attr('class','btn-block manager-btn-red double');
			$('#end-of-day-btn').attr('class','btn-block manager-btn-orange double');
			$('#printings-btn').attr('class','btn-block manager-btn-red double');
			// var this_url = baseUrl+'manager/manager_end_of_day';
			// $.post(this_url, {}, function(data){
			// 	$('.manager-content').html(data);
			// });
			$('.manager-content').rLoad({url:baseUrl+'endofday'});
			return false;
		});
		$('#mall-btn').click(function(){
			var mall = $(this).attr('mall');
			console.log(baseUrl+mall);
			$('.manager-content').rLoad({url:baseUrl+mall});
			return false;
		});
		$('#report-btn').click(function(event)
		{
			$('.manager-content').rLoad({url:baseUrl+'reports'});
			event.preventDefault();
			// $.ajax({url:baseUrl+'manager/manager_reports'}).done(function(data){$('.manager-content').html(data);});
			// $.post(baseUrl+'manager/manager_reports',{},function(data)
			// {
			// 	alert(data);
			// });
		});
		$('#printings-btn').click(function(){
			$('#cash-drawer-btn').attr('class','btn-block manager-btn-red double');
			$('#end-of-day-btn').attr('class','btn-block manager-btn-red double');
			$('#printings-btn').attr('class','btn-block manager-btn-orange double');
			// var this_url = baseUrl+'manager/manager_end_of_day';
			// $.post(this_url, {}, function(data){
			// 	$('.manager-content').html(data);
			// });
			$('.manager-content').rLoad({url:baseUrl+'prints'});
			return false;
		});
		$('#order-btn').click(function(){
			// alert('Order');
			var this_url = baseUrl+'manager/manager_orders';

			$.post(this_url, {}, function(data){
				$('.manager-content').html(data);
			});
			return false;
		});

		$('#system-btn').click(function(){
			var this_url = baseUrl+'manager/manager_settings';

			$.post(this_url, {}, function(data){
				$('.manager-content').html(data);
			});
			return false;
		});

		$('#exit-btn').click(function(){
			// window.location = baseUrl+'cashier';
			window.location = baseUrl+'manager/go_logout';
			return false;
		});

	<?php elseif($use_js == 'managerReasonsJs'): ?>
		$('.reason-btns').click(function(){
			var reason = $(this).text();
			$('.reason-btns').attr('style','background-color:#D12027 !important;');
			$(this).attr('style','background-color:#d45500 !important;');
			$('#pop-reason').val(reason);
			return false;
		});
	<?php elseif($use_js == 'managerLoginJs'): ?>
		$('#pin-login').on('click',function(event)
		{
			event.preventDefault();

			var hashTag = window.location.hash;

			var formData = 'pin='+$('#pin').val();
			$.post(baseUrl+'manager/go_login',formData,function(data)
			{
				if (typeof data.error_msg === 'undefined')
					location.reload();
				else
					rMsg(data.error_msg,'error');
			},'json');
		});
		$('#cancel-btn').on('click',function(event)
		{
			event.preventDefault();
			window.history.back();
		});
	<?php elseif($use_js == 'endofdayJS'): ?>
		$('#day-report-btn').click(function(event)
		{
			event.preventDefault();
			$('#manager-content').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-4x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'manager/manager_end_of_day_report';
			$.post(this_url, {}, function(data){
				$('#manager-content').html(data);
			});
		});
		$('#day-xread-btn').click(function(event){
			event.preventDefault();
			$('#manager-content').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-4x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'manager/manager_xread';

			$.post(this_url, {}, function(data){
				$('#manager-content').html(data);
			});
		});
		$('#day-zread-btn').click(function(event){
			$('#manager-content').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-4x fa-fw fa-spin aw"></i></div></center>');
			var this_url = baseUrl+'manager/manager_zread';

			$.post(this_url, {}, function(data){
				$('#manager-content').html(data);
			});
		});
		$('#day-report-btn').click();
	<?php elseif($use_js == 'endofdayReportJS'): ?>
		$('#print-btn').click(function(event){
			$.post(baseUrl+'manager/print_endofday_receipt','',function(data)
			{
				rMsg(data.msg,'success');
			},'json');
			event.preventDefault();
		});
		$('.div-report-txt').perfectScrollbar({suppressScrollX:true});
	<?php elseif($use_js == 'xreadJS'): ?>
		$('#print-btn').click(function(event){
			event.preventDefault();

			bootbox.confirm('<h4>You are about to end this shift schedule (X-Read). Would you like to proceed?</h4>',function(result)
			{
				if (result) {
					$.post(baseUrl+'cashier/print_xread',{},function(data)
					{
						setTimeout(function(){
							window.location = baseUrl+'manager/go_logout/clock';
						},800);
					},'json');
				}
			});
		});

		$.post(baseUrl+'cashier/show_xread',{},function(data)
		{
			$('#read-txt').html(data.txt);
		},'json');

		$.post(baseUrl+'manager/check_xread_okay',{},function(data)
		{
			if (typeof data.error != 'undefined') {
				$('#print-btn').attr('disabled','disabled');
				rMsg(data.error,'error');
			}
		},'json');

		$('.div-report-txt').perfectScrollbar({suppressScrollX:true});
	<?php elseif($use_js == 'zreadJS'): ?>
		// $('#print-btn').click(function(event){
		// 	event.preventDefault();

		// 	bootbox.confirm('<h4>You are about to run end of day report (Z-Read). Would you like to proceed?</h4>',function(result)
		// 	{
		// 		if (result) {
		// 			$.post(baseUrl+'cashier/print_zread',{},function(data)
		// 			{
		// 				if (typeof data.error_msg === 'undefined') {
		// 					rMsg(data.msg,'success');
		// 					setTimeout(function(){
		// 						window.location = baseUrl+'site/go_logout';
		// 					},800);
		// 				}
		// 				else
		// 					rMsg(data.error_msg,'error');
		// 			},'json');
		// 		}
		// 	});

		// });
		$('#print-btn').click(function(event){
			var btn = $('#print-btn');
			btn.goLoad();
			$.post(baseUrl+'cashier/print_zread',{},function(data)
			{
				if (typeof data.error_msg === 'undefined') {
					rMsg(data.msg,'success');
					location.reload();
					// setTimeout(function(){
					// 	window.location = baseUrl+'site/go_logout';
					// },800);
				}
				else
					rMsg(data.error_msg,'error');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
			return false;
		});

		$.post(baseUrl+'cashier/show_zread',{},function(data){
			$('#read-txt').html(data.txt);
		},'json');
		// alert(data);
		// });

		$.post(baseUrl+'cashier/check_zread_okay',{},function(data)
		{
			// alert(data);
			if (data.error != "") {
				$('#print-btn').attr('disabled','disabled');
				rMsg(data.error,'error');
			}
		},'json');
		// });
		// 	console.log(data);
		// });

		$('.div-report-txt').perfectScrollbar({suppressScrollX:true});
	<?php elseif($use_js == 'systemJS'): ?>
		loadDivs('settings');
		$('#settings-div').rLoad({url:baseUrl+'manager/system_settings'});
		$('#settings-btn').click(function(){
			loadDivs('settings');
			$('#settings-div').rLoad({url:baseUrl+'manager/system_settings'});
			// var this_url = baseUrl+'manager/system_settings';
			// $('#settings-div').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
			// $.post(this_url, {}, function(data){
			// 	$('#settings-div').html(data);
			// });
			return false;
		});
		function loadDivs(type){
			$('.loads-div').hide();
			$('#'+type+'-div').show();
		}
	<?php elseif($use_js == 'systemSettingsJS'): ?>
		$('#save-btn').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-btn'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										rMsg(data.msg,'success');
									}
			});
			return false;
		});
		$('#branch_code,#branch_desc,#tin,#bir,#branch_name,#address,#machine_no,#email,#contact_no,#delivery_no,#permit_no,#serial,#website')
        .keyboard({
            alwaysOpen: false,
            usePreview: false
        });


   <?php elseif($use_js == 'managerOrdersJS'): ?>
		// alert('Manager Orders');
		var scrolled=0;
		var transScroll=0;
		$('.manager-orders-list').perfectScrollbar({suppressScrollX: true});
		$('.manager-orders-main-btns').show();
		$('.manager-orders-close-trx').hide();

		function loadOrders(terminal, status,types){
			// alert(terminal+'---'+status+'---'+types);
			$('.manager-orders-list').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
			// $.post(baseUrl+'cashier/manager_view_orders/'+terminal+'/open/'+types+'/null/combineList',function(data){
			$.post(baseUrl+'cashier/manager_view_orders/'+terminal+'/'+status+'/'+types+'/null/combineList',function(data){
				// alert(baseUrl+'cashier/manager_view_orders/'+terminal+'/'+status+'/'+types+'/null/combineList'+'-----'+data);
				$('.manager-orders-list').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						addDelFunc(id,val);

					});
					$('.manager-orders-list').perfectScrollbar('update');
				}
			},'json');
			// });
		}

		function addDelFunc(num){
			$('#order-btnish-'+num).click(function(){
				// alert(num);
				$('.sel-row').removeClass('selected');
				$(this).addClass('selected');
			});
		}

		// //working
		function loadTrx(sales_id){
			// var id = $('#settle').attr('sales');
			// alert('Load trx:'+sales_id);

			$.post(baseUrl+'cashier/manager_settle_transactions/'+sales_id,function(data){
				// alert('dsadasd-----'+data);
				$('.manager-orders-menu').hide();
				$('.manager-orders-main-btns').hide();
				$('.manager-orders-close-trx').show();

				$('.manager-orders-list').html(data.code);
				$('.manager-orders-list').perfectScrollbar({suppressScrollX: true});
				$.each(data.ids,function(key,pay_id){
					// deletePayment(pay_id,id);
				});
			},'json');
			// });
		}

		$('#close-trx-btn').click(function(){
			$('.manager-orders-menu').show();
			$('.manager-orders-main-btns').show();
			$('.manager-orders-close-trx').hide();
			return false;
		});

		// function loadTrx(sales_id){
			// // var id = $('#settle').attr('sales');
			// // alert('Load trx:'+sales_id);

			// $.post(baseUrl+'cashier/manager_settle_transactions/'+sales_id,function(data){
				// // alert('dsadasd-----'+data);
				// $('.manager-orders-btns').html(data.code);
				// $('.manager-orders-btns').perfectScrollbar({suppressScrollX: true});
				// $.each(data.ids,function(key,pay_id){
					// // deletePayment(pay_id,id);
				// });
			// },'json');
			// // });
		// }

		loadOrders('my','open','all');

		$('.my-all-btns').click(function(){
			var terminal = $(this).attr('ref');
			var status = $(this).attr('status');
			var types = $('.manager-orders-list').attr('types');
			$('.manager-orders-list').attr('terminal',terminal);
			$('#clickedmenu').val(terminal);
			loadOrders(terminal,status,types);
			return false;
		});

		$('#recall-btn').click(function(){
			var sel = $('.selected');
			var ref = sel.attr('ref');
			var type = sel.attr('type');
			// alert(ref+'-----'+type);
			window.location = baseUrl+'cashier/counter/'+type+'/'+ref;
			return false;
		});

		$('#view-trx-btn').click(function(){
			// alert('View Transactions');
			var sel = $('.selected');
			var ref_id = sel.attr('ref');
			var type = sel.attr('type');
			// alert(ref_id+'-----'+type);
			loadTrx(ref_id);
			return false;
		});

		$('#print-selected-btn').click(function(){
			var sel = $('.selected');
			var ref_id = sel.attr('ref');
			var type = sel.attr('type');
			// alert(ref_id+'-----'+type);

			$.post(baseUrl+'cashier/print_sales_receipt/'+ref_id,'',function(data)
			{
				rMsg(data.msg,'success');
			},'json');
			return false;
		});

		$('#print-all-btn').click(function(){
			var terminal = $('#clickedmenu').val();
			var types = $('.manager-orders-list').attr('types');
			// alert('Print all: '+terminal+'---'+types);
			$.post(baseUrl+'cashier/manager_print_all_receipts/'+terminal+'/open/'+types+'/null/combineList/true',function(data){
				// alert(data);
				// console.log(data);
				rMsg(data.msg,'success');
			},'json');

			return false;
		});

	<?php elseif ($use_js == "managerReportsJs"): ?>
		$('.btn-manager-report').on('click',function(event){
			event.preventDefault();
			var id = $(this).attr('id');
			$('#report-view-div').html('');
			$('#report-form-div').html('<i class="fa fa-4x fa-spinner fa-spin"></i>');
			$.ajax({
					url:baseUrl+'manager/manager_report_form/'+id,
					dataType:'json'
				})
			 	.done(function(data){

			 		$('#report-form-div').html(data.code);
					$('.datepicker').datepicker({format:'yyyy-mm-dd'});
			 		// $('.daterangepicker').daterangepicker();
			 		$('.daterangepicker').each(function(index)
			 		{
			 			if ($(this).hasClass('datetimepicker')) {
			 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
			 			} else {
			 				$(this).daterangepicker({separator: ' to '});
			 			}
			 		});
			 		// $('.timepicker').timepicker({showInputs:false});
			 		$('#run-rep-btn').click(function(e){
			 			// return false;
						$('#report-view-div').html('<i class="fa fa-4x fa-spinner fa-spin"></i>');
						var goPassTo ='manager/manager_report_view/'+id+'/true';
						if(id == 'system-sales'){
							goPassTo = 'manager/print_system_sales_report/'+id+'/true';
						}
						else if(id == 'menu-sales'){
							goPassTo = 'manager/print_menu_sales_report/'+id+'/true';
						}

						var formData = $('#report_form').serialize();
						$("#report_form").rOkay({
							btn_load		: 	$('#run-rep-btn'),
							// btn_load		: 	$('.report-submit-btn'),
							passTo			: 	goPassTo,
							bnt_load_remove	: 	true,
							asJson			: 	true,
							onComplete		:	function(data){
													console.log(goPassTo);
													// alert(data);
													$('#report-view-div').html(data.code);
													$('.report-submit-btn').removeAttr('disabled');
													$('#report-view-div').perfectScrollbar({suppressScrollX: true});
													scroller();
												}
						});
			 			return false;
					});
					//PRINT DITO CLICK
					$('#gen-rep-btn').click(function(e){
						// $('#report-view-div').html('<i class="fa fa-4x fa-spinner fa-spin"></i>');
						// var passTo	= 'manager/manager_report_view/'+id;
						// var noError = $("#report_form").rOkay({
						// 	btn_load		: 	$('#gen-rep'),
						// 	bnt_load_remove	: 	true,
						// 	goSubmit		: 	false
						// });
						// if(noError){
						// 	var get = $('#report_form').serialize();
						// 	// alert(get);
						// 	passTo +=  '?'+get;
						// 	// alert(baseUrl+passTo);
						// 	window.location = baseUrl+passTo;

						// }
						// $('#report-view-div').html('<i class="fa fa-4x fa-spinner fa-spin"></i>');
						var goPassTo ='manager/manager_report_view/'+id;
						if(id == 'system-sales'){
							goPassTo = 'manager/print_system_sales_report/'+id;
						}
						else if(id == 'menu-sales'){
							goPassTo = 'manager/print_menu_sales_report/'+id;
						}

			 			$("#report_form").rOkay({
							// btn_load		: 	$('#run-rep-btn'),
							btn_load		: 	$('#gen-rep-btn'),
							passTo			: 	goPassTo,
							bnt_load_remove	: 	true,
							asJson			: 	false,
							onComplete		:	function(data){
													// console.log('manager/manager_report_view/'+id+'/true');
													// alert(data);
													// console.log(data);
													// $('#report-view-div').html(data.code);
													// $('#view-scrollbar-div').perfectScrollbar();
													// $('#gen-rep-btn').removeAttr('disabled');
													$('.report-submit-btn').removeAttr('disabled');
													// rMsg(data.msg,'success');
													// alert(data)	;
												}
						});
						return false;
					});

				});
			});
		function scroller(){
			var scrolled = 0;
			$("#order-scroll-down-btn").on("click" ,function(){
			    // var scrollH = parseFloat($("#report-view-div")[0].scrollHeight) - 150;
			    // if(scrolled < scrollH){

				    scrolled=scrolled+150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					});
			    // }
			});
			$("#order-scroll-up-btn").on("click" ,function(){
				if(scrolled > 0){
					scrolled=scrolled-150;
					$("#report-view-div").animate({
					        scrollTop:  scrolled
					});
				}
			});
			$("#report-view-div").bind("mousewheel",function(ev, delta) {
			    var scrollTop = $(this).scrollTop();
			    $(this).scrollTop(scrollTop-Math.round(delta));
			    scrolled=scrollTop-Math.round(delta);
			});
		}
     <?php elseif($use_js == 'managerFreeReasonsJs'): ?>
		$('.reason-btns').click(function(){
			var reason = $(this).text();
			$('.reason-btns').attr('style','background-color:#D12027 !important;');
			$(this).attr('style','background-color:#d45500 !important;');
			$('#pop-reason').val(reason);
			return false;
		});

		$('#other-reason-txt')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
            autoAccept : true
        });

		// $('#run-rep').click(function(){
		// 	alert('zzzzzzzzzzzzz');
		// 	// $("#report_form").rOkay({
		// 	// 	btn_load		: 	$('#run-rep'),
		// 	// 	bnt_load_remove	: 	true,
		// 	// 	asJson			: 	true,
		// 	// 	onComplete		:	function(data){
		// 	// 							// alert(data);
		// 	// 							rMsg(data.msg,'success');
		// 	// 						}
		// 	// });
		// 	return false;
		// });

		// $('.scrollbar-div').perfectScrollbar({suppressScrollX: true});

		<?php elseif($use_js == 'discountReasonsJs'): ?>
			$('.reason-btns').click(function(){
				var reason = $(this).text();
				var code = $(this).attr('code');
				// alert(code);
				$('.reason-btns').attr('style','background-color:#D12027 !important;');
				$(this).attr('style','background-color:#d45500 !important;');
				$('#pop-reason').val(reason);
				$('#pop-code').val(code);
				return false;
			});

	<?php endif; ?>
});
</script>