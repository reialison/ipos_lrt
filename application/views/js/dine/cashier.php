<script>
$(document).ready(function(){
	<?php if($use_js == 'controlPanelJs'): ?>
		var docH = $('body').height();
		var colH = docH/2-160;
		$('#cashier-panel').height(605);
		$('.cpanel-top').height(460);
		$('.orders-lists').height(colH-150);
		$('#orders-search').height(colH-150);
		$('.orders-lists-load').height(colH-150);

		var scrolled=0;
		$('#sample').keyboard({ layout: 'qwerty' });
		startTime();
		terminal = $('#terminal-btn').attr('type');
		// status = $('#status-btn').attr('type');
		status = 'open';
		if($('#types-btn').exists())
    		types = $('#types-btn').attr('type');
    	else
    		types = 'all';
		now = $('#now-btn').attr('type');
		search_id = 'none';
		server_id = '0';
		search_val = '';
		loadOrders(terminal,status,types,now,search_id,server_id);
		$('#manager-btn').click(function(){
			$.callManager({
				success : function(){
					window.location = baseUrl+'manager';
				}
			});
			return false;
		});
		// $('#gift-card-btn').click(function(){
		// 	window.location = baseUrl+'gift_cards/cashier_gift_cards';
		// 	return false;
		// });

		loadtransType(1);
		loadBrand();

		function loadBrand(){

			$('#load-img').html('');
			// page = $('#page_value').val();
			// page = $('#page_value').val();
			// formData = 'brand='+brand;
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/load_brands',function(data){
		 		// alert(data.error);return false;
		 		// alert(data);
		 		// alert(data.brand_img);
				var sCol = $('#load-img');	
				// $('<img src='+baseUrl+'uploads/brands/'+data+'.jpg width="100%" height="55px;">').appendTo(sCol);
				$('<img src='+baseUrl+data.brand_img+' width="100%" height="50px;">').appendTo(sCol);
	 		},'json');
		 	// },'json');
				

		}


		function loadtransType(page=1){
			// page = $('#page_value').val();
			// page = $('#page_value').val();
			formData = 'page='+page;
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_trans_type',formData,function(data){
		 		// alert(data.error);return false;
		 		if(data.error == ''){
		 			showTransType(data.buttons,1);
		 			$('#page_value').val(page);
		 		}
		 		// alert(data);
	 		// });
		 	},'json');	

		}

		function loadtransTypeSearch(val){

			// page = $('#page_value').val();
			// page = $('#page_value').val();
			formData = 'val='+val;
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_trans_type_search',formData,function(data){
		 		// alert(data);
		 		// alert(data.error);return false;
		 		if(data.error == ''){
		 			showTransType(data.buttons,1);
		 			$('#page_value').val(page);
		 		}
		 		// alert(data);
	 		// });
		 	},'json');
				

		}
		function showTransType(data,ctr){	
			$('#transaction-div').html('');

			// $('.menus-div .items-lists').html('');
			var div = $('#transaction-div');
			
 
			$.each(data,function(key,val){
				
				var sCol = $('<div class="col-md-5 media-margin-trans-type"></div>');
				// var sCol = $('<div class="col-md-5" style="border-right-style:groove"></div>');


	 			$('<button/>')
	 			.attr({'id':val['text_id']+'-btn','ref':val['text_id'],'class':'new-order-btns btn-block media-order-btns cpanel-btn-blue '+val['new_trans_type'],'style':''})
	 			.text(val['text'])
	 			// .appendTo('.menu-cat-container')
	 			.appendTo(sCol);
	 			// .click(function(){
					// 	var mods_mandatory = $('.mods-mandatory').val();
					// 	// alert(mods_mandatory);
					// 	if(mods_mandatory >= "1"){
					// 		var mod_name = $('.mod-group-name').val();
					// 		rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
					// 		return false;
					// 	}


	 			// 	$('#search-menu').val('');
	 			// 	// loadsDiv('subcategory',cat_id,val,null);
	 			// 	loadsDiv('menus',cat_id,val,null);
	 			// 	return false;
	 			// });
	 			sCol.appendTo(div);

				// ctr++;
			});


			// functions button
 			$('#dine-in-btn').click(function(){
 				formData = 'txt=dinein';
       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
       				// alert(data);
					// window.location = baseUrl+'cashier/counter/'+data;
					window.location = baseUrl+'cashier/tables/'+data;
       			});
				return false;
			});
			// $('#retail-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/retail'+'#retail';
			// 	return false;
			// });
			$('#delivery-btn').click(function(){
				window.location = baseUrl+'cashier/delivery';
				return false;
			});
			$('#pickup-btn').click(function(){
				
				window.location = baseUrl+'cashier/pickup';
				return false;
			});
			// $('#counter-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/counter';
			// 	return false;
			// });
			// $('#drive-thru-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/drivethru';
			// 	return false;
			// });
			$('#mcb-btn').click(function(){
				formData = 'txt=mcb';
       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
       				// alert(data);
					// window.location = baseUrl+'cashier/counter/mcb';
					window.location = baseUrl+'cashier/counter/'+data;
       			});
				return false;
			});
			$('#takeout-btn').click(function(){

				// formData = 'type=takeout';
		  //      	$.post(baseUrl+'cashier/check_tables',formData,function(data){
		  //      		// alert(data);
		  //      		// console.log(data);
		  //      		if(data.status){
				// 		window.location = baseUrl+'cashier/tables_other/takeout';
		  //      		}
		  //      		else{
		       			// rMsg(data.error,'error');
		       			
		       				// $type = 'takeout';
		       				// $encrypt = urlencode($this->encrypt->encode($type));
		       			formData = 'txt=takeout';
		       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
		       				// alert(data);
							window.location = baseUrl+'cashier/counter/'+data;
		       			});
		       	// 	}
		       	// },'json');	
				return false;
			});

			// //panda order
			// $('#foodpanda-btn').click(function(){
			// 	formData = 'type=foodpanda';
		 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
		 //       		// alert(data);
		 //       		// console.log(data);
		 //       		if(data.status){
			// 			window.location = baseUrl+'cashier/tables_other/foodpanda';
		 //       		}
		 //       		else{
		 //       			// rMsg(data.error,'error');
			// 			window.location = baseUrl+'cashier/counter/foodpanda';
		 //       		}
		 //       	},'json');	
			// 	return false;
			// });
			// //lala food
			// $('#lalafood-btn').click(function(){
			// 	// alert('aw');
			// 	window.location = baseUrl+'cashier/counter/lalafood';
			// 	return false;
			// });

			// //eatigo
			// $('#eatigo-btn').click(function(){
			// 	window.location = baseUrl+'cashier/tables/eatigo';
			// 	return false;
			// });

			// //bigdish
			// $('#bigdish-btn').click(function(){
	 	// 		window.location = baseUrl+'cashier/tables/bigdish';
			// 	return false;
			// });

			// //honestbee
			// $('#honestbee-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/honestbee';
			// 	return false;
			// });

			// //zomato
			// $('#zomato-btn').click(function(){
			// 	window.location = baseUrl+'cashier/tables/zomato';
			// 	return false;
			// });

			// //picc
			// $('#picc-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/picc';
			// 	return false;
			// });

			// //amorsolo
			// $('#amorsolo-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/amorsolo';
			// 	return false;
			// });

			// //cash bar
			// $('#cashbar-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/cashbar';
			// 	return false;
			// });

			// //menu go
			// $('#menugo-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/menugo';
			// 	return false;
			// });

			// //zomato delivery
			// $('#zomato-delivery-btn').click(function(){
			// 	window.location = baseUrl+'cashier/counter/zomato-delivery';
			// 	return false;
			// });

			// $('#grabfood-btn').click(function(){
			// 	formData = 'type=grabfood';
		 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
		 //       		// alert(data);
		 //       		// console.log(data);
		 //       		if(data.status){
			// 			window.location = baseUrl+'cashier/tables_other/grabfood';
		 //       		}
		 //       		else{
		 //       			// rMsg(data.error,'error');
			// 			window.location = baseUrl+'cashier/counter/grabfood';
		 //       		}
		 //       	},'json');	
			// 	return false;
			// });

			// $('#pickaroo-btn').click(function(){
			// 	formData = 'type=pickaroo';
		 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
		 //       		// alert(data);
		 //       		// console.log(data);
		 //       		if(data.status){
			// 			window.location = baseUrl+'cashier/tables_other/pickaroo';
		 //       		}
		 //       		else{
		 //       			// rMsg(data.error,'error');
			// 			window.location = baseUrl+'cashier/counter/pickaroo';
		 //       		}
		 //       	},'json');	
			// 	return false;
			// });

			// $('#ecom-btn').click(function(){
			// 	formData = 'type=ecom';
		 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
		 //       		// alert(data);
		 //       		// console.log(data);
		 //       		if(data.status){
			// 			window.location = baseUrl+'cashier/tables_other/ecom';
		 //       		}
		 //       		else{
		 //       			// rMsg(data.error,'error');
			// 			window.location = baseUrl+'cashier/counter/ecom';
		 //       		}
		 //       	},'json');	
			// 	return false;
			// });
			$('#reservation-btn').click(function(){
				window.location = baseUrl+'cashier/tables/reservation';
				return false;
			});

			$('.new_trans_type').click(function(){
	            ref = $(this).attr('ref');
	            if(ref == 'delivery'){
            		window.location = baseUrl+'cashier/delivery';
	            }else if(ref == 'pickup'){
	            	window.location = baseUrl+'cashier/pickup';
	            }else{
	            	formData = 'txt='+ref;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
	       				// alert(data);
	            		// window.location = baseUrl+'cashier/counter/'+ref;
	            		window.location = baseUrl+'cashier/counter/'+data;
	       			});
	            }
	            return false;
	        });

		}

		$('#tnext-btn').click(function(){
			page = $('#page_value').val();
			page = Number(page) + 1;
			// $('#page_value').val(page);
			// alert(page);
			loadtransType(page);
		});

		$('#tprev-btn').click(function(){
			page = $('#page_value').val();
			// if(page != 1){
				page = Number(page) - 1;
				// $('#page_value').val(page)
				// alert(page);
				loadtransType(page);
			// }
		});


		$('#search-trans').on('keyup',function(){
    		// $('#page_value').val(1);
			val = $(this).val();

			// $('#search-trans-div').html('');
			// $('.pn-btn').hide();
			
			if(val == ""){
				loadtransType(1);
				$('.pn-btn').show();
			}else{
			// 	$('.new-order-btns').hide();
				// alert(val);
				loadtransTypeSearch(val);
			}

    	});

    	$('#search-trans')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
			autoAccept : true,
			change   : function(e, keyboard, el) { 
				// alert(1);
				// $('#search-menu').trigger('keyup');
				val = $('input[name="search-trans"]').val();

				// $('#search-trans-div').html('');
				// $('.pn-btn').hide();
				if(val == ""){
					loadtransType(1);
					$('.pn-btn').show();
				}else{
				// 	$('.new-order-btns').hide();
					// alert(val);
					loadtransTypeSearch(val);
				}
				
				return false;
			},

        })
        .addNavigation({
            position   : [0,0],
            toggleMode : false,
            focusClass : 'hasFocus'
        });

    	$('#selbrand-btn').click(function(){

			$.rPopForm({
				loadUrl : 'cashier/select_brands',
				passTo	: 'cashier/update_brand_sess',
				// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
				title	: '<font size="4">Select Brand</font>',
				noButton: 1,
				// wide	: 1,
				hide	: 1,
				rform 	: 'brand-form',
				onComplete : function(data){
					// alert(data);
					// $('.brandname-div').html('');
					// $('.bname').text(data);
					// loadMenuCategories();
					// sCol.html('asfasfsafsadfasdf');
					loadBrand();

				}
			});
		});



		$(document).on('click','.brand-btns',function(){
        	$('.brand-btns').attr('class','brand-btns btn-block cpanel-btn-orange btn btn-success');
        	brand = $(this).attr('brand');

        	// alert(brand);
        	$('#hid-brand').val(brand);

        	$(this).attr('class','brand-btns btn-block cpanel-btn-orange btn btn-danger');

        	var formData = $('#brand-form').serialize();
        	$.post(baseUrl+'cashier/update_brand_sess',formData,function(data){
				loadBrand();

				$('.bname').text(data);

				$('.close').click();
			});
        	
        });


		$('#pr-btn').click(function(){
			page = $('#page_trans_value').val();
			page = Number(page) - 1;

			if(page != 0){
				// $('#page_value').val(page);
				// alert(page);
				
				terminal = $('#terminal-btn').attr('type');
				// status = $('#status-btn').attr('type');
				status = $('#status_hid').val();
				// if($('#types-btn').exists())
			  //   		types = $('#types-btn').attr('type');
			  //   	else
	    		types = 'all';
				now = $('#now-btn').attr('type');
				search_id = 'none';
				server_id = '0';
				search_val = '';
				loadOrders(terminal,status,types,now,search_id,server_id,'',page);
				$('#page_trans_value').val(page);
			}



		});

		$('#nxt-btn').click(function(){
			page = $('#page_trans_value').val();
			page = Number(page) + 1;
			// $('#page_value').val(page);
			// alert(page);
			terminal = $('#terminal-btn').attr('type');
			// status = $('#status-btn').attr('type');
			status = $('#status_hid').val();
			// if($('#types-btn').exists())
	  //   		types = $('#types-btn').attr('type');
	  //   	else
    		types = 'all';
			now = $('#now-btn').attr('type');

			// alert(status);
			search_id = 'none';
			server_id = '0';
			search_val = '';

			loadOrders(terminal,status,types,now,search_id,server_id,'',page);

			$('#page_trans_value').val(page);
		});



		$('#gift-card-btn').click(function(){
			// window.location = baseUrl+'cashier_gift_card/counter/giftcheque';
			formData = 'txt=giftcheque';
   			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
				window.location = baseUrl+'cashier_gift_card/counter/'+data;
   			});
			return false;
		});
		
		$('#loyalty-card-btn').click(function(){
			window.location = baseUrl+'loyalty';
			return false;
		});
		$('#reserve-btn').click(function(){
			// alert('haha');
			window.location = baseUrl+'cashier/set_schedule';
			return false;
		});
		$('#quick-edit-btn').click(function(){
			$.callQuickEdit({
				success : function(){
					// var ur = baseUrl+'drawer/open_drawer';
					// $.post(ur);
				}
			});
			return false;
		});
		$('#printer-setup-btn').click(function(){
			$.callPrinterSetup({
				success : function(){
					var ur = baseUrl+'drawer/open_drawer';
					$.post(ur);
				}
			});
			return false;
		});
		$('#customer-btn').click(function(){
			// window.location = baseUrl+'customers/cashier_customers';
			window.location = baseUrl+'pos_customers/customer_terminal';
			return false;
		});
		$('#cust-bank-btn').click(function(){
			window.location = baseUrl+'custs_bank';
			return false;
		});
		$('#time-clock-btn').click(function(){
			window.location = baseUrl+'shift';
			// window.location = baseUrl+'clock';
			return false;
		});
		$('#open-drawer-btn').click(function(){
			$.callManager({
				success : function(){
					var ur = baseUrl+'drawer/open_drawer';
					$.post(ur);
				}
			});
			return false;
		});
		$('#restart-printer-button').click(function(event){
			swal({
			  title: "Are you sure you want to restart all printers?",
			  text: "Printing receipts will not work properly while printer connection is restarting.",
			  type: "warning",
			  showCancelButton: true,
			  cancelButtonText: 'No',
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "YES, PROCEED",
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true
			},
			function(){
				// swal('',)
				$.post(baseUrl+'site/restart_printer',{'ajax':true},function(e){
					// console.log(e);
					if(e){
						 swal("Restart printer has been successfully executed!",'','success');
					}

				});
			});
		});
		// $('#dine-in-btn').click(function(){
		// 	window.location = baseUrl+'cashier/tables/dinein';
		// 	return false;
		// });
		// $('#retail-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/retail'+'#retail';
		// 	return false;
		// });
		// $('#delivery-btn').click(function(){
		// 	window.location = baseUrl+'cashier/delivery';
		// 	return false;
		// });
		// $('#pickup-btn').click(function(){
		// 	window.location = baseUrl+'cashier/pickup';
		// 	return false;
		// });
		// $('#counter-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/counter';
		// 	return false;
		// });
		// $('#drive-thru-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/drivethru';
		// 	return false;
		// });
		// $('#mcb-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/mcb';
		// 	return false;
		// });
		// $('#takeout-btn').click(function(){

		// 	formData = 'type=takeout';
	 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
	 //       		// alert(data);
	 //       		// console.log(data);
	 //       		if(data.status){
		// 			window.location = baseUrl+'cashier/tables_other/takeout';
	 //       		}
	 //       		else{
	 //       			// rMsg(data.error,'error');
		// 			window.location = baseUrl+'cashier/counter/takeout';
	 //       		}
	 //       	},'json');	
		// 	return false;
		// });
		$('#back-office-btn').click(function(){
			// window.location = baseUrl;
			window.location = baseUrl+'dashboard';
			return false;
		});
		$('#logout-btn').click(function(){
			window.location = baseUrl+'site/go_logout';
			return false;
		});

		$('#sync-items-btn').click(function(){
			// window.location = baseUrl;
			$("html, body").animate({ scrollTop: 0 }, "slow");

				$.rProgressBar();
				$.post(baseUrl+'site/download_masterfile',{'ajax':true},function(e){
					$.rProgressBarEnd({
						onComplete : function(){
							console.log(e);
							if(e){
								 swal("Download has been successful!",'','success');
							}
		 					//swal("Deleted!", "Your imaginary file has been deleted.", "success");
		 				}

		 			});

				});
		});

		$('#sync-data-btn').click(function(){
			swal({
			  title: "Are you sure you want to consolidate the data to Master DB?",
			  text: "CHANGES ARE IRREVERSIBLE",
			  type: "warning",
			  showCancelButton: true,
			  cancelButtonText: 'No',
			  confirmButtonClass: "btn-danger",
			  confirmButtonText: "YES, PROCEED",
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true
			},
			function(){
				// swal('',)

				// $("html, body").animate({ scrollTop: 0 }, "slow");
				// $.rProgressBar();


				$.post(baseUrl+'site/execute_migration',{'ajax':true},function(e){
					// $.rProgressBarEnd({
					// 	onComplete : function(){
							console.log(e);
							if(e){
								 swal("Consolidation has been successfully executed!",'','success');
							}
			  				// swal("Deleted!", "Your imaginary file has been deleted.", "success");
		  		// 		}

		 			// });

				});
			});

		});

		// });
		$('#cust-inq-btn').click(function(){
			// window.location = baseUrl;
			window.location = baseUrl+'customers/customer_inquiry';
			return false;
		});
		//panda order
		// $('#foodpanda-btn').click(function(){
		// 	formData = 'type=foodpanda';
	 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
	 //       		// alert(data);
	 //       		// console.log(data);
	 //       		if(data.status){
		// 			window.location = baseUrl+'cashier/tables_other/foodpanda';
	 //       		}
	 //       		else{
	 //       			// rMsg(data.error,'error');
		// 			window.location = baseUrl+'cashier/counter/foodpanda';
	 //       		}
	 //       	},'json');	
		// 	return false;
		// });
		// //lala food
		// $('#lalafood-btn').click(function(){
		// 	// alert('aw');
		// 	window.location = baseUrl+'cashier/counter/lalafood';
		// 	return false;
		// });

		// //eatigo
		// $('#eatigo-btn').click(function(){
		// 	window.location = baseUrl+'cashier/tables/eatigo';
		// 	return false;
		// });

		// //bigdish
		// $('#bigdish-btn').click(function(){
 	// 		window.location = baseUrl+'cashier/tables/bigdish';
		// 	return false;
		// });

		// //honestbee
		// $('#honestbee-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/honestbee';
		// 	return false;
		// });

		// //zomato
		// $('#zomato-btn').click(function(){
		// 	window.location = baseUrl+'cashier/tables/zomato';
		// 	return false;
		// });

		// //picc
		// $('#picc-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/picc';
		// 	return false;
		// });

		// //amorsolo
		// $('#amorsolo-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/amorsolo';
		// 	return false;
		// });

		// //cash bar
		// $('#cashbar-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/cashbar';
		// 	return false;
		// });

		// //menu go
		// $('#menugo-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/menugo';
		// 	return false;
		// });

		// //zomato delivery
		// $('#zomato-delivery-btn').click(function(){
		// 	window.location = baseUrl+'cashier/counter/zomato-delivery';
		// 	return false;
		// });

		// $('#grabfood-btn').click(function(){
		// 	formData = 'type=grabfood';
	 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
	 //       		// alert(data);
	 //       		// console.log(data);
	 //       		if(data.status){
		// 			window.location = baseUrl+'cashier/tables_other/grabfood';
	 //       		}
	 //       		else{
	 //       			// rMsg(data.error,'error');
		// 			window.location = baseUrl+'cashier/counter/grabfood';
	 //       		}
	 //       	},'json');	
		// 	return false;
		// });

		// $('#pickaroo-btn').click(function(){
		// 	formData = 'type=pickaroo';
	 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
	 //       		// alert(data);
	 //       		// console.log(data);
	 //       		if(data.status){
		// 			window.location = baseUrl+'cashier/tables_other/pickaroo';
	 //       		}
	 //       		else{
	 //       			// rMsg(data.error,'error');
		// 			window.location = baseUrl+'cashier/counter/pickaroo';
	 //       		}
	 //       	},'json');	
		// 	return false;
		// });

		// $('#ecom-btn').click(function(){
		// 	formData = 'type=ecom';
	 //       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
	 //       		// alert(data);
	 //       		// console.log(data);
	 //       		if(data.status){
		// 			window.location = baseUrl+'cashier/tables_other/ecom';
	 //       		}
	 //       		else{
	 //       			// rMsg(data.error,'error');
		// 			window.location = baseUrl+'cashier/counter/ecom';
	 //       		}
	 //       	},'json');	
		// 	return false;
		// });
		$('#reservation-btn').click(function(){
			window.location = baseUrl+'cashier/tables/reservation';
			return false;
		});

		// $('.new_trans_type').click(function(){
  //           ref = $(this).attr('ref');
  //           window.location = baseUrl+'cashier/counter/'+ref;
  //           return false;
  //       });

		$("#order-scroll-down-btn").on("click" ,function(){
		    var scrollH = parseFloat($(".orders-lists-load")[0].scrollHeight) - 260;
		    if(scrolled < scrollH){
			    scrolled=scrolled+150;
				$(".orders-lists").animate({
				        scrollTop:  scrolled
				});		    	
		    }
		});
		$("#order-scroll-up-btn").on("click" ,function(){
			if(scrolled > 0){
				scrolled=scrolled-150;
				$(".orders-lists").animate({
				        scrollTop:  scrolled
				});				
			}
		});
		$(".orders-lists").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		    scrolled=scrollTop-Math.round(delta);
		});
		$("#refresh-btn").click(function(){
			$('#server-search').hide();
			$('#orders-search').hide();
			terminal = $('#terminal-btn').attr('type');
    		// status = $('#status-btn').attr('type');
    		status = $('#status_hid').val();
    		//types = $('#types-btn').attr('type');
    		if($('#types-btn').exists())
	    		types = $('#types-btn').attr('type');
	    	else
	    		types = 'all';
    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';

    		loadOrders(terminal,status,types,now,search_id,server_id);

			//loadOrders();
			return false;
		});
		$("#recall-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var tbl_id = $('.order-view-list').attr('table_id');
			
			//check if settled na
			$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
				if(data1 == 1){
					rMsg('This transaction has been settled.','error');
				}else{
					//check if may activity yun table
					$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
						if(data.error == ""){
							formData = 'txt='+type;
			       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
			       				// alert(data);
			       				// console.log(data);
								// window.location = baseUrl+'cashier/settle/'+data;
								window.location = baseUrl+'cashier/counter/'+data2+'/'+id+'#'+data2;
			       			});
						}else{
							rMsg(data.error,'error');
						}	
					},'json');
				}

			});

			// alert(table_id);


			return false;
		});
		$("#update-to-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var tbl_id = $('.order-view-list').attr('table_id');


			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					window.location = baseUrl+'cashier/counter/'+type+'/'+id+'/update';
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
				

			// alert(table_id);


			return false;
		});
		$("#split-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.callManager({
				success : function(manager){
					$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
						if(data.error == ""){
							formData = 'txt='+id;
			       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
			       				// alert(data);
			       				// console.log(data);
								window.location = baseUrl+'cashier/split/'+type+'/'+data2;
			       			});
						}else{
							rMsg(data.error,'error');
						}	
					},'json');
				}
			});
			
			return false;
		});
		$("#combine-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.callManager({
				success : function(manager){
					$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
						if(data.error == ""){
							formData = 'txt='+id;
			       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
			       				// alert(data);
			       				// console.log(data);
								window.location = baseUrl+'cashier/combine/'+type+'/'+data2;
			       			});
						}else{
							rMsg(data.error,'error');
						}	
					},'json');
				}
			});
			return false;
		});
		// cashier panel
		$("#void-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var types = $('.order-view-list').attr('type');
			var status = $('.order-view-list').attr('status');
			var tbl_id = $('.order-view-list').attr('table_id');
			var ref_no = $('.order-view-list').attr('ref_no');
			// alert(id);
			// return false;
				//check if may activity yun table
				$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
					if(data.error == ""){
						$.callManager({
							success : function(manager){
								var type = $('.order-view-list').attr('approver',manager.manager_id);
								if(types == 'reservation'){
									// var type = $('#reasons_div').attr('approver',manager.manager_id);
									// loadDivs('reasons');
									$("#reasons_div").modal('show');
									// // var type = $('#reasons_div').attr('approver',manager.manager_id);
									// var approver = $('.order-view-list').attr('approver');
									// // var approver = $('#reasons_div').attr('approver');
									// formData = 'approver='+approver;
									// var box = bootbox.dialog({
									//   message: baseUrl+'cashier/void_reserve_reason_pop',
									//   title: 'Void Reservation',
									//   className: 'manager-call-pop',
									//   buttons: {
									//     submit: {
									//       label: "Submit",
									//       className: "btn btn-guest-submit pop-manage pop-manage-green",
									//       callback: function() {
									//       	// formData += '&add_amount='+$('#add_amount').val()+'&ref_no='+$('#ref_no').val();
									//       	formData += '&reason='+$('#other-reason-txt').val()+'&credit_amount='+$('#credit_amount').val()+'&credit_note_no='+$('#credit_note_no').val();
									//       	// var add_amount = $('#add_amount').val();
									//       	// var ref_no = $('#ref_no').val();
									//       	// formData += '&add_amount='+add_amount+'&ref_no='+ref_no;
									//       	$.post(baseUrl+'cashier/rvoid_order/'+id+'',formData,function(data){
									//       		// console.log(data);
									//       		// return false;
									//       		if(data.error == ""){
									//       			// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
									//       			// });	
									//       			$("#reasons_div").modal('hide');
									// 				$("#pickTimeModal2").modal('hide');
									//       			// $("#refresh-btn").trigger('click');
									//       			rMsg('Success!  Voided '+type+' #'+id,'success');
									//       			// btn.goLoad({load:true});
									//       			location.reload();
									//       			return false;


									//       		}
									//       		else{
									//       			rMsg(data.error,'error');
									//       			btn.goLoad({load:false});
									//       		}
									//       	// });
									//       	},'json');
									//         return true;
									//       }
									//     },
									//     cancel: {
									//       label: "CANCEL",
									//       className: "btn pop-manage pop-manage-red",
									//       callback: function() {
									//         // Example.show("uh oh, look out!");
									//         btn.goLoad({load:false});
									//         return true;
									//       }
									//     }
									//   }
									// });
									// return false;
								}else{
									loadDivs('reasons');
								}	
							}
						});			
					}else{
						rMsg(data.error,'error');
					}	
				},'json');
			
			return false;
		});
		$("#save-reserve-reason-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var types = $('.order-view-list').attr('type');
			var status = $('.order-view-list').attr('status');
			var tbl_id = $('.order-view-list').attr('table_id');
			var ref_no = $('.order-view-list').attr('ref_no');
    		var noError = $('#reasons_div').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var approver = $('.order-view-list').attr('approver');
					formData = 'approver='+approver;
					// formData += '&add_amount='+$('#add_amount').val()+'&ref_no='+$('#ref_no').val();
			      	formData += '&reason='+$('#rother-reason-txt').val()+'&credit_amount='+$('#credit_amount').val()+'&credit_note_no='+$('#credit_note_no').val();
				// alert(formData);
				// return false;
				$.post(baseUrl+'cashier/rvoid_order/'+id,formData,function(data){
					      		// console.log(data);
					      		// return false;
					      		if(data.error == ""){
					      			$("#reasons_div").modal('hide');
									// $("#pickTimeModal2").modal('hide');
					      			// $("#refresh-btn").trigger('click');

					      			if(data.js_rcps){
					      				html_print(data.js_rcps);
					      			}
					      			
					      			rMsg('Success!  Voided '+types+' #'+id,'success');
					      			// btn.goLoad({load:true});
					      			location.reload();
					      			return false;


					      		}
					      		else{
					      			rMsg(data.error,'error');
					      			btn.goLoad({load:false});
					      		}
					      	// });
					      	},'json');
			}
    		return false;
		});
		$("#change-to-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var status = $('.order-view-list').attr('status');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					$.callManager({
						success : function(manager){
							loadDivs('change-to');
						}
					});
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
			
			return false;
		});
		$(".change-to-btns").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var change_type = $(this).attr('ref');
			var btn = $(this);
			formData = 'type='+change_type;
			btn.goLoad();
			if(change_type == 'dinein' || change_type == 'eatigo' || change_type == 'bigdish' || change_type == 'zomato'){
				bootbox.dialog({
				  message: baseUrl+'cashier/transfer_tables',
				  // title: 'Somthing',
				  className: 'manager-call-pop',
				  buttons: {
				    submit: {
				      label: "Transfer",
				      className: "btn  pop-manage pop-manage-green",
				      callback: function() {
				        var sales_id = id;
				        var to_table = $('#to-table').val();
				        formData = 'type='+change_type+'&tbl_id='+to_table;
				       	$.post(baseUrl+'cashier/change_order_to/'+id,formData,function(data){
				       		// alert(data);
				       		// console.log(data);
				       		if(data.error == ""){
				       			$("#refresh-btn").trigger('click');
				       			rMsg('Success!  Order '+' #'+id+' Changed to '+type,'success');
				       			btn.goLoad({load:false});
				       		}
				       		else{
				       			rMsg(data.error,'error');
				       		}
				       	},'json');	
				       	// });	
				      }
				    },
				    cancel: {
				      label: "CANCEL",
				      className: "btn pop-manage pop-manage-red",
				      callback: function() {
				        // Example.show("uh oh, look out!");
				        btn.goLoad({load:false});
				      }
				    }
				  }
				});
			}	
			else{
				$.post(baseUrl+'cashier/change_order_to/'+id,formData,function(data){
					if(data.error == ""){
						$("#refresh-btn").trigger('click');
						rMsg('Success!  Order '+' #'+id+' Changed to '+type,'success');
						btn.goLoad({load:false});
					}
					else{
						rMsg(data.error,'error');
					}
				},'json');				
			}		
			// alert(data);
			// });
			return false;
		});
		$("#cancel-other-reason-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var approver = $('.order-view-list').attr('approver');
			var prev = $('#now-btn').attr('type');
			var old = 0;
			if(prev == 'all_trans'){
				old = 1;
			}
			var reason = $(this).text();
			var btn = $(this);
			btn.goLoad();
			formData = 'approver='+approver;
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/other_reason_pop',
			  title: 'Other Reason',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "Submit",
			      className: "btn btn-guest-submit pop-manage pop-manage-green",
			      callback: function() {
			      	formData += '&reason='+$('#other-reason-txt').val();
			      	$.post(baseUrl+'cashier/void_order/'+id+'/'+old,formData,function(data){
			      		if(data.error == ""){
			      			// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
			      			// });	
			      			if(data.js_rcps){
								html_print(data.js_rcps);
							}

			      			$("#refresh-btn").trigger('click');
			      			rMsg('Success!  Voided '+type+' #'+id,'success');
			      			btn.goLoad({load:false});
			      		}
			      		else{
			      			rMsg(data.error,'error');
			      			btn.goLoad({load:false});
			      		}
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
		$("#cancel-reserve-reason-btn").click(function(){
			// var id = $('.order-view-list').attr('ref');
			// var type = $('.order-view-list').attr('type');
			// var id = $("input[type=hidden]#tsales_id").val();
			// var type = $("input[type=hidden]#ttype").val();
			// var approver = $('#reasons_div').attr('approver');
			var id = $('.order-view-list').attr('ref');
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
			formData = 'approver='+approver;
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/void_reserve_reason_pop',
			  title: 'Void Reservation',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "Submit",
			      className: "btn btn-guest-submit pop-manage pop-manage-green",
			      callback: function() {
			      	formData += '&add_amount='+$('#add_amount').val()+'&ref_no='+$('#ref_no').val();
			      	$.post(baseUrl+'cashier/rvoid_order/'+id+'/'+old,formData,function(data){
			      		// console.log(data);
			      		if(data.error == ""){
			      			if(data.js_rcps){
								html_print(data.js_rcps);
							}
							
			      			$("#reasons_div").modal('hide');
							$("#pickTimeModal2").modal('hide');
			      			rMsg('Success!  Voided '+type+' #'+id,'success');
			      			location.reload();
			      			return false;
			      			btn.goLoad({load:false});
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
		$(".reason-btns").click(function(){
			var id = $('.order-view-list').attr('ref');
			var type = $('.order-view-list').attr('type');
			var approver = $('.order-view-list').attr('approver');

			var prev = $('#now-btn').attr('type');
			var old = 0;
			if(prev == 'all_trans'){
				old = 1;
			}
			var reason = $(this).text();
			var btn = $(this);
			btn.goLoad();
			formData = 'reason='+reason+'&approver='+approver;
			$.post(baseUrl+'cashier/void_order/'+id+'/'+old,formData,function(data){
				if(data.error == ""){
					// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
					// });	
					if(data.js_rcps){
						html_print(data.js_rcps);
					}

					$("#refresh-btn").trigger('click');
					rMsg('Success!  Voided '+type+' #'+id,'success');
					btn.goLoad({load:false});
				}
				else{
					rMsg(data.error,'error');
					btn.goLoad({load:false});
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$(".cancel-reason-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			$('#order-btn-'+id).trigger('click');
			return false;
		});
		$("#settle-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					formData = 'txt='+id;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
	       				// alert(data);
	       				// console.log(data);
						// window.location = baseUrl+'cashier/settle/'+id;
						window.location = baseUrl+'cashier/settle/'+data2;
	       			});
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
			return false;
		});
		$("#cash-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					formData = 'txt='+id;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
	       				// alert(data);
	       				// console.log(data);
						// window.location = baseUrl+'cashier/settle/'+id+'#cash';
						window.location = baseUrl+'cashier/settle/'+data2+'#cash';
	       			});
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
			return false;
		});
		$("#credit-btn").click(function(){
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					formData = 'txt='+id;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
	       				// alert(data);
	       				// console.log(data);
						// window.location = baseUrl+'cashier/settle/'+id+'#credit';
						window.location = baseUrl+'cashier/settle/'+data2+'#credit';
	       			});
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
			
			return false;
		});
		$("#receipt-btn").click(function(event){
			// alert('aw');
			event.preventDefault();
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert(table_id);

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					var prev = $('#now-btn').attr('type');
					var ur = baseUrl+'cashier/print_sales_receipt_justin/'+id;
					if(prev == 'all_trans'){
						ur = baseUrl+'cashier/reprint_receipt_previous/'+id;
					}
					// alert(change_db);
					$.post(ur,'',function(data)
					{
						if(data.js_rcps){
							html_print(data.js_rcps);
						}

						rMsg(data.msg,'success');						
						
					},'json');
					// });
				}else{
					rMsg(data.error,'error');
				}	
			},'json');


			
		});
		$('#reprint-os-btn').click(function(){
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');
			// alert('aw');

			$.post(baseUrl+'cashier/check_printer_online',function(data){
				//check if may activity yun table
				// alert(data.ping_error);
				// alert(data);
				if(data.ping_error == 0){
					$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
						if(data.error == ""){
							var prev = $('#now-btn').attr('type');
							var ur = 0;
							if(prev == 'all_trans'){
								ur = 1;
							}
							$.post(baseUrl+'cashier/print_os/'+id+'/0/1/'+ur,{'isjson':1},function(data){
								// console.log(data);
								if(data.js_rcps){
									html_print(data.js_rcps);
								}

								rMsg('Order Slip Reprinted.','success');
							},'json');
						}else{
							rMsg(data.error,'error');
						}	
					},'json');
				}else{
					// alert(id);

					$.rPopForm({
						loadUrl : 'cashier/printer_option',
						passTo	: 'cashier/print_setup_update_db',
						// title	: '<center>Hello ,</br>What task would you like me to do for you?</center>',
						// noButton: 1,
						hide : 1,
						rform 	: 'printer-setup-form',
						onComplete : function(data){
										// goTo('menu/subcategories');
										// alert(data);
										var prev = $('#now-btn').attr('type');
										var ur = 0;
										if(prev == 'all_trans'){
											ur = 1;
										}
										$.post(baseUrl+'cashier/print_os/'+id+'/0/1/'+ur,'',function(data){
											// alert(data);
											// console.log(data);
											if(data.js_rcps){
												html_print(data.js_rcps);
											}
											
											rMsg('Order Slip Reprinted.','success');
										});

									 }
					});


					// $.callPrinterSetup2({
					// 	addData : id,
					// 	success : function(){
							
					// 		var prev = $('#now-btn').attr('type');
					// 		var ur = 0;
					// 		if(prev == 'all_trans'){
					// 			ur = 1;
					// 		}
					// 		$.post(baseUrl+'cashier/print_os/'+id+'/0/1/'+ur,'',function(data){
					// 			// alert(data);
					// 			// console.log(data);
					// 			rMsg('Order Slip Reprinted.','success');
					// 		});


					// 	}
					// });
				}

			// });
			},'json');
			
			return false;
		});	
		$("#checklist-btn").click(function(event){
			// alert('aw');
			event.preventDefault();
			var id = $('.order-view-list').attr('ref');
			var tbl_id = $('.order-view-list').attr('table_id');

			//check if may activity yun table
			// $.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
			// 	if(data.error == ""){
					var prev = $('#now-btn').attr('type');
					var ur = baseUrl+'cashier/print_checklist/'+id;
					// if(prev == 'all_trans'){
					// 	ur = baseUrl+'cashier/reprint_receipt_previous/'+id;
					// }
					// alert(change_db);
					$.post(ur,'',function(data)
					{
						// alert(data);
						if(data.js_rcp){
							$('#print-rcp').html(data.js_rcp);
						}
						rMsg(data.msg,'success');
					},'json');
					// });
			// 	}else{
			// 		rMsg(data.error,'error');
			// 	}	
			// },'json');


			
		});
		$("#back-order-list-btn").click(function(){
			loadDivs('orders');
			return false;
		});
		function loadDivs(type){
			$('.center-loads-div').hide();
			$('.'+type+'-div').show();
		}
		var timeOut = 0;
		function loadOrders(terminal,status,types,now,search_id,server_id,search_val,page_no=1){
			terminal = 'my';
			
			// console.log(baseUrl+'cashier/orders/'+terminal+'/'+status+'/'+types+'/'+now+'/'+search_id+'/'+server_id);
			$.post(baseUrl+'cashier/orders/'+terminal+'/'+status+'/'+types+'/'+now+'/'+search_id+'/'+server_id+'/box/'+page_no,search_val,function(data){
				// alert(data);
				// console.log(data);
				clearTimeout(timeOut);
				if(data.has == 1){
					$('.orders-lists-load').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');

					$('.orders-lists-load').html(data.code);
					$('.orders-lists').perfectScrollbar({suppressScrollX: true});
					var idd = data.ids;
					if(data.ids != null){
						// if(idd.length > 0){
						var last_id = "";
						$.each(data.ids,function(id,val){
							$('#order-btn-'+id).click(function(){
								$('#prev-next-div').hide();
								loadDivs('orders-view');
								$('.order-view-list').attr('ref',id);
								$('.order-view-list').attr('type',val.type);
								$('.order-view-list').attr('status',val.status);
								$('.order-view-list').attr('table_id',val.table_id);
								if(val.status == 'voided'){
									$('#recall-btn').attr('disabled','disabled');
									$('#split-btn').attr('disabled','disabled');
									$('#combine-btn').attr('disabled','disabled');
									$('#settle-btn').attr('disabled','disabled');
									// $('#receipt-btn').attr('disabled','disabled');
									$('#reprint-os-btn').attr('disabled','disabled');
									$('#cash-btn').attr('disabled','disabled');
									$('#credit-btn').attr('disabled','disabled');
									$('#void-btn').attr('disabled','disabled');
									$('#change-to-btn').attr('disabled','disabled');
								}else{
									if(now == 'all_trans'){
										$('#recall-btn').attr('disabled','disabled');
										$('#split-btn').attr('disabled','disabled');
										$('#combine-btn').attr('disabled','disabled');
										$('#settle-btn').attr('disabled','disabled');
										// $('#receipt-btn').attr('disabled','disabled');
										$('#cash-btn').attr('disabled','disabled');
										$('#credit-btn').attr('disabled','disabled');
										// $('#void-btn').attr('disabled','disabled');
									}
									else{
										
										//FOR TAGUEGARAO DISABLE RECALL WHEN STETTLED
											if(val.status == 'settled'){
												$('#recall-btn').attr('disabled','disabled');
												$('#split-btn').attr('disabled','disabled');
												$('#combine-btn').attr('disabled','disabled');
												$('#settle-btn').attr('disabled','disabled');
												$('#cash-btn').attr('disabled','disabled');
												$('#credit-btn').attr('disabled','disabled');
												$('#change-to-btn').attr('disabled','disabled');
												$('#change-to-btn').remove();
												$('#reprint-os-btn').attr('disabled','disabled');
												$('#update-to-btn').show();
											}
											else{
												$('#recall-btn').removeAttr('disabled');
												$('#split-btn').removeAttr('disabled');
												$('#combine-btn').removeAttr('disabled');
												$('#settle-btn').removeAttr('disabled');
												$('#receipt-btn').removeAttr('disabled');
												$('#cash-btn').removeAttr('disabled');
												$('#credit-btn').removeAttr('disabled');
												$('#void-btn').removeAttr('disabled');
												$('#change-to-btn').removeAttr('disabled');
											}

										//ORIGINAL
											// $('#recall-btn').removeAttr('disabled');
											// $('#split-btn').removeAttr('disabled');
											// $('#combine-btn').removeAttr('disabled');
											// $('#settle-btn').removeAttr('disabled');
											// $('#receipt-btn').removeAttr('disabled');
											// $('#cash-btn').removeAttr('disabled');
											// $('#credit-btn').removeAttr('disabled');
											// $('#void-btn').removeAttr('disabled');
									}
								}
								$('.order-view-list').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin"></i></div></center>');
								var prev = 0;
								if(now == 'all_trans')
									prev = 1;
								$.post(baseUrl+'cashier/order_view/'+id+'/'+prev,function(data){
									// console.log(data);
									$('.order-view-list').html(data.code);
									$('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
									// alert(data);
								// });
								},'json');
								return false;
							});
							last_id = id;
						});
						// console.log(last_id);
						$('.orders-lists').attr('last_id',last_id);
					}
					checkNewOrder();
					loadDivs('orders');
				}
				else if(page_no==1){
					$('.orders-lists-load').html("<center> No Orders Found. </center>");
					loadDivs('orders');
				}
			// });
			},'json');
		}
		function checkNewOrder(){
			var last_id = $('.orders-lists').attr('last_id');
			// if (typeof last_id !== typeof undefined && last_id !== false) {
			// 	last_id = "";
			// }
			// console.log(last_id);
				// last_id = "";
	    		var status = $('#status-btn').attr('type');
				var terminal = $('#terminal-btn').attr('type');
				if(status == 'open'){
		    		if($('#types-btn').exists())
			    		types = $('#types-btn').attr('type');
			    	else
			    		types = 'all';
		    		now = $('#now-btn').attr('type');
		    		search_id = 'none';
		    		server_id = '0';
		    		loadNewOrders(terminal,status,types,now,search_id,server_id,last_id);
				}
			timeOut = setTimeout(function(){
		  		checkNewOrder();
			}, 1000);	
		}
		function loadNewOrders(terminal,status,types,now,search_id,server_id,last_id){
			terminal = 'my';
			// console.log('cashier/new_orders/'+terminal+'/'+status+'/'+types+'/'+now+'/'+search_id+'/'+server_id+'/'+last_id);
			$.post(baseUrl+'cashier/new_orders/'+terminal+'/'+status+'/'+types+'/'+now+'/'+search_id+'/'+server_id+'/'+last_id,function(data){
				// console.log(data);
				var ids = data.ids;
				if(!$.isEmptyObject(ids)){
					// console.log($.isEmptyObject(ids));
					if(last_id == ""){
						$('.orders-lists-load').find('.row').append(data.code);						
					}
					else{
						$('.orders-lists-load').closest('.row').find('.col-md-6').first().before(data.code);						
					}
					$.each(data.ids,function(id,val){
						$('#order-btn-'+id).click(function(){
							loadDivs('orders-view');
							$('.order-view-list').attr('ref',id);
							$('.order-view-list').attr('type',val.type);
							$('.order-view-list').attr('status',val.status);
							if(val.status == 'voided'){
								$('#recall-btn').attr('disabled','disabled');
								$('#split-btn').attr('disabled','disabled');
								$('#combine-btn').attr('disabled','disabled');
								$('#settle-btn').attr('disabled','disabled');
								// $('#receipt-btn').attr('disabled','disabled');
								$('#cash-btn').attr('disabled','disabled');
								$('#credit-btn').attr('disabled','disabled');
								$('#void-btn').attr('disabled','disabled');
							}else{
								if(now == 'all_trans'){
									$('#recall-btn').attr('disabled','disabled');
									$('#split-btn').attr('disabled','disabled');
									$('#combine-btn').attr('disabled','disabled');
									$('#settle-btn').attr('disabled','disabled');
									// $('#receipt-btn').attr('disabled','disabled');
									$('#cash-btn').attr('disabled','disabled');
									$('#credit-btn').attr('disabled','disabled');
									// $('#void-btn').attr('disabled','disabled');
								}
								else{
									
									//FOR TAGUEGARAO DISABLE RECALL WHEN STETTLED
										if(val.status == 'settled'){
											$('#recall-btn').attr('disabled','disabled');
											$('#split-btn').attr('disabled','disabled');
											$('#combine-btn').attr('disabled','disabled');
											$('#settle-btn').attr('disabled','disabled');
											$('#cash-btn').attr('disabled','disabled');
											$('#credit-btn').attr('disabled','disabled');
										}
										else{
											$('#recall-btn').removeAttr('disabled');
											$('#split-btn').removeAttr('disabled');
											$('#combine-btn').removeAttr('disabled');
											$('#settle-btn').removeAttr('disabled');
											$('#receipt-btn').removeAttr('disabled');
											$('#cash-btn').removeAttr('disabled');
											$('#credit-btn').removeAttr('disabled');
											$('#void-btn').removeAttr('disabled');
										}

									//ORIGINAL
										// $('#recall-btn').removeAttr('disabled');
										// $('#split-btn').removeAttr('disabled');
										// $('#combine-btn').removeAttr('disabled');
										// $('#settle-btn').removeAttr('disabled');
										// $('#receipt-btn').removeAttr('disabled');
										// $('#cash-btn').removeAttr('disabled');
										// $('#credit-btn').removeAttr('disabled');
										// $('#void-btn').removeAttr('disabled');
								}
							}
							$('.order-view-list').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin"></i></div></center>');
							var prev = 0;
							if(now == 'all_trans')
								prev = 1;
							$.post(baseUrl+'cashier/order_view/'+id+'/'+prev,function(data){
								$('.order-view-list').html(data.code);
								$('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
								// alert(data);
							// });
							},'json');
							return false;
						});
					
						$('.orders-lists').attr('last_id',id);
					});	
				}
			},'json');	
			// });	
		}
		//timeticker
		function startTime(){
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();

            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);

            // dd = today.getDate();
            // mm = today.getMonth();
            // yy = today.getFullYear();

           	time = h + ":" + m + ":" + s;



            //Check for PM and AM
            var day_or_night = (h > 11) ? "PM" : "AM";

            //Convert to 12 hours system
            if (h > 12)
                h -= 12;

            //Add time to the headline and update every 500 milliseconds
            $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
            // var day = d.getDate();

            
            // alert(datetime);

            if(m == '00' && s == '00'){
            	// alert('aw');
            	//create textfile

            	$.post(baseUrl+'ayala/regen_file_hourly','date='+time,function(data){
					// alert(data);
					// console.log(data);
					// if(data.error == 0){
					// 	rMsg(data.msg,'success');
					// 	$.post(baseUrl+'ayala/get_file','date='+date,function(data2){
					// 		var prints = data2;
					// 		$('#daily-div').html(prints['daily']);					
					// 		$('#hourly-div').html(prints['hourly']);					
					// 		btn.goLoad2({load:false});
					// 	},'json').fail( function(xhr, textStatus, errorThrown) {
					//         alert(xhr.responseText);
					//     });
						
					// }
					// else{
					// 	rMsg(data.msg,'error');
					// 	btn.goLoad2({load:false});
					// }
				// },'json').fail( function(xhr, textStatus, errorThrown) {
			 //        alert(xhr.responseText);
			    });


            }

            setTimeout(function() {
                startTime();
            }, 500);
        }
        function checkTime(i){
            if (i < 10)
            {
                i = "0" + i;
            }
            return i;
        }
	    ///////////////////////////////Jed//////////////////////////////////
	    ////////////////////////////////////////////////////////////////////
    	$('#terminal-btn').click(function(){
    		$('#server-search').hide();
    		$('#orders-search').hide();
    		btn = $(this).attr('btn');
    		type = $(this).attr('type');
    		if(type == 'my'){
    			act = 'all';
    			$(this).attr('type',act);
    			$('#terminal_text').html('<i class="fa fa-users fa-2x fa-fw"></i><br>ALL');
    		}else{
    			act = 'my';
    			$(this).attr('type',act);
    			$('#terminal_text').html('<i class="fa fa-desktop fa-2x fa-fw"></i><br>MY');
    		}

    		terminal = $('#terminal-btn').attr('type');
    		status = $('#status-btn').attr('type');
    		if($('#types-btn').exists())
	    		types = $('#types-btn').attr('type');
	    	else
	    		types = 'all';
    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';

    		loadOrders(terminal,status,types,now,search_id,server_id);
    	});
    	$('#status-btn').click(function(){
    		$('#server-search').hide();
    		$('#orders-search').hide();
    		btn = $(this).attr('btn');
    		type = $(this).attr('type');
    		if(type == 'open'){
    			act = 'settled';
    			$(this).attr('type',act);
    			$('#status_text').html('<i class="fa fa-arrow-down fa-2x fa-fw"></i><br><?php echo lng("cp_btn_settled") ?>');
    		}else if(type == 'settled'){
    			act = 'cancel';
    			$(this).attr('type',act);
    			$('#status_text').html('<i class="fa fa-ban fa-2x fa-fw"></i><br><?php echo lng("cp_btn_cancelled") ?>');
    		}else if(type == 'cancel'){
    			act = 'voided';
    			$(this).attr('type',act);
    			$('#status_text').html('<i class="fa fa-times fa-2x fa-fw"></i><br><?php echo lng("cp_btn_voided") ?>');
    		}else{
    			act = 'open';
    			$(this).attr('type',act);
    			$('#status_text').html('<i class="fa fa-arrow-up fa-2x fa-fw"></i><br><?php echo lng("cp_btn_open") ?>');
    		}

    		terminal = $('#terminal-btn').attr('type');
    		status = $('#status-btn').attr('type');
    		if($('#types-btn').exists())
	    		types = $('#types-btn').attr('type');
	    	else
	    		types = 'all';

    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';
    		loadOrders(terminal,status,types,now,search_id,server_id);
    	});


    	$('.stat-btn').click(function(){
    		// alert('aw');
    		$('#page_trans_value').val(1);
    		$('#server-search').hide();
    		$('#orders-search').hide();
    		btn = $(this).attr('btn');
    		type = $(this).attr('type');

    		terminal = $('#terminal-btn').attr('type');
    		status = $(this).attr('type');
    		if($('#types-btn').exists())
	    		types = $('#types-btn').attr('type');
	    	else
	    		types = 'all';


	    	$('#type-txt').text(type.toUpperCase()+" TRANSACTIONS");
	    	$('#status_hid').val(status);

    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';
    		loadOrders(terminal,status,types,now,search_id,server_id);
    	});

    	$('#types-btn').click(function(){
    		$('#server-search').hide();
    		$('#orders-search').hide();
    		btn = $(this).attr('btn');
    		type = $(this).attr('type');
    		if(type == 'all'){
    			act = 'dinein';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-sign-in fa-2x fa-fw"></i><br>FOR HERE');
    		}
    		else if(type == 'dinein'){
    			act = 'delivery';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-truck fa-2x fa-fw"></i><br>DELIVERY');
    		}
    		else if(type == 'delivery'){
    			act = 'counter';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-keyboard-o fa-2x fa-fw"></i><br>COUNTER');
    		}
    		else if(type == 'counter'){
    			act = 'pickup';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-briefcase fa-2x fa-fw"></i><br>PICKUP');
    		}
    		else if(type == 'pickup'){
    			act = 'takeout';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-sign-out fa-2x fa-fw"></i><br>TO GO');
    		}
    		else if(type == 'takeout'){
    			act = 'drivethru';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-road fa-2x fa-fw"></i><br>DRIVE-THRU');
    		}else if(type == 'drivethru'){
    			act = 'reservation';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-book fa-2x fa-fw"></i><br>RESERVATION');
    		}
    		else if(type == 'reservation'){
    			act = 'all';
    			$(this).attr('type',act);
    			$('#types_text').html('<i class="fa fa-book fa-2x fa-fw"></i><br>ALL TYPES');
    		}

    		terminal = $('#terminal-btn').attr('type');
    		status = $('#status-btn').attr('type');
    		types = $('#types-btn').attr('type');
    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';

    		loadOrders(terminal,status,types,now,search_id,server_id);
    	});
		$('#now-btn').click(function(){
			$('#server-search').hide();
			$('#orders-search').hide();
    		btn = $(this).attr('btn');
    		type = $(this).attr('type');
    		if(type == 'all_trans'){
    			act = 'now';
    			$(this).attr('type',act);
    			$('#day_text').html('<i class="fa fa-clock-o fa-2x fa-fw"></i><br><?php echo lng("cp_btn_today") ?>');
    		}else{
    			act = 'all_trans';
    			$(this).attr('type',act);
    			$('#day_text').html('<i class="fa fa-clock-o fa-2x fa-fw"></i><br><?php echo lng("cp_btn_previous") ?>');
    		}

    		terminal = $('#terminal-btn').attr('type');
    		status = $('#status-btn').attr('type');
    		if($('#types-btn').exists())
	    		types = $('#types-btn').attr('type');
	    	else
	    		types = 'all';
    		now = $('#now-btn').attr('type');
    		search_id = 'none';
    		server_id = '0';

    		loadOrders(terminal,status,types,now,search_id,server_id);
    	});
    	$('#look-btn').click(function(){
    		$('.orders-lists').hide();
    		$('.orders-view-div').hide();
    		$('#orders-search').show();
    		$('#server-search').hide();
    	});
    	$('#server-btn').click(function(){
    		$('#orders-search').hide();
    		$('.orders-lists').hide();
    		$('.orders-view-div').hide();
    		$('#server-search').show();
    	});
    	$('#search-order-btn').click(function(event){
    		search_val = $('#search-form-order').serialize();
    		$('#orders-search').hide();
    		loadOrders(terminal,status,types,now,search_id,server_id,search_val);
    		return false;
    	});	
    	$('#go-search-order').click(function(event){

    		search_id = $('#search-order').val();
    		$('#orders-search').hide();
    		loadOrders(terminal,status,types,now,search_id,server_id);
    		$('#search-order').val('');
    		event.preventDefault();
    	});
    	$('#search-server-btn').click(function(event){

    		server_id = $('#user').val();
    		if(server_id == ''){
    			rMsg('Select a food server.','warning');
    		}else{

	    		$('#server-search').hide();
	    		loadOrders(terminal,status,types,now,search_id,server_id);
	    		$('#user').val('');
    		}
    		// $('#search-order').val('');
    		event.preventDefault();
    	});

    	//check online order
    	$('#online-order-notif').click(function(){
    		 $('.notify-bubble').hide(400);
    		$('#online-order-btn').click();
    	});

    	$('#online-order-btn').click(function(){
    		$('#server-search').hide();
    		$('#orders-search').hide();
    		$('.orders-lists').show();
    		$('.orders-view-div').hide();
    		$('.online-orders-view-div').show();
    		terminal = $('#terminal-btn').attr('type');
    		
    		$('#type-txt').text("ONLINE ORDER TRANSACTIONS");
    		// loadOnlineOrders(terminal,status,types,now,search_id,server_id);
    		loadOnlineOrders(terminal,'','','','','');
    		$('.online-orders-lists-load').find('.row').html(123);	

    		return false;
    	});

    	function loadOnlineOrders(terminal,status,types,now,search_id,server_id,search_val){
			terminal = 'my';
			$('.orders-lists-load').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
			// console.log(baseUrl+'cashier/orders/'+terminal+'/'+status+'/'+types+'/'+now+'/'+search_id+'/'+server_id);
			$.post(baseUrl+'cashier/online_orders/'+terminal,function(data){
				// alert(data);
				// console.log(data);
				clearTimeout(timeOut);
				if(data.has == 1){
					$('.orders-lists-load').html(data.code);
					$('.orders-lists').perfectScrollbar({suppressScrollX: true});
					var idd = data.ids;
					if(data.ids != null){
						// if(idd.length > 0){
						var last_id = "";
						$.each(data.ids,function(id,val){
							$('#order-btn-'+id).click(function(){
								loadDivs('orders-view');
								$('.order-view-list').attr('ref',id);
								$('.order-view-list').attr('type',val.type);
								$('.order-view-list').attr('status',val.status);
								$('.order-view-list').attr('table_id',val.table_id);
								if(val.status == 'voided'){
									$('#recall-btn').attr('disabled','disabled');
									$('#split-btn').attr('disabled','disabled');
									$('#combine-btn').attr('disabled','disabled');
									$('#settle-btn').attr('disabled','disabled');
									// $('#receipt-btn').attr('disabled','disabled');
									$('#reprint-os-btn').attr('disabled','disabled');
									$('#cash-btn').attr('disabled','disabled');
									$('#credit-btn').attr('disabled','disabled');
									$('#void-btn').attr('disabled','disabled');
									$('#change-to-btn').attr('disabled','disabled');
								}else{
									if(now == 'all_trans'){
										$('#recall-btn').attr('disabled','disabled');
										$('#split-btn').attr('disabled','disabled');
										$('#combine-btn').attr('disabled','disabled');
										$('#settle-btn').attr('disabled','disabled');
										// $('#receipt-btn').attr('disabled','disabled');
										$('#cash-btn').attr('disabled','disabled');
										$('#credit-btn').attr('disabled','disabled');
										// $('#void-btn').attr('disabled','disabled');
									}
									else{
										
										//FOR TAGUEGARAO DISABLE RECALL WHEN STETTLED
											if(val.status == 'settled'){
												$('#recall-btn').attr('disabled','disabled');
												$('#split-btn').attr('disabled','disabled');
												$('#combine-btn').attr('disabled','disabled');
												$('#settle-btn').attr('disabled','disabled');
												$('#cash-btn').attr('disabled','disabled');
												$('#credit-btn').attr('disabled','disabled');
												$('#change-to-btn').attr('disabled','disabled');
												$('#reprint-os-btn').attr('disabled','disabled');
											}
											else{
												$('#recall-btn').removeAttr('disabled');
												$('#split-btn').removeAttr('disabled');
												$('#combine-btn').removeAttr('disabled');
												$('#settle-btn').removeAttr('disabled');
												$('#receipt-btn').removeAttr('disabled');
												$('#cash-btn').removeAttr('disabled');
												$('#credit-btn').removeAttr('disabled');
												$('#void-btn').removeAttr('disabled');
												$('#change-to-btn').removeAttr('disabled');
											}

										//ORIGINAL
											// $('#recall-btn').removeAttr('disabled');
											// $('#split-btn').removeAttr('disabled');
											// $('#combine-btn').removeAttr('disabled');
											// $('#settle-btn').removeAttr('disabled');
											// $('#receipt-btn').removeAttr('disabled');
											// $('#cash-btn').removeAttr('disabled');
											// $('#credit-btn').removeAttr('disabled');
											// $('#void-btn').removeAttr('disabled');
									}
								}
								$('.order-view-list').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin"></i></div></center>');
								var prev = 0;
								if(now == 'all_trans')
									prev = 1;
								$.post(baseUrl+'cashier/order_view/'+id+'/'+prev,function(data){
									// console.log(data);
									$('.order-view-list').html(data.code);
									$('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
									// alert(data);
								// });
								},'json');
								return false;
							});
							last_id = id;
						});
						// console.log(last_id);
						$('.orders-lists').attr('last_id',last_id);
					}
					// checkNewOrder();
					loadDivs('orders');
				}
				else{
					$('.orders-lists-load').html("<center> No Orders Found. </center>");
					loadDivs('orders');
				}
			// });
			},'json');
		}

		$('#s_ref_code,#s_rec_code,#s_from_amt,#s_to_amt')
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

        function html_print(js_rcps){ 
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){	
			   		// alert(JSON.stringify(value.printer));		   
					$('#print-rcp').html(v.value);
				});
			});
        	
        }
    <?php elseif($use_js == 'searchPanelJs'): ?>
    	$('#search-btn').click(function(){
    		$('#search-form').rOkay({
    			passTo 		: 'prints/order_box',	
    			onComplete 	: function (data){
    							alert(data);
    						}
    		});
    		return false;
    	});	
    	$('#back-btn').click(function(){
    		window.location = baseUrl+'cashier';
    		return false;
    	});
    	$('.daterangepicker').each(function(index){
 			if ($(this).hasClass('datetimepicker')) {
 				$(this).daterangepicker({separator: ' to ', timePicker: true, timePickerIncrement:15, format: 'YYYY/MM/DD h:mm A'});
 			} else {
 				$(this).daterangepicker({separator: ' to '});
 			}
 		});
    <?php elseif($use_js == 'counterJs'): ?>
    	
  //   	$("#scan-txt").animate({opacity:0},200,"linear",function(){
		//   $(this).animate({opacity:1},200);
		// });
		function blink_text() {
		    $('#scan-txt').text('BARCODE SCAN READY');
		    $('#scan-txt').fadeOut(1000);
		    $('#scan-txt').fadeIn(1000);
		}
		setInterval(blink_text, 2000);

    	var active_div='category';
    	var ctrlKeyDown = false;

		// $(document).ready(function(){    
		//     $(document).on("keydown", fkeydown);
		//     $(document).on("keyup", fkeyup);
		//     $(document).bind("contextmenu",function(e){
  //             return false;
  //      		}); 
		// });

		function fkeydown(e) { 

		    if ((e.which || e.keyCode) == 116 || ((e.which || e.keyCode) == 82 && ctrlKeyDown)) {
		        // Pressing F5 or Ctrl+R
		        e.preventDefault();
		    } else if ((e.which || e.keyCode) == 17) {
		        // Pressing  only Ctrl
		        ctrlKeyDown = true;
		    }
		};

		function fkeyup(e){
		    // Key up Ctrl
		    if ((e.which || e.keyCode) == 17) 
		        ctrlKeyDown = false;
		};
		
    	startTime();

    	function startTime(){
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();

            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);

            // dd = today.getDate();
            // mm = today.getMonth();
            // yy = today.getFullYear();

           	time = h + ":" + m + ":" + s;



            //Check for PM and AM
            var day_or_night = (h > 11) ? "PM" : "AM";

            //Convert to 12 hours system
            if (h > 12)
                h -= 12;

            //Add time to the headline and update every 500 milliseconds
            // $('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
            // var day = d.getDate();

            
            // alert(datetime);

            if(m == '00' && s == '00'){
            	// alert('aw');
            	//create textfile

            	$.post(baseUrl+'ayala/regen_file_hourly','date='+time,function(data){
					// alert(data);
					// console.log(data);
					// if(data.error == 0){
					// 	rMsg(data.msg,'success');
					// 	$.post(baseUrl+'ayala/get_file','date='+date,function(data2){
					// 		var prints = data2;
					// 		$('#daily-div').html(prints['daily']);					
					// 		$('#hourly-div').html(prints['hourly']);					
					// 		btn.goLoad2({load:false});
					// 	},'json').fail( function(xhr, textStatus, errorThrown) {
					//         alert(xhr.responseText);
					//     });
						
					// }
					// else{
					// 	rMsg(data.msg,'error');
					// 	btn.goLoad2({load:false});
					// }
				// },'json').fail( function(xhr, textStatus, errorThrown) {
			 //        alert(xhr.responseText);
			    });


            }

            setTimeout(function() {
                startTime();
            }, 500);
        }
        function checkTime(i){
            if (i < 10)
            {
                i = "0" + i;
            }
            return i;
        }

		// $(':button').click(function(){
		// 	$.beep();
		// 	// alert('here');
		// });
		
		$(document).scannerDetection({
			avgTimeByChar: 40,
			onComplete: function(barcode, qty){ 
				// console.log(barcode);
				// console.log(qty);
				var formData = 'barcode='+barcode;

				$('#scan-txt').text(barcode);
				// alert(formData);
				scannedRetailItem(formData);
			},
			onError: function(string){}
		});
		$('#counter').disableSelection();
		var scrolled=0;
		var transScroll=0;
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		loadMenuCategories();
		loadTransCart();
		loadTransChargeCart();
		var hashTag = window.location.hash;
		if(hashTag == '#retail'){
			remeload('retail');
		}
		$('#free-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var id  = $('.selected').attr("ref");
			if (typeof id !== typeof undefined && id !== false) {
				$.callManager({
					success : function(manager){
						var man_user = manager.manager_username;
						var man_id = manager.manager_id;
							
						

						$.callFreeReasons({
								submit : function(reason){
									// console.log(reason);
									var free_reason = reason;
									// if(free_reason == ""){
									// 	rMsg('Please select/input a reason','error');
									// 	return false;
									// }else{
										var formData = 'free_user_id='+man_id+'&free_reason='+free_reason;

										$('body').goLoad2();
										$.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
											// console.log(data);
											$('#trans-row-'+id+' .cost').text(0);
											var text = $('#trans-row-'+id).find('.name').text();
											// alert(text);
											$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text + " <br>&nbsp;&nbsp;&nbsp;<span class='label label-sm label-warning'>"+free_reason+"</span>");
											transTotal();
											rMsg('Updated Menu as free','success');
											$('body').goLoad2({load:false});
										});
									// }
									
									// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
									// 	// alert(data);
									// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
									// 		sel.prev().addClass('selected');
									// 		sel.remove();
									// 		if(cart == 'trans_cart' && retail === false){
									// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
									// 				var cat_id = $(".category-btns:first").attr('ref');
									// 				var cat_name = $(".category-btns:first").text();
									// 				var val = {'name':cat_name};
									// 				loadsDiv('menus',cat_id,val,null);
									// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
									// 			});
									// 		}
									// 		$('.counter-center .body').perfectScrollbar('update');
									// 		transTotal();
									// 	},'json');
									// });

								}
							});
						// var formData = 'free_user_id='+man_id;
						// $('body').goLoad2();
						// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
						// 	$('#trans-row-'+id+' .cost').text(0);
						// 	var text = $('#trans-row-'+id).find('.name').text();
						// 	// alert(text);
						// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text);
						// 	transTotal();
						// 	rMsg('Updated Menu as free','success');
						// 	$('body').goLoad2({load:false});
						// });
					}
				});
			}
			return false;
		});
		$('#serve-no-btn').click(function(){
			$.callServerNo({
				success : function(serve_no){
					$('#ord-serve-no').text('Serve No.'+serve_no);	
					rMsg('Serve # Updated to '+serve_no,'success');
				}
			});
			return false;
		});
		$('#rcpt-prev-btn').click(function(){
			$.post(baseUrl+'cashier/submit_trans_temp/true/null/false/0/null/null/false/null/false',function(data){

				// alert(data);
				// console.log(data);

				$.rPopForm({
					loadUrl : 'reprint/view_receipt/1',
					passTo	: '',
					// title	: '<center>Hello ,</br>What task would you like me to do for you?</center>',
					noButton: 1,
					// rform 	: 'guide_form',
					onComplete : function(){
									// goTo('menu/subcategories');
								 }
				});
				// alert(data);
				// if(data.error != null){
				// 	rMsg(data.error,'error');
				// 	// btn.prop('disabled', false);
				// }
				// else{
				// 	// if(data.act == 'add'){
				// 	// 	newTransaction(false,data.type);
				// 	// 	if(btn.attr('id') == 'submit-btn'){
				// 	// 		rMsg('Success! Transaction Submitted.','success');
				// 	// 	}
				// 	// 	else{
				// 	// 		rMsg('Transaction Hold.','warning');
				// 	// 	}
				// 	// 	btn.prop('disabled', false);
				// 	// }
				// 	// else{
				// 	// 	newTransaction(true,data.type);
				// 	// }
				// }

				// $("#zero-rated-btn").removeClass('counter-btn-green');
				// $("#zero-rated-btn").removeClass('zero-rated-active');
				// $("#zero-rated-btn").addClass('counter-btn-red');
				// $('.center-div .foot .foot-det').css({'background-color':'#fff'});
				// $('.center-div .foot .foot-det .receipt').css({'color':'#000'});
				
				// $('.counter-center .body').perfectScrollbar('update');
				// $(".counter-center .body").scrollTop(0);
			// },'json').fail( function(xhr, textStatus, errorThrown) {
			// 		   alert(xhr.responseText);
			});	
		});
		$('#submit-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = true;
			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}

			if(doPrintOS){
				$.post(baseUrl+'cashier/check_printer_online',function(data){

					if(data.ping_error == 0){

						var go = true;
						if($('#buy2take1').exists()){
							if(!$('#counter').hasClass('on-promo-choose')){
								$('#counter').addClass('on-promo-choose');
								$('#counter').addClass('on-promo-submit');
								$.post(baseUrl+'cashier/buy2take1_qty',function(data){
									$('#counter').attr('promo-qty',data.qty);
									$('#promo-qty').text(data.qty);
									$('#promo-txt').show();
								},'json');
								go = false;
							}
							else{
								$('#counter').removeClass('on-promo-choose');
								go = true;
							}				
						}
						if(go){
							btn.prop('disabled', true);
							$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
							
								if(data.error != null){
									rMsg(data.error,'error');
									btn.prop('disabled', false);
								}
								else{
									if(data.js_rcps){
										$.ajaxSetup({async: false});										
										html_print(data.js_rcps);
									}
									
									if(data.act == 'add'){
										newTransaction(false,data.type);
										if(btn.attr('id') == 'submit-btn'){
											rMsg('Success! Transaction Submitted.','success');
										}
										else{
											rMsg('Transaction Hold.','warning');
										}
										btn.prop('disabled', false);
									}
									else{
										newTransaction(true,data.type);
									}
								}

								$("#zero-rated-btn").removeClass('counter-btn-green');
								$("#zero-rated-btn").removeClass('zero-rated-active');
								$("#zero-rated-btn").addClass('counter-btn-red');
								$('.center-div .foot .foot-det').css({'background-color':'#fff'});
								$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
								
								$('.counter-center .body').perfectScrollbar('update');
								$(".counter-center .body").scrollTop(0);
							},'json').fail( function(xhr, textStatus, errorThrown) {
									   alert(xhr.responseText);
									});	
							// alert(data);
							// });
						}
						return false;

					}else{

						$.rPopForm({
							loadUrl : 'cashier/printer_option',
							passTo	: 'cashier/print_setup_update_db',
							// title	: '<center>Hello ,</br>What task would you like me to do for you?</center>',
							// noButton: 1,
							hide : 1,
							rform 	: 'printer-setup-form',
							onComplete : function(data){
											
											var go = true;
											if($('#buy2take1').exists()){
												if(!$('#counter').hasClass('on-promo-choose')){
													$('#counter').addClass('on-promo-choose');
													$('#counter').addClass('on-promo-submit');
													$.post(baseUrl+'cashier/buy2take1_qty',function(data){
														$('#counter').attr('promo-qty',data.qty);
														$('#promo-qty').text(data.qty);
														$('#promo-txt').show();
													},'json');
													go = false;
												}
												else{
													$('#counter').removeClass('on-promo-choose');
													go = true;
												}				
											}
											if(go){
												btn.prop('disabled', true);
												$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
												
													if(data.error != null){
														rMsg(data.error,'error');
														btn.prop('disabled', false);
													}
													else{
														if(data.act == 'add'){
															newTransaction(false,data.type);
															if(btn.attr('id') == 'submit-btn'){
																rMsg('Success! Transaction Submitted.','success');
															}
															else{
																rMsg('Transaction Hold.','warning');
															}
															btn.prop('disabled', false);
														}
														else{
															newTransaction(true,data.type);
														}
													}

													$("#zero-rated-btn").removeClass('counter-btn-green');
													$("#zero-rated-btn").removeClass('zero-rated-active');
													$("#zero-rated-btn").addClass('counter-btn-red');
													$('.center-div .foot .foot-det').css({'background-color':'#fff'});
													$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
													
													$('.counter-center .body').perfectScrollbar('update');
													$(".counter-center .body").scrollTop(0);
												},'json').fail( function(xhr, textStatus, errorThrown) {
														   alert(xhr.responseText);
														});	
												// alert(data);
												// });
											}
											return false;



											

										 }
						});


					}



				},'json');
			}else{
				var go = true;
				if($('#buy2take1').exists()){
					if(!$('#counter').hasClass('on-promo-choose')){
						$('#counter').addClass('on-promo-choose');
						$('#counter').addClass('on-promo-submit');
						$.post(baseUrl+'cashier/buy2take1_qty',function(data){
							$('#counter').attr('promo-qty',data.qty);
							$('#promo-qty').text(data.qty);
							$('#promo-txt').show();
						},'json');
						go = false;
					}
					else{
						$('#counter').removeClass('on-promo-choose');
						go = true;
					}				
				}
				if(go){
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
					
						if(data.error != null){
							rMsg(data.error,'error');
							btn.prop('disabled', false);
						}
						else{
							if(data.act == 'add'){
								newTransaction(false,data.type);
								if(btn.attr('id') == 'submit-btn'){
									rMsg('Success! Transaction Submitted.','success');
								}
								else{
									rMsg('Transaction Hold.','warning');
								}
								btn.prop('disabled', false);
							}
							else{
								newTransaction(true,data.type);
							}
						}

						$("#zero-rated-btn").removeClass('counter-btn-green');
						$("#zero-rated-btn").removeClass('zero-rated-active');
						$("#zero-rated-btn").addClass('counter-btn-red');
						$('.center-div .foot .foot-det').css({'background-color':'#fff'});
						$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
						
						$('.counter-center .body').perfectScrollbar('update');
						$(".counter-center .body").scrollTop(0);
					},'json').fail( function(xhr, textStatus, errorThrown) {
							   alert(xhr.responseText);
							});	
					// alert(data);
					// });
				}
				return false;
			}


				
		});
		$('#send-trans-btn').click(function(){
			ttype = $('#ttype').val();
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = false;
						// alert(print);return false;

			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}
			if(ttype == 'counter' || ttype == 'takeout'){
				// if($("#ord-serve-no").text() == "" && ttype == 'takeout'){
				// 	rMsg('Select Serve No.','error');
				// }
				// else{
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
						if(data.error != null){
							rMsg(data.error,'error');
							btn.prop('disabled', false);
						}
						else{
							if(data.act == 'add'){
								newTransaction(false,data.type);
								if(btn.attr('id') == 'send-trans-btn'){
									rMsg('Success! Transaction Submitted.','success');
								}
								else{
									rMsg('Transaction Hold.','warning');
								}
								btn.prop('disabled', false);
							}
							else{
								newTransaction(true,data.type);
							}
						}

						$("#zero-rated-btn").removeClass('counter-btn-green');
						$("#zero-rated-btn").removeClass('zero-rated-active');
						$("#zero-rated-btn").addClass('counter-btn-red');
						$('.center-div .foot .foot-det').css({'background-color':'#fff'});
						$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
						
						$('.counter-center .body').perfectScrollbar('update');
						$(".counter-center .body").scrollTop(0);
					},'json').fail( function(xhr, textStatus, errorThrown) {
							   alert(xhr.responseText);
							});	
					// alert(data);
					// });
				// }
			}else{
				// if($("#trans-server-txt").text() == ""){
				// 	rMsg('Select Food Server','error');
				// }
				// else{
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/0/null/'+doPrintOS,function(data){
						if(data.error != null){
							rMsg(data.error,'error');
							btn.prop('disabled', false);
						}
						else{
							if(data.act == 'add'){
								newTransaction(false,data.type);
								if(btn.attr('id') == 'send-trans-btn'){
									rMsg('Success! Transaction Submitted.','success');
								}
								else{
									rMsg('Transaction Hold.','warning');
								}
								btn.prop('disabled', false);
							}
							else{
								newTransaction(true,data.type);
							}
						}

						$("#zero-rated-btn").removeClass('counter-btn-green');
						$("#zero-rated-btn").removeClass('zero-rated-active');
						$("#zero-rated-btn").addClass('counter-btn-red');
						$('.center-div .foot .foot-det').css({'background-color':'#fff'});
						$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
						
						$('.counter-center .body').perfectScrollbar('update');
						$(".counter-center .body").scrollTop(0);
					},'json').fail( function(xhr, textStatus, errorThrown) {
							   alert(xhr.responseText);
							});	
					// alert(data);
					// });
				// }		
				
			}
			return false;	
		});
		$('#print-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i> <br>Billing');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i> <br>Billing');
			}
		});
		$('#print-os-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i><br>ORDER SLIP');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i><br>ORDER SLIP');
			}
		});
		$('#hold-all-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans',function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					if(data.act == 'add'){
						newTransaction(false,data.type);
						if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
					}
					else{
						newTransaction(true,data.type);
					}
				}
			},'json');
			return false;
		});
		$('#rhold-all-btn').click(function(){
			// alert('haha');
			// return false;
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/rsubmit_trans',function(data){
				// console.log(data);
				// return false;
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					if(data.js_rcp){
						$('#print-rcp').html(data.js_rcp);
					}

					if(data.act == 'add'){
						newTransaction(false,data.type);
						if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
					}
					else{
						newTransaction(true,data.type);
					}
				}
			// });
			},'json');
			return false;
		});
		$('#settle-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-settle');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				btn.prop('disabled', true);

				<?php if(REQUIRE_CUSTOMER){ ?>

					swal({
					  title: "",
					  text: "",
					  type: "input",
					  showCancelButton: true,
					  closeOnConfirm: true,
					  inputPlaceholder: "Customer Name"
					}, function (inputValue) {
					  if (inputValue === false){$('#settle-btn').removeAttr('disabled'); return false;} 
					  if (inputValue === "") {
					    swal.showInputError("Customer name required.");
					    return false
					  } 

					  var formData = 'customer_name='+inputValue;

						  $.post(baseUrl+'cashier/customer_name_add',formData,function(data){
							// alert(nocharge);
								console.log(data);
								$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
									console.log(data);
										if(data.error != null){
											rMsg(data.error,'error');
											btn.prop('disabled', false);
										}
										else{
											// newTransaction(false);
											if(btn.attr('id') == 'settle-btn'){
												rMsg('Success! Transaction Submitted.','success');
											}
											else{
												rMsg('Transaction Hold.','warning');
											}
											btn.prop('disabled', false);
											window.location = baseUrl+'cashier/settle/'+data.id;
										}
								},'json').fail( function(xhr, textStatus, errorThrown) {
										alert(xhr.responseText);
								});	
							});
						// },'json');
					});
				<?php }else{ ?>
					// console.log('dataaa');
					$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
									console.log(data);
										if(data.error != null){
											rMsg(data.error,'error');
											btn.prop('disabled', false);
										}
										else{
											// newTransaction(false);
											if(btn.attr('id') == 'settle-btn'){
												rMsg('Success! Transaction Submitted.','success');
											}
											else{
												rMsg('Transaction Hold.','warning');
											}
											btn.prop('disabled', false);
											formData = 'txt='+data.id;
							       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
							       				// alert(data);
							       				// console.log(data);
												window.location = baseUrl+'cashier/settle/'+data;
							       			});
											// window.location = baseUrl+'cashier/settle/'+data.id;
										}
								},'json').fail( function(xhr, textStatus, errorThrown) {
										alert(xhr.responseText);
								});	

				<?php } ?>
				// alert(data);
				// });
			}
			return false;
		});
		$('#cash-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-cash');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						newTransaction(false);
						if(btn.attr('id') == 'cash-btn'){
							//rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
						window.location = baseUrl+'cashier/settle/'+data.id+'#cash';
					}
				},'json').fail( function(xhr, textStatus, errorThrown) {
						   alert(xhr.responseText);
						});	
			}
			return false;
		});
		$('#credit-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-credit');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				var btn = $(this);
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						newTransaction(false);
						if(btn.attr('id') == 'credit-btn'){
							//rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
						window.location = baseUrl+'cashier/settle/'+data.id+'#credit';
					}
				},'json').fail( function(xhr, textStatus, errorThrown) {
						   alert(xhr.responseText);
						});	
			}
			return false;
		});
		// split sa loob ng transaction
		$("#splitin-btn").click(function(){
			var id = $(this).attr('aid');
			var type = $(this).attr('atype');
			
			window.location = baseUrl+'cashier/split/'+type+'/'+id;
			// alert(table_id);
			return false;
		});
		$('#waiter-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			// loadsDiv('waiter',null,null,null);
			// loadWaiters();
			$.callFS({
				success : function(emp){
								if(emp['emp_id']){
									$.post(baseUrl+'cashier/update_trans/waiter_id/'+emp['emp_id'],function(data){
										$('#trans-server-txt').text('FS: '+emp['emp_username']).show();
										rMsg(emp['emp_username']+' added as Food Server','success');
									},'json');
								}
						  }
			});



			return false;
		});
		$('#remove-waiter-btn').click(function(){
			$.post(baseUrl+'cashier/update_trans/waiter_id/null/true',function(data){
				$('#trans-server-txt').text('').hide();
				rMsg('Food Server Removed.','success');
			},'json');
			return false;
		});
		$('#loyalty-btn').click(function(){
			// var sel = $('.selected');
			// if(sel.exists()){
			// 	if(sel.hasClass('loaded')){
			// 		$.callManager({
			// 			success : function(){
			// 				loadsDiv('qty',null,null,null);
			// 			}	
			// 		});		
			// 	}
			// 	else	
			loadsDiv('loyalty',null,null,null);
			// }
			return false;
		});
		///FOR RETAIL
			$('#retail-btn').click(function(){
				if($(this).hasClass('counter-btn-red')){
					$(this).removeClass('counter-btn-red');
					$(this).addClass('counter-btn-green');
					loadItemCategories();
					loadsDiv('retail');
					$('#scan-code').focus();
					$('#go-scan-code').removeClass('counter-btn-orange');
					$('#go-scan-code').addClass('counter-btn-green');
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-red');
					loadMenuCategories();
					var cat_id = $(".category-btns:first").attr('ref');
					var cat_name = $(".category-btns:first").text();
					var val = {'name':cat_name};
					loadsDiv('menus',cat_id,val,null);
					$('#go-scan-code').removeClass('counter-btn-green');
					$('#go-scan-code').addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#go-scan-code').click(function(){
				if($(this).hasClass('counter-btn-orange')){
					$(this).removeClass('counter-btn-orange');
					$(this).addClass('counter-btn-green');
					$('#scan-code').focus();
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#scan-code').on('keyup',function(e){
				if(e.keyCode == 13){
					var code = $(this).val();
					if(code != ""){
						$.post(baseUrl+'cashier/scan_code/'+code,function(data){
							if(data.error == ""){
								var opt = data.item;
								addRetailTransCart(opt.item_id,opt);
								// $.beep();
							}
							else{
								rMsg(data.error,'error');
								// $.beep({'status':'error'});
								
							}
						},'json');
					} 
					else{
						rMsg('Item not found.','error');
						// $.beep({'status':'error'});
					    
					}
					$('#scan-code').val('');
				}
			});
			$('#go-search-item').click(function(){
				var btn = $(this);
				var search = $('#search-item').val();
				if(search != ""){
					var formData = 'search='+search;
					loadRetailItemList(formData,btn);
				}
				else{
					rMsg('Nothing to search.','error');
				}
				return false;
			});
			$('#customer-btn').click(function(){
				var btn = $(this);
				loadsDiv('customers',null,null,null);
				return false;
			});
			$('#remove-customer').click(function(){
				$.post(baseUrl+'cashier/update_trans_customer/',function(data){
					$('#trans-customer').text('').hide();
					rMsg('REMOVED Customer ID','success');
				});
			});
			$('#go-search-customer').click(function(){
				var btn = $(this);
				var search = $('#search-customer').val();
				if(search != ""){
					var formData = 'search='+search;
					loadRetailCustomerList(formData,btn);
				}
				else{
					rMsg('Nothing to search.','error');
				}
				return false;
			});
		$('#qty-btn').click(function(){
			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			var sel = $('.selected');
			if(sel.exists()){
				if(sel.hasClass('loaded')){
					$.callManager({
						success : function(){
							loadsDiv('qty',null,null,null);
						}	
					});		
				}
				else	
					loadsDiv('qty',null,null,null);
			}
			return false;
		});
		$('#qty-btn-cancel,#qty-btn-done').click(function(){
			// if($('#retail-btn').hasClass('counter-btn-red')){
				$('.loads-div').hide();				
				$('.menus-div').show();
			// }
			// else{
			// 	remeload('retail');
			// }
			return false;
		});
		$(".edit-qty-btn").click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var formData = 'value='+btn.attr('value')+'&operator='+btn.attr('operator');
			btn.prop('disabled', true);

			check_inv = check_menu_qty(id,btn.attr('value'),btn.attr('operator'));
			
			if(check_inv == true){
				alert('Input quantity exceed available quantity');
				btn.prop('disabled', false);
				return false;
			}

			$.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
				var qty = data.qty;
				var cost = data.cost;
				$('#trans-row-'+id+' .qty').text(qty);

				if(qty > 1){
					total_cost = cost * qty;
					$('#trans-row-'+id+' .cost').text(total_cost);
					$('#trans-row-'+id+' .price').text('@'+cost);
				}else{
					$('#trans-row-'+id+' .price').text('');
					$('#trans-row-'+id+' .cost').text(data.cost);
				}
				btn.prop('disabled', false);

				// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
												
				// 								transTotal();
				// 							});

				transTotal();
			},'json');
			
			return false;
		});

		$("#resetqty-btn").click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var formData = 'value='+btn.attr('value')+'&operator='+btn.attr('operator');
			btn.prop('disabled', true);

			check_inv = check_menu_qty(id,btn.attr('value'),btn.attr('operator'));
			
			if(check_inv == true){
				alert('Input quantity exceed available quantity');
				btn.prop('disabled', false);
				return false;
			}

			$.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
				var qty = data.qty;
				var cost = data.cost;
				$('#trans-row-'+id+' .qty').text(qty);

				if(qty > 1){
					total_cost = cost * qty;
					$('#trans-row-'+id+' .cost').text(total_cost);
					$('#trans-row-'+id+' .price').text('@'+cost);
				}else{
					$('#trans-row-'+id+' .price').text('');
					$('#trans-row-'+id+' .cost').text(data.cost);
				}
				btn.prop('disabled', false);

				// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
												
				// 								transTotal();
				// 							});
				$.post(baseUrl+'cashier/delete_promo/'+id,function(promo_data){
						if(promo_data != -1){
							$.post(baseUrl+'wagon/delete_to_wagon/trans_cart/'+promo_data,function(data){
								$('#trans-row-'+promo_data).remove();
							},'json');	

							addTransPromo();														
						}
				});

				transTotal();
			},'json');
			
			return false;
		});

		function check_menu_qty(trans_id,qty,operator){
			var tot_qty = 0;
			var menu_qty = 0;
			var menu_id = 0;
			var tbl_menu_qty = 0;

			$.ajaxSetup({async: false});
			$.post(baseUrl + 'cashier/count_trans_menu/',{'trans_id':trans_id,'value':qty,'operator':operator},function(data){
				tot_qty =  data.qty;
				menu_id = data.menu_id;
				tbl_menu_qty = data.menu_qty;
				menu_qty= $('#menu-'+menu_id).attr('qty');
			},'json');

			
			if(tbl_menu_qty == '' || tbl_menu_qty == null){
				return false;
			}
			else if(tot_qty > menu_qty){
				return true;
			}else{
				$('#menuqty-'+menu_id).text(menu_qty - tot_qty);

				if(menu_qty-tot_qty == 0){
					$('#menu-'+menu_id).prop('disabled',true);
				}
			}

			return false;
		}

		$("#multiply-items").click(function(event){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var val = $('#times-qty').val();
			if(val == '' || val == ' '){
				rMsg('Please input a number.','error');
			}else if(val < 0){
				rMsg('Quantity should be greater than 0','error');
				$('#times-qty').val('');
			}else{

				check_inv = check_menu_qty(id,val,btn.attr('operator'));
			
				if(check_inv == true){
					alert('Input quantity exceed available quantity');
					btn.prop('disabled', false);
					return false;
				}

				// var formData = 'value='+val+'&operator='+btn.attr('operator');
				var formData = 'value='+val+'&operator=equal';
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
					var qty = data.qty;
					$('#trans-row-'+id+' .qty').text(qty);
					// if(data.qty == 1){
					// 	$('#trans-row-'+id+' .price').text('');
					// 	$('#trans-row-'+id+' .cost').text(data.price);
					// }else{
					// 	$('#trans-row-'+id+' .price').text('@'+data.price);
					// 	$('#trans-row-'+id+' .cost').text(data.subtotal);
					// }

					btn.prop('disabled', false);

					// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
												
					// 							transTotal();
					// });

					$.post(baseUrl+'cashier/promo_item_qty/'+id+'/'+$('#ttype').val(),function(promo_data){
							// alert(id);
							//  alert(promo_data.id);
							if(promo_data.ref_line != undefined){ 
								$('#trans-row-'+promo_data.ref_line+' .qty').text(promo_data.ref_qty);
								makeItemRow(promo_data.id,0,promo_data.items,'promo',is_takeout,'');
							}else if(promo_data.qty != undefined){
								$('#trans-row-'+promo_data.id+' .qty').text(promo_data.qty);
								$('#trans-row-'+promo_data.id).addClass('promo');
							}else if(id != promo_data.id){  
								makeItemRow(promo_data.id,promo_data.free_menu,promo_data.items,'promo',is_takeout,'');
							}
							
						},'json');
					// addTransPromo();
					
					transTotal();
					$('#times-qty').val('');
					$('.loads-div').hide();				
					$('.menus-div').show();
				},'json');
			}
			// $('#qty-btn-done').click();
			return false;
		});
		$("#save-price").click(function(event){
			// alert('aw');
			// var sel = $('.selected');
			var btn = $(this);
			// var id = sel.attr("ref");
			var menu_id = $("#menu-id-hidden").val();
			var menu_cat_id = $("#menu-cat-id-hidden").val();
			var menu_cat_name = $("#menu-cat-name-hidden").val();
			var val = $('#menu-price').val();

			if(val == '' || val == ' '){
				rMsg('Please input a number.','error');
			}
			// else if(val < 0 || val == 0){
			// 	rMsg('Amount should be greater than 0','error');
			// 	$('#menu-price').val('');
			// }
			else{

				$.post(baseUrl+'cashier/get_menus_sorted_orig/'+menu_cat_id+'/'+menu_id,function(data){
					// var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		
					// console.log(data);
					// alert('aw');
			 		$.each(data,function(key,opt){
			 			addTransCart_price(menu_id,opt,val,btn);
			 		});

			 	},'json');
			 	// });
				$('#menu-price').val('');
				var obj = {
				        	'name':menu_cat_name
					    };
				loadsDiv('menus',menu_cat_id,obj,null);

				// alert(menu_id);
				// alert(menu_cat_id);
				// var formData = 'value='+val+'&operator='+btn.attr('operator');
				// btn.prop('disabled', true);
				// $.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
				// 	var qty = data.qty;
				// 	$('#trans-row-'+id+' .qty').text(qty);
				// 	// if(data.qty == 1){
				// 	// 	$('#trans-row-'+id+' .price').text('');
				// 	// 	$('#trans-row-'+id+' .cost').text(data.price);
				// 	// }else{
				// 	// 	$('#trans-row-'+id+' .price').text('@'+data.price);
				// 	// 	$('#trans-row-'+id+' .cost').text(data.subtotal);
				// 	// }

				// 	btn.prop('disabled', false);
				// 	transTotal();
				// 	$('#times-qty').val('');
				// },'json');
			}
			// $('#qty-btn-done').click();
			return false;
		});
		$("#zero-rated-btn").click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var btn = $(this);


			$.callManager({
				addData : 'ZeroRated',
				success : function(manager){
					loadsDiv('zero',null,null,null);
					// var zero_name = $("#zero-cust-name").val();
					// alert(zero_name);
							// var man_user = manager.manager_username;
							// var man_id = manager.manager_id;
							// if(!btn.hasClass('zero-rated-active')){
							// 	$.post(baseUrl+'cashier/update_trans/zero_rated/1',function(data){
							// 		btn.removeClass('counter-btn-red');
							// 		btn.addClass('counter-btn-green');
							// 		btn.addClass('zero-rated-active');
							// 		$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
							// 		$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});
							// 		transTotal();
							// 	});
							// }
							// else{
							// 	$.post(baseUrl+'cashier/update_trans/zero_rated/0',function(data){
							// 		btn.removeClass('counter-btn-green');
							// 		btn.removeClass('zero-rated-active');
							// 		btn.addClass('counter-btn-red');
							// 		$('.center-div .foot .foot-det').css({'background-color':'#fff'});
							// 		$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
							// 		transTotal();
							// 	});
							// }
				}
			});		
			return false;
		});
		$("#prcss-zero").click(function(){
			var btn = $('#zero-rated-btn');
			var zero_name = $("#zero-cust-name").val();
			// alert(zero_name);	

			var formData = $('#zero-form').serialize();

			var cust_name = $('#zero-cust-name').val();
			var card_num = $('#zero-cust-code').val();
			if(card_num == ""){
				rMsg('Card Number cannot be empty.','error');
				return false;
			}
			if(cust_name == ""){
				rMsg('Customer name cannot be empty.','error');
				return false;
			}


			if(!btn.hasClass('zero-rated-active')){
				$.post(baseUrl+'cashier/update_trans/zero_rated/1',formData,function(data){
					// alert(data);
					btn.removeClass('counter-btn-red');
					btn.addClass('counter-btn-green');
					btn.addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});
					transTotal();
				});
			}
			else{
				$.post(baseUrl+'cashier/update_trans/zero_rated/0',formData,function(data){
					btn.removeClass('counter-btn-green');
					btn.removeClass('zero-rated-active');
					btn.addClass('counter-btn-red');
					$('.center-div .foot .foot-det').css({'background-color':'#fff'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
					transTotal();
				});
			}
			return false;
		});

		$('#selbrand-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}

			$.rPopForm({
				loadUrl : 'cashier/select_brands',
				passTo	: 'cashier/update_brand_sess',
				// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
				title	: '<font size="4">Select Brand</font>',
				noButton: 1,
				// wide	: 1,
				hide	: 1,
				rform 	: 'brand-form',
				onComplete : function(data){
					// alert(data);
					// $('.brandname-div').html('');
					$('.bname').text(data);
					loadMenuCategories();
				}
			});
		});

		loadBrand();

		$(document).on('click','.brand-btns',function(){
        	$('.brand-btns').attr('class','brand-btns btn-block cpanel-btn-orange btn btn-success');
        	brand = $(this).attr('brand');

        	// alert(brand);
        	$('#hid-brand').val(brand);

        	$(this).attr('class','brand-btns btn-block cpanel-btn-orange btn btn-danger');

        	var formData = $('#brand-form').serialize();
        	$.post(baseUrl+'cashier/update_brand_sess',formData,function(data){
				$('.bname').text(data);
				$('#menu_categories_page').val(1);
				$('#menu_page').val(1);
				$('#subcat_menu_page').val(1);
				loadMenuCategories();

				$('.close').click();
				loadBrand();
			});
        	
        });

        function loadBrand(){

			$('#load-img').html('');
			// page = $('#page_value').val();
			// page = $('#page_value').val();
			// formData = 'brand='+brand;
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/load_brands',function(data){
		 		// alert(data.error);return false;
		 		// alert(data);
				var sCol = $('#load-img');	
				$('.bname').text(data.brand);
				$('<img src='+baseUrl+data.brand_img+' width="100%" height="55px;">').appendTo(sCol);
	 		},'json');
		 	// },'json');
				

		}

		// $('.brand-btns').click(function(){
  //       	brand = $(this).attr('brand');
  //       	// alert(brand)
  //       	formData = 'brand='+brand;
  //       	$.post(baseUrl+'cashier/update_brand_sess',formData,function(data){
  //       		// alert(data);
  //       		loadMenuCategories();

		       

		// 	});

  //       });

		$('#add-discount-choose-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}

			loadsDiv('choosedisc',null,null,null);
			return false;
		});
		$('#add-discount-btn').click(function(){

			//check if may line discount na
			formData = 'button=alldisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){
				// alert(data);
				if(data.error != ""){
					rMsg(data.error,'error');
				}else{

					$.callManager({
						addData : 'Discount',
	 					success : function(){
	 						
							$.rPopForm({
								loadUrl : 'discount/discount_pop',
								passTo	: '',
								// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
								title	: '<font size="4">Discount Form</font>',
								noButton: 1,
								wide	: 1,
								// rform 	: 'guide_form',
								onComplete : function(){
												
											 }
							});
							
	 					}	
 					});

				}
						
			},'json');
			// });


			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			// loadsDiv('sel-discount',null,null,null);
			// loadDiscounts();
			return false;
		});
		$('#add-discount-line-btn').click(function(){
			// $.rPopForm({
			// 	loadUrl : 'discount/discount_pop',
			// 	passTo	: '',
			// 	// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
			// 	title	: '<font size="4">Discount Form</font>',
			// 	noButton: 1,
			// 	wide	: 1,
			// 	// rform 	: 'guide_form',
			// 	onComplete : function(){
								
			// 				 }
			// });

			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			formData = 'button=linedisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){

				if(data.error != ""){
					rMsg(data.error,'error');
				}else{
					loadsDiv('sel-discount',null,null,null);
					loadDiscounts();
				}
						
			},'json');


			return false;
		});
		$('#no-discount-btn').click(function(){
			rMsg('Adding Discount is not Allowed','error');
			return false;
		});
		$('#add-disc-person-btn').click(function(){
			// $('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				// var ref = $(this).attr('ref');
				var ref = $('#prcss-disc').attr('disc-code');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

					var disc_code = $('#disc-disc-code').val();
					if(disc_code == "SNDISC" || disc_code == "PWDISC"){
						var disc_cust_code = $('#disc-cust-code').val();
						console.log(disc_cust_code.length);
						if(disc_cust_code == ""){
							rMsg('Card Number cannot be empty','error');
							return false;
						}
					}
				$.post(baseUrl+'cashier/add_person_disc_line',formData,function(data){

						$('#add-disc-person-btn').goLoad({load:false});
						if(data.error==""){
							$('.disc-persons-list-div').html(data.code);
							$.each(data.items,function(code,opt){
								$("#disc-person-rem-"+code).click(function(){
									var lin = $(this).parent().parent();
									$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
										lin.remove();
										rMsg('Person Removed.','success');
										transTotal();
									});
									return false;
								});
							});
							transTotal();
						}
						else{
							rMsg(data.error,'error');
						}
				},'json');
			}
			return false;
		});
		$('#add-disc-person-line-btn').click(function(){
			// $('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				// alert(guests);
				// var ref = $(this).attr('ref');
				var ref = $('#prcss-disc-line').attr('disc-code');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

					var disc_code = $('#disc-disc-code').val();
					if(disc_code == "SNDISC" || disc_code == "PWDISC"){
						var disc_cust_code = $('#disc-cust-code').val();
						console.log(disc_cust_code.length);
						if(disc_cust_code == ""){
							rMsg('Card Number cannot be empty','error');
							return false;
						}
					}
				$.post(baseUrl+'cashier/add_person_disc_line',formData,function(data){
					// alert(data);
						$('#add-disc-person-btn').goLoad({load:false});
						if(data.error==""){
							$('.disc-persons-list-div').html(data.code);
							$.each(data.items,function(code,opt){
								$("#disc-person-rem-"+code).click(function(){
									var lin = $(this).parent().parent();
									$.post(baseUrl+'cashier/remove_person_disc_line/'+opt.disc+'/'+code,function(data){
										lin.remove();
										rMsg('Person Removed.','success');
										
										$.each(data.affeted_row,function(code,opt){

											// txt = $('#trans-row-'+code+' .cost').text();

											// new_amt = Number(txt) + Number(opt.disc_amt);
											$('#trans-row-'+code+' .cost').text(opt.def_amt);
										});


										transTotal();
									// });
									},'json');
									return false;
								});
								$('#disc-person-'+code).click(function(){
									$('.disc-person').removeClass('selected');
									code = $(this).attr('code');
									sname = $(this).attr('sname');
									remarks = $(this).attr('remarks');
									// bday = $(this).attr('bday');

									$(this).addClass('selected');

									formData = 'code='+code+'&sname='+sname+'&remarks='+remarks;
									$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
										// transTotal();
									});
								});
							});
							// transTotal();
						}
						else{
							rMsg(data.error,'error');
						}
				},'json');
				// });
			}
			return false;
		});
		$('#disc-cust-remarks').click(function(){
			$.callDiscReasons({
				submit : function(reason){
					// console.log(reason);
					var free_reason = reason;
					// if(free_reason == ""){
					// 	rMsg('Please select/input a reason','error');
					// 	return false;
					// }else{
						var formData = 'free_reason='+free_reason;

						// $('body').goLoad2();
						$('#disc-cust-remarks').val(free_reason);
						// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
						// 	// console.log(data);
						// 	$('#trans-row-'+id+' .cost').text(0);
						// 	var text = $('#trans-row-'+id).find('.name').text();
						// 	// alert(text);
						// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text + " <br>&nbsp;&nbsp;&nbsp;<span class='label label-sm label-warning'>"+free_reason+"</span>");
						// 	transTotal();
						// 	rMsg('Updated Menu as free','success');
						// 	$('body').goLoad2({load:false});
						// });
					// }
					
					// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
					// 	// alert(data);
					// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
					// 		sel.prev().addClass('selected');
					// 		sel.remove();
					// 		if(cart == 'trans_cart' && retail === false){
					// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
					// 				var cat_id = $(".category-btns:first").attr('ref');
					// 				var cat_name = $(".category-btns:first").text();
					// 				var val = {'name':cat_name};
					// 				loadsDiv('menus',cat_id,val,null);
					// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
					// 			});
					// 		}
					// 		$('.counter-center .body').perfectScrollbar('update');
					// 		transTotal();
					// 	},'json');
					// });

				}
			});
		});
		$('.disc-btn-row').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#edit-order-guest-no').click(function(){
			$.callEditGuests({
				success : function(guest){
					$('#ord-guest-no').text(guest);	
					$('#disc-guests').val(guest);	
					rMsg('Guest has been updated to'+guest,'success');
				}
			});
			return false;
		});
		$('#prcss-disc').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			console.log(ref);
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				$('#ord-guest-no').text(guests);
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#prcss-disc-line').click(function(){
			// alert('ss');
			var sel_p = $('.disc-person');
			var disc_id = $(this).attr('disc-id');
			var disc_code = $(this).attr('disc-code');
			// var disc_remarks = "";

			// if(sel_p.hasClass('selected')){
			var select = false;
			// if(disc_id == 1 || disc_id == 2){
				if(sel_p.hasClass('selected')){
					select = true;
				}
			// }else{
			// 	select = true;
			// }
			// alert('aw');
			$('.disc-person').each(function(){
				if($(this).hasClass('selected')){
					// select = true;
					remarks = ($(this).attr('remarks'));
					// var disc_remarks = $(this).attr('remarks');
				}
			});
			// alert('ew');


			// alert(remarks);

			if(select == true){

				var sel = $('.selected');
				var btn = $(this);
				var id = sel.attr("ref");

				if(id){

					
					var formData = 'disc_id='+disc_id+'&disc_code='+disc_code+'&disc_remarks='+remarks;
					// var formData = 'disc_id='+disc_id+'&disc_code='+disc_code;
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/discount_item/'+id,formData,function(data){
						// alert(data);
						// console.log(data);
						rMsg('Discount has been added','success');
						// alert(data);
						// console.log(data);
						// var qty = data.qty;
						// var cost = data.cost;
						// $('#trans-row-'+id+' .qty').text(qty);
						// if(data.qty == 1){
						// 	$('#trans-row-'+id+' .price').text('');
						// 	$('#trans-row-'+id+' .cost').text(data.cost);
						// }else{
						// 	total_cost = cost * qty;
						// 	$('#trans-row-'+id+' .price').text('@'+cost);
						$('#trans-row-'+id+' .cost').text(data.discounted_amt);
						// }

						btn.prop('disabled', false);
						transTotal();
						// $('#times-qty').val('');
					// });
					},'json');

				}else{
					rMsg('Please select an item','error');
				}
			
			}else{
				rMsg('Please select a person','error');

			}
			// $('#qty-btn-done').click();
			return false;
		});
		$('#remove-disc-btn').click(function(){
			var disc_code = $('#disc-disc-code').val();
			$.post(baseUrl+'cashier/del_trans_disc/'+disc_code,function(data){
				rMsg('Discounts Removed','success');
				$('.disc-person').remove();
				$('#disc-form')[0].reset();
				$('#disc-guests').val('');
				transTotal();
			});
			return false;
		});
		$('#remove-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}


			var sel = $('.selected');
			// alert('aw');
			if(sel.exists()){

				if(sel.hasAttr('is_def')){
					rMsg('Cannot be remove.','error');
				}else{
					// alert('ww');
					if(sel.hasClass('update')){
						rMsg('Cannot be remove.','error');
					}else{
						if(sel.hasClass('loaded')){
							
							var id = sel.attr('ref');
							var cart = 'trans_cart';
							var type = 'menu';
							
							if(sel.hasClass('trans-sub-row')){
								cart = 'trans_mod_cart';
								type = 'mod';
								sales_mod_id = sel.attr('sales-mod-id');
							}
							else if(sel.hasClass('trans-charge-row')){
								cart = 'trans_charge_cart';
								type = 'charge';
							}
							else if(sel.hasClass('trans-subsub-row')){
								cart = 'trans_submod_cart';
								type = 'submod';
							}

							// alert(cart);
							var retail = false;
							if(sel.hasClass('retail-item')){
								retail = true;
								type = 'retail';
							}

							if(type != 'menu' && type != 'retail'){
								rMsg('Please remove the menu.','error');
							}else{

								$.callManager({
									success : function(manager){
										var man_user = manager.manager_username;
										var man_id = manager.manager_id;
										$.callReasons({
											submit : function(reason){
												var id = sel.attr('ref');
												var cart = 'trans_cart';
												var type = 'menu';
												
												if(sel.hasClass('trans-sub-row')){
													cart = 'trans_mod_cart';
													type = 'mod';
													sales_mod_id = sel.attr('sales-mod-id');
												}
												else if(sel.hasClass('trans-charge-row')){
													cart = 'trans_charge_cart';
													type = 'charge';
												}
												else if(sel.hasClass('trans-subsub-row')){
													cart = 'trans_submod_cart';
													type = 'submod';
												}

												// alert(cart);
												var retail = false;
												if(sel.hasClass('retail-item')){
													retail = true;
													type = 'retail';
												}

												// if(type != 'menu' && type != 'retail'){
												// 	rMsg('Please remove the menu.','error');
												// }else{
												$.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
													// alert(data);
													$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
														sel.prev().addClass('selected');
														sel.remove();
														if(cart == 'trans_cart' && retail === false){
															$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
																var cat_id = $(".category-btns:first").attr('ref');
																var cat_name = $(".category-btns:first").text();
																var val = {'name':cat_name};
																loadsDiv('menus',cat_id,val,null);
																$('.trans-sub-row[trans-id="'+id+'"]').remove();
																$('.trans-subsub-row[trans-id="'+id+'"]').remove();
															});
														}
														else if(cart == 'trans_mod_cart' && retail === false){

															$.post(baseUrl+'cashier/delete_trans_menu_submodifier/'+sales_mod_id,function(data){
																//remove submo
																// alert(data);
																var cat_id = $(".category-btns:first").attr('ref');
																var cat_name = $(".category-btns:first").text();
																var val = {'name':cat_name};
																loadsDiv('menus',cat_id,val,null);
																// $('.trans-sub-row[trans-id="'+id+'"]').remove();
																$('.trans-subsub-row[trans-mod-id="'+id+'"]').remove();
															});

															$('#trans-row-'+id).click();
														}
														$('.counter-center .body').perfectScrollbar('update');
														

														// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
														// 	transTotal();
														// });

														transTotal();
													},'json');
													
													$.post(baseUrl+'cashier/delete_promo/'+id,function(promo_data){
															if(promo_data != -1){
																$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+promo_data,function(data){
																	$('#trans-row-'+promo_data).remove();
																},'json');	

																addTransPromo();														
															}
													});
												});
													
												// }

												

											}
										});
									}
								});

							}
						}
						else{
							var id = sel.attr('ref');
							var cart = 'trans_cart';
							if(sel.hasClass('trans-sub-row'))
								cart = 'trans_mod_cart';
							else if(sel.hasClass('trans-charge-row'))
								cart = 'trans_charge_cart';
							else if(sel.hasClass('trans-subsub-row'))
								cart = 'trans_submod_cart';
							var retail = false;
							if(sel.hasClass('retail-item'))
								retail = true;
							if(!sel.hasClass('trans-remarks-row')){
								// alert(2);
								$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
									var del_item = data.items;
									var cat_name = sel.attr('cat_name');
									var cat_id = sel.attr('cat_id');
									var mod_name = sel.attr('mod_name');
									var mandatory = sel.attr('mandatory');
									var mod_group_id = sel.attr('mod_group_id');
									var selected_type = sel.attr('class');
									var mod_min_no = sel.attr('mod_min_no');
									var mod_used = $('li[mod_group_id='+mod_group_id+']').length;
									var searched = selected_type.search("trans-row");
									if(searched >= "0"){
										$('.mods-mandatory').remove();
										$('.mod-group-name').remove();
									}
									if(mandatory == "1" && mod_used <= mod_min_no){
										var value_mod =  mod_min_no-mod_used;
										// alert(value_mod);
										$('#mods-mandatory-'+mod_group_id).remove();
										$('#mod-group-name-'+mod_group_id).remove();
										$('<input/>').attr({
											'type':'hidden',
											'value':mod_name,
											'id':'mod-group-name-'+mod_group_id,
											'class':'mod-group-name'
										}).appendTo('.mods-div .mods-lists');
										$('<input/>').attr({
											'type':'hidden',
											'value':value_mod+1,
											'id':'mods-mandatory-'+mod_group_id,
											'class':'mods-mandatory'
										}).appendTo('.mods-div .mods-lists');
									}
									sel.prev().addClass('selected');
									sel.remove();
									if(cart == 'trans_cart' && retail === false){
										// alert('sss');
										$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
											// alert(data);
											// var cat_id = $(".category-btns:first").attr('ref');
											// var cat_id = data.cat_id;
											// var cat_name = $(".category-btns:first").text();
											// var cat_name = data.cat_name;
											var val = {'name':cat_name};
											loadsDiv('menus',cat_id,val,null);
											$('.trans-sub-row[trans-id="'+id+'"]').remove();
											$('.trans-subsub-row[trans-id="'+id+'"]').remove();

											$('.mods-mandatory').remove();
											$('.mod-group-name').remove();

										});
										// },'json');
									}
									else if(cart == 'trans_mod_cart' && retail === false){

										$.post(baseUrl+'cashier/delete_trans_menu_submodifier2/'+id,function(data){
											//remove submo
											// alert(data);
											// console.log(data);
											var cat_id = $(".category-btns:first").attr('ref');
											var cat_name = $(".category-btns:first").text();
											var val = {'name':cat_name};
											// loadsDiv('menus',cat_id,val,null);
											// $('.trans-sub-row[trans-id="'+id+'"]').remove();
											$('.trans-subsub-row[trans-mod-id="'+id+'"]').remove();

											// $('.mods-mandatory').remove();
											// $('.mod-group-name').remove();
										});
										var t_id = sel.attr('trans-id');
										$('#trans-row-'+t_id).click();
									}
									else if(cart == 'trans_submod_cart' && retail === false){
										var t_id = sel.attr('trans-id');
										var tm_id = sel.attr('trans-mod-id');
										$('#trans-sub-row-'+t_id+'-'+tm_id).click();
									}
									
									$('.counter-center .body').perfectScrollbar('update');

									// $.ajaxSetup({async: false});
									// $.post(baseUrl+'cashier/discount_item_qty/-1/'+del_item.menu_id,function(data){
									// 					transTotal();
									// 				});
									transTotal();
								},'json');

								$.post(baseUrl+'cashier/delete_promo/'+id,function(promo_data){
										if(promo_data != -1){
											$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+promo_data,function(data){
												$('#trans-row-'+promo_data).remove();
											},'json');

											addTransPromo();
										}
								});
							}
							else{
								$.post(baseUrl+'cashier/remove_trans_remark/'+id,function(data){
									sel.prev().addClass('selected');
									$('#trans-remarks-row-'+id).remove();
									$('.counter-center .body').perfectScrollbar('update');
								// });
								},'json');
							}


							if(cart == 'trans_mod_cart'){
								$('.mods-div .mods-lists').html('');
								var mod_grp_id = sel.attr('mod_group_id');
								var mod_name = sel.attr('mod_name');
								var trans_id = sel.attr('trans-id');
								type = 'mods';
								// alert(mod_name+'---'+mod_group_id);
								$.post(baseUrl+'cashier/get_modifiers/'+mod_grp_id+'/'+trans_id,function(data){
									// alert(data);

									var modGRP = data.group;

									if(!$.isEmptyObject(modGRP)){
										// alert('ss');
										modgrp_cnt = 0;
										$.each(modGRP,function(mod_group_id,opt){
											modgrp_cnt++;
										});

										$('.loads-div').hide();
										$('.'+type+'-div').show();
										// alert(opt);
										// var mandatory_div = mandatory;
										seq = 1;
										$.each(modGRP,function(mod_group_id,opt){
										// alert(nicko_test);
											// if(seq == 1){
												style = '';
											// }else{
											// 	style = 'display:none;';
											// }

											// alert('a');
											var row = $('<div/>').attr({'class':'mod-group mod-grp-'+seq,'id':'mod-group-'+mod_group_id,'style':style}).appendTo('.mods-div .mods-lists');
											
											// default_count = 0;
											// total_mod = 0;
											// $.each(opt.details,function(mod_id,det){
											// 	total_mod++;
											// 	if(det.default == 1){
											// 		default_count++;
											// 	}
											// });

											// alert(default_count);
											// if(other != 'back_sub'){
												// alert(opt.mandatory);
											// alert(opt.mandatory + '---' + opt.name);
												// if(opt.mandatory == "1" && mandatory_div == "1"){
												// 	$('<input/>').attr({
												// 		'type':'hidden',
												// 		'value':opt.name,
												// 		'id':'mod-group-name-'+mod_group_id,
												// 		'class':'mod-group-name'
												// 	}).appendTo('#mods_mandatory_div');
												// 	$('<input/>').attr({
												// 		'type':'hidden',
												// 		'value':opt.min_no,
												// 		'id':'mods-mandatory-'+mod_group_id,
												// 		'class':'mods-mandatory'
												// 	}).appendTo('#mods_mandatory_div');
												// }
												// $('#mods-mandatory-'+mod_group_id).val(opt.min_no);

											// }
											// alert('sss');
											// if(default_count < total_mod){

												$('<h4/>').text(opt.name)
														  .addClass('text-center receipt media-mod-title')
														  .css({'margin-bottom':'5px'})
														  .appendTo('#mod-group-'+mod_group_id);
												var mandatory = opt.mandatory;
												var multiple = opt.multiple;
												// alert(mandatory);

												var div = $('#mod-group-'+mod_group_id);
												var divRow = $('<div/>').attr({'class':'row'});
												// var div = $('#mod-group-'+mod_group_id).append('<div class="row"></div>');
												$.each(opt.details,function(mod_id,det){

													// alert(det.default);
													// if(det.default != 1){

														var sCol = $('<div class="col-md-3 media-margin-mods"></div>');
														$('<button/>')
														.attr({'id':'mod-'+mod_id,'ref':mod_id,'class':'counter-btn-silver btn btn-block btn-default mods-btns media-ui-mods-btn','style':'color:'+opt.branch_text_color+'!important;'})
														// .css({'margin':'5px','width':'130px'})
														.text(det.name)
														.appendTo(sCol)
														.click(function(){

															$('.mods-btns').css("background-color", "#43B0F0!important");

										 					$('#mod-'+mod_id).css("background-color", "#999");

										 					var set_qty =1;

															if($('#num-qty').val() != ''){
																  set_qty = $('#num-qty').val();
																  $('#num-qty').val('');
																  $('#up-txt').text('');
															}

										 					// alert('aw');
										 					var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
															// var man_checker = mandatory_num -1;
															var man_checker = mandatory_num -set_qty;
															$('#mods-mandatory-'+mod_group_id).val(man_checker);
															if(man_checker <= "0"){
																$('#mod-group-name-'+mod_group_id).remove();
																$('#mods-mandatory-'+mod_group_id).remove();
															}
															
															det.qty=set_qty;
															addTransModCartRemove(trans_id,mod_group_id,mod_id,det,opt.menu_id,null,opt.menu_name,opt.mandatory,multiple,opt.name,opt.min_no,modgrp_cnt);


															


															return false;
														});
										 				sCol.appendTo(divRow);
										 				$('.scrollers-mods').css("display", 'none');
										 			
									 			});
									 			div.append(divRow);
									 			// $('<hr/>').appendTo('#mod-group-'+mod_group_id);
									 		// }
									 			seq++;
								 		});
										// $('.mods-div .mods-lists').after('<div class="scrollers-mods mod-scroll-width"><div class="row"><div class="col-md-3 text-left"><button id="previous-mod-btn" class="btn-block counter-btn-red-gray double btn btn-default media-mod-btns media-btn-height" style="background-color:#993300!important;width:163px"><i class="fa fa-fw fa-reply fa-2x media-previous"><span> PREVIOUS</span> </i></button></div><div class="col-md-3 text-left"><button id="mods-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-mod-btns media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:163px"><i class="fa fa-fw fa-chevron-left media-back-icon "></i><span class="media-back">BACK</span></button></div><div class="col-md-3 text-left"><button id="mods-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-mod-btns media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:163px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
								 	// 	$("#previous-mod-btn").on("click" ,function(){
								 	// 		$('#menu_categories_page').val(1);
				 					// 		// loadMenuCategories();
								 	// 	});
								 	// 	$("#mods-item-scroll-down").on("click" ,function(){
								 	// 	 //    scrolled=scrolled+100;
								 	// 		// $(".items-lists").animate({
								 	// 		//         scrollTop:  scrolled
								 	// 		// });

						 			// 	    var inHeight = $(".mods-lists")[0].scrollHeight;
						 			// 	    var divHeight = $(".mods-lists").height();
						 			// 	    var trueHeight = inHeight - divHeight;
						 			//         if((scrolled + 150) > trueHeight){
						 			//         	scrolled = trueHeight;
						 			//         }
						 			//         else{
						 			//     	    scrolled=scrolled+150;				    	
						 			//         }
						 			// 	    // scrolled=scrolled+100;
						 			// 		$(".mods-lists").animate({
						 			// 		        scrollTop:  scrolled
						 			// 		},200);
								 	// 	});
								 	// 	$("#mods-item-scroll-up").on("click" ,function(){
								 	// 		// scrolled=scrolled-100;
								 	// 		// $(".items-lists").animate({
								 	// 		//         scrollTop:  scrolled
								 	// 		// });
								 	// 		if(scrolled > 0){
								 	// 			scrolled=scrolled-150;
								 	// 			$(".mods-lists").animate({
								 	// 			        scrollTop:  scrolled
								 	// 			},200);
								 	// 		}
								 	// 	});
									}

								// });
								},'json');



							}else if(sel.hasClass('trans-subsub-row')){
								$('.submods-div .submods-lists').html('');
								var mod_line_id = sel.attr('trans-mod-id');
								var grp_name = sel.attr('group');
								var trans_id = sel.attr('trans-id');
								var modid = sel.attr('mod_id');
								type = 'submods';

								ttype = $('#ttype').val();
								var formData = 'ttype='+ttype;
								// if(other == "addModDefault"){
								// 	formData += '&add_defaults=1';
								// }
								$.post(baseUrl+'cashier/get_submodifiers/'+modid+'/'+trans_id+'/'+grp_name,formData,function(data){

									
									var submod = data.group;
									// var dfltSub = data.dflts;
									var grouping = data.grouping;
					
									if(!$.isEmptyObject(submod)){
										$('.loads-div').hide();
										$('.'+type+'-div').show();

										var row = $('<div/>').attr({'class':'mod-sub','id':'mod-subs'}).appendTo('.submods-div .submods-lists');
										// var div = $('#mod-subs');
										// var divRow = $('<div/>').attr({'class':'row'});
										seq = 1;
										submodgrp_cnt = 0;
										$.each(grouping,function(group_name,gopt){
											// alert(group_name);
											// $('.mods-div .title').text(opt.name+" Modifiers");
											if(seq == 1){
												style = '';
											}else{
												style = 'display:none;';
											}

											var g_row = $('<div/>').attr({'class':'grp-divs col-md-12 grp-div-'+seq,'id':'grp-div-'+gopt.group_id,'style':'height:auto;color:#fff;'+style}).appendTo(row);
											var title_row = $('<h3/>').attr({'class':'title text-center','style':'color:#fff','id':'grp-subs-'+gopt.group_id}).text(group_name).appendTo(g_row);

											seq++;
											submodgrp_cnt++;
											
										});

										$.each(submod,function(mod_sub_id,opt){
												
												// $.each(opt.details,function(mod_id,det){

													var sCol = $('<div class="col-md-4 col-lg-3"></div>');
													$('<button/>')
													.attr({'id':'submod-'+mod_sub_id,'ref':mod_sub_id,'class':'counter-btn-silver btn btn-block btn-default submods-btns media-submods-btns','style':'color:'+opt.branch_text_color+'!important;background-color:#43B0F0!important;height:60px;'})
													// .css({'margin':'5px','width':'130px'})
													.text(opt.name)
													.appendTo(sCol)
													.click(function(){


														$('.submods-btns').css("background-color", "#43B0F0!important");

									 					$('#submod-'+mod_sub_id).css("background-color", "#999");
														// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
														// var man_checker = mandatory_num -1;
														// $('#mods-mandatory-'+mod_group_id).val(man_checker);
														// if(man_checker <= "0"){
														// 	$('#mod-group-name-'+mod_group_id).remove();
														// 	$('#mods-mandatory-'+mod_group_id).remove();
														// }

														var set_qty =1;

														if($('#num-qty').val() != ''){
															  set_qty = $('#num-qty').val();
															  $('#num-qty').val('');
															  $('#up-txt').text('');
														}

														opt.qty=set_qty;

														addTransSubModCartRemove(trans_id,mod_sub_id,modid,opt,null,grp_name,mod_line_id);

														// curr_submod_seq = $('#submod_seq').val();
														// formData = 'trans_id='+trans_id+'&mod_id='+id+'&submod_seq_last='+submodgrp_cnt+'&curr_submod_seq='+curr_submod_seq+'&group_id='+opt.group_id+'&mod_line_id='+other;
														// // alert(formData);
														// $.post(baseUrl+'cashier/check_submodifier_grp',formData,function(data){

														// 	// console.log(data);
														// 	// alert(data);
														// 	if(data.status == 'next_div'){
														// 		submod_seq = $('#submod_seq').val();
														// 		$('.grp-divs').hide();
														// 		next_seq = Number(submod_seq) + 1;
														// 		$('.grp-div-'+next_seq).show();
														// 		$('#submod_seq').val(next_seq);

														// 		// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
														// 		// var man_checker = mandatory_num -1;
														// 		// $('#mods-mandatory-'+mod_group_id).val(man_checker);
														// 		// if(man_checker <= "0"){
														// 		// 	$('#mod-group-name-'+mod_group_id).remove();
														// 		// 	$('#mods-mandatory-'+mod_group_id).remove();
														// 		// }


														// 	}else if(data.status == 'finish'){
																
														// 		var mods_mandatory = $('.mods-mandatory').val();
														// 		// alert(mods_mandatory);
														// 		if(mods_mandatory >= "1"){
														// 			// var mod_name = $('.mod-group-name').val();
														// 			// rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
														// 			// return false;
														// 			loadsDiv('mods',data.menu_id,data.obj,data.trans_id,'back_sub');
														// 		}else{
														// 			loadsDiv('menus',data.cat_id,'SAMPLE');
														// 		}
														// 		// checkModifierDone(trans_id,'submods');
														// 	}

														// // });
														// },'json');


														// loadsDiv('submods',mod_id,det,trans_id,"","0");
														// alert('aw');
														return false;
													});
									 				// sCol.appendTo(row);
									 				sCol.appendTo('#grp-div-'+opt.group_id);

									 			// });
									 		});


							 			// div.append(divRow);
							 			// $('<hr/>').appendTo('#mod-sub-'+mod_sub_id);
										
									}

								// alert(data);
								// });
								},'json');


							}




						}
						
					}
				}
			}
			return false;
		});
		$('#charges-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			$('.charges-div .title').text('Select Charges');
			loadCharges();
			loadsDiv('charges',null,null,null);
			return false;
		});
		$('#remarks-btn').click(function(){
			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}
			var sel = $('.selected');
			$('#line-remarks').val('');
			if(sel.exists()){
				loadsDiv('remarks',null,null,null);
			}
			return false;
		});
		$('#add-remark-btn').click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");

			var noError = $('#remarks-form').rOkay({
				 				btn_load		: 	$(this),
				 				goSubmit		: 	false,
				 				bnt_load_remove	: 	true
							});
			if(noError){
				var formData = $('#remarks-form').serialize();		
				btn.goLoad();
				$.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){

					makeRemarksItemRow(id,data.remarks);
				
					btn.goLoad({load:false});
				},'json');	
			}
			return false;
		});

		$('#inv-check-btn').click(function(){
			$.rPopForm({
				// loadUrl : 'discount/discount_pop',
				loadUrl : 'cashier/inventory_check_menu',
				passTo	: '',
				// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
				title	: '<font size="4">Inventory Check</font>',
				noButton: 1,
				wide	: 1,
				// rform 	: 'guide_form',
				onComplete : function(){
								
							 }
			});
		});

		// for side remarks ex.100%
		$('#sugar-btn').click(function(){
			$('#dis_ice').hide();
			$('#dis_sugar').removeClass('display_sugar');
			$('#dis_sugar').show();
			// var sel = $('.selected');
			// var btn = $(this);
			// var id = sel.attr("ref");

			// text = $(this).text();


			// // if(noError){
			// var formData = 'line-remarks='+text;	
			// btn.goLoad();
			// $.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
			// 	makeRemarksItemRow(id,data.remarks);
				
			// 	btn.goLoad({load:false});
			// },'json');
			// // }
			// return false;
		});

		$('#ice-btn').click(function(){
			$('#dis_sugar').hide();
			$('#dis_ice').removeClass('hide');
			$('#dis_ice').show();
			// var sel = $('.selected');
			// var btn = $(this);
			// var id = sel.attr("ref");

			// text = $(this).text();


			// // if(noError){
			// var formData = 'line-remarks='+text;	
			// btn.goLoad();
			// $.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
			// 	makeRemarksItemRow(id,data.remarks);
				
			// 	btn.goLoad({load:false});
			// },'json');
			// // }
			// return false;
		});
		$('.add-rem-btns').click(function(){
			$('#dis_sugar').addClass('display_sugar');
		});
		$('.sugar-sub-btn, .ice-sub-btn').click(function(){
			if($('.subname').exists()){
				$('.subname').remove();
			}
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var text = $(this).text();
			var formData = 'line-remarks='+text;	

			$.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
				makeRemarksItemRow(id,data.remarks);
				
				btn.goLoad({load:false});
				// $('.sugar-sub-btn').attr('disabled','disabled');
			},'json');
			// }
			return false;
			// $('<span/>').attr('class','subname').css('margin-left','26px').html(text).appendTo('#trans-remarks-row-'+id);
		});

		$('#remarks-btn').click(function(){
			$('#dis_sugar').addClass('display_sugar');
		});

		$('#reset-remark-btn').click(function(){
			var sel = $('.selected');
			var id = sel.attr("ref");
			$.post(baseUrl+'cashier/remove_trans_remark/'+id,function(data){
							sel.prev().addClass('selected');
							$('#trans-remarks-row-'+id).remove();
							$('.counter-center .body').perfectScrollbar('update');
					$('.sugar-sub-btn, .add-rem-btns').removeAttr('disabled');
						// });
			},'json');

		});

		//end for side remarks

		//para sa extra buttons sa remarks
		$('.add-rem-btns').click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");

			text = $(this).text();

			// if(noError){
			var formData = 'line-remarks='+text;	
			btn.goLoad();
			$.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
					console.log(data);
				makeRemarksItemRow(id,data.remarks);
				
					btn.goLoad({load:false});
				// btn.attr('disabled','disabled');
			},'json');
			// });
			return false;
		});
		//para sa non chargeable takeout in dine in
		$('#take-all-btn').click(function(){
			var btn = $(this);

			ttype = $('#ttype').val();
			// alert(ttype);

			if(ttype == 'dinein'){
				if(!btn.hasClass('take-all-active')){
					
					btn.removeClass('counter-btn-green');					
					btn.addClass('take-all-active');	
					btn.addClass('counter-btn-red');
					btn.css('background-color','red');
				}
				else{
					btn.removeClass('counter-btn-red');
					btn.addClass('counter-btn-green');
					btn.removeClass('take-all-active');
					btn.css('background-color','#70AD47');

				}
			}else{
				rMsg('For dinein transaction only.','error');
			}



		});

		$('#tax-exempt-btn').click(function(){
			$.callManager({
				success : function(){
							$.post(baseUrl+'cashier/trans_exempt_to_tax',function(data){
								alert(data);
								transTotal();
								checkWagon('trans_cart');
							// },'json');	
							});	
						  }
			});						  
			return false;
		});
		$('#manager-btn').click(function(){
			window.location = baseUrl+'manager';
			return false;
		});
		$('#logout-btn').click(function(){
			window.location = baseUrl+'site/go_logout';
			return false;
		});
		$('#cancel-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#display-search-btn').click(function(){

			var mods_mandatory = $('.mods-mandatory').val();
			// alert(mods_mandatory);
			if(mods_mandatory >= "1"){
				var mod_name = $('.mod-group-name').val();
				rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
				return false;
			}

			// window.location = baseUrl+'cashier';
			$('.search-display').show();
			$('.center-btn-div').hide();
			$('.center-btn-div').hide();

			return false;
		});

		$('#cancel-search-btn').click(function(){
			// window.location = baseUrl+'cashier';
			$('#search-menu').val('');
			$('.search-display').hide();
			$('.center-btn-div').show();
			$('.center-btn-div').show();
			
			return false;
		});
		$("#menu-cat-scroll-down").on("click" ,function(){
		    var inHeight = $(".menu-cat-container")[0].scrollHeight;
		    var divHeight = $(".menu-cat-container").height();
		    var trueHeight = inHeight - divHeight;
	        if((scrolled + 150) > trueHeight){
	        	scrolled = trueHeight;
	        }
	        else{
	    	    scrolled=scrolled+150;				    	
	        }
		    // scrolled=scrolled+100;
			$(".menu-cat-container").animate({
			        scrollTop:  scrolled
			},200);
		});
		$("#menu-cat-scroll-up").on("click" ,function(){
			if(scrolled > 0){
				scrolled=scrolled-150;
				$(".menu-cat-container").animate({
				        scrollTop:  scrolled
				},200);
			}
		});
		$(".menu-cat-container").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$(".items-lists").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$('#search-menu').on('keyup',function(){
			var search = $(this).val();
			if(search != ""){
				remeload('menu');
				$('.scrollers-category').remove();
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.menus-div').show();
				$('.menus-div .title').text('Search: '+search);
				$('.menus-div .items-lists').html('');

				ttype = $('#ttype').val();
				// alert(ttype);

				var formData = 'search='+search+'&ttype='+ttype;
				$.post(baseUrl+'cashier/get_menus_search_sorted',formData,function(data){
					// alert(data);
					// console.log(data);
					active_div='menus';
					$('.scrollers-category').remove();
					$('.scrollers-menu').remove();

					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		$.each(data,function(key,opt){
			 			
			 			var menu_id = opt.id;

			 			var menu_id = opt.id;
			 			menu_qty = opt.qty-opt.cart_menu_qty;
			 			
			 			var unavailable = '';
			 			if(opt.qty == ''){
			 				unavailable = false;
			 			}

			 			
			 			var unavailable_text = '';
			 			
			 			if(opt.unavailable == 1){
			 				 unavailable_text = '<br /><span style="color:#fff">NOT AVAILABLE</span>';
			 				 unavailable = true;
			 			}else if(opt.qty >= 0 && opt.qty != ''){
			 				unavailable_text = '<br /><span style="color:#fff">(<span id="menuqty-'+menu_id+'">'+menu_qty+'</span>) QTY</span>';
			 				unavailable =  menu_qty <= 0 ? true : false;
			 			}
			 			else if(menu_qty  <= 0 && opt.qty != ''){
			 				unavailable_text =  '<br /><span style="color:#fff">(0) QTY</span>';
			 				unavailable = true;
			 			}

			 			var sCol = $('<div class="col-md-3 media-margin-menu"></div>');
			 			$('<button/>')
			 			// .attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'qty':opt.qty,'class':' btn btn-block btn-default media-menu-ord-btn','style':'color:'+opt.branch_text_color+'!important;'})
			 			.prop('disabled',unavailable)
				 			// .text(opt.name)
				 		.html(opt.name + unavailable_text)
			 			.appendTo(sCol)
			 			.click(function(){

			 				if(opt.neg_inv == '0'){
			 					// hindi pede negative
			 					if(opt.menu_oh <= 0){
			 						rMsg(opt.name+' is out of stock.','error');
			 						return false;
			 					}else{

			 						if(opt.check_reorder == 1){
				 						if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else{
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}
			 						
			 						// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(opt.category);
					 				// 	$('#menu-cat-name-hidden').val(opt.category_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{

						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt);
							 							  }	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt);
						 				}

						 				if(opt.qty != ''){
							 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
							 					if(data.qty >= opt.qty){
							 						$('#menu-'+menu_id).prop('disabled',true);
							 					}

							 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
							 				},'json');

							 			}
						 			// }

					 				return false;
					 				$('.scrollers-menu').remove();

			 					}
			 				}else{
				 				if(opt.check_reorder == 1){
					 				if(opt.reorder_qty != 0){
				 						if(opt.menu_oh > 0){
						 					if(opt.menu_oh <= opt.reorder_qty){
						 						rMsg(opt.name+' inventory is near to out of stock.','warning');
						 					}
				 						}else{
				 							rMsg(opt.name+' inventory is in negative stock.','warning');
				 						}

					 				}
				 				}

				 				// if(opt.cost  == 0){
				 				// 	$('.loads-div').hide();
				 				// 	$('#menu-id-hidden').val(menu_id);
				 				// 	$('#menu-cat-id-hidden').val(opt.category);
				 				// 	$('#menu-cat-name-hidden').val(opt.category_name);
									// $('.price-div').show();
									// selectModMenu();
				 				// }else{

					 				if(opt.free == 1){
						 				$.callManager({
						 					success : function(){
										 				addTransCart(menu_id,opt);
						 							  }	
						 				});
					 				}
					 				else{
					 					addTransCart(menu_id,opt);
					 				}

					 				
					 				if(opt.qty != ''){
							 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
							 					if(data.qty >= opt.qty){
							 						$('#menu-'+menu_id).prop('disabled',true);
							 					}

							 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
							 				},'json');

							 			}
					 			// }
				 				return false;
			 				}

			 			});
			 			sCol.appendTo(div);
			 		});
			 		$('.menus-div .items-lists').after('<div class="scrollers-menu"><div class="row"><div class="col-md-3 text-left"><button id="previous-search-btn" class="btn-block counter-btn-red-gray double btn btn-default media-btn-height" style="background-color:#993300!important;width:130px"><i class="fa fa-fw fa-reply fa-2x media-previous"></i><span style="font-size:15px"> PREVIOUS</span> </button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:130px"><i class="fa fa-fw fa-chevron-left media-back-icon"></i><span class="media-back">BACK</span></button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:130px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
			 		$("#previous-search-btn").on("click" ,function(){
		 		 
	 					$('#menu_categories_page').val(1);
	 					loadMenuCategories();
			 		});
			 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });

	 				    var inHeight = $(".items-lists")[0].scrollHeight;
	 				    var divHeight = $(".items-lists").height();
	 				    var trueHeight = inHeight - divHeight;
	 			        if((scrolled + 150) > trueHeight){
	 			        	scrolled = trueHeight;
	 			        }
	 			        else{
	 			    	    scrolled=scrolled+150;				    	
	 			        }
	 				    // scrolled=scrolled+100;
	 					$(".items-lists").animate({
	 					        scrollTop:  scrolled
	 					},200);
			 		});
			 		$("#menu-item-scroll-up").on("click" ,function(){
			 			// scrolled=scrolled-100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			if(scrolled > 0){
			 				scrolled=scrolled-150;
			 				$(".items-lists").animate({
			 				        scrollTop:  scrolled
			 				},200);
			 			}
			 		});
			 	// });
			 	},'json');
			}
			return false;
		});

		$('#search-menu')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
			autoAccept : true,
			change   : function(e, keyboard, el) { 
				// alert(1);
				// $('#search-menu').trigger('keyup');
				var search = $('#search-menu').val();
				if(search != ""){
					remeload('menu');
					$('.scrollers-category').remove();
					$('.scrollers-menu').remove();
					$('.loads-div').hide();
					$('.menus-div').show();
					$('.menus-div .title').text('Search: '+search);
					$('.menus-div .items-lists').html('');

					ttype = $('#ttype').val();
					// alert(ttype);

					var formData = 'search='+search+'&ttype='+ttype;
					$.post(baseUrl+'cashier/get_menus_search_sorted',formData,function(data){
						// alert(data);
						// console.log(data);
						active_div='menus';
						$('.scrollers-category').remove();
						$('.scrollers-menu').remove();

						var div = $('.menus-div .items-lists').append('<div class="row"></div>');
				 		$.each(data,function(key,opt){
				 			
				 			var menu_id = opt.id;

				 			var menu_id = opt.id;
				 			menu_qty = opt.qty-opt.cart_menu_qty;
				 			
				 			var unavailable = '';
				 			if(opt.qty == ''){
				 				unavailable = false;
				 			}

				 			
				 			var unavailable_text = '';
				 			
				 			if(opt.unavailable == 1){
				 				 unavailable_text = '<br /><span style="color:#fff">NOT AVAILABLE</span>';
				 				 unavailable = true;
				 			}else if(opt.qty >= 0 && opt.qty != ''){
				 				unavailable_text = '<br /><span style="color:#fff">(<span id="menuqty-'+menu_id+'">'+menu_qty+'</span>) QTY</span>';
				 				unavailable =  menu_qty <= 0 ? true : false;
				 			}
				 			else if(menu_qty  <= 0 && opt.qty != ''){
				 				unavailable_text =  '<br /><span style="color:#fff">(0) QTY</span>';
				 				unavailable = true;
				 			}

				 			var sCol = $('<div class="col-md-3 media-margin-menu"></div>');
				 			$('<button/>')
				 			// .attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
				 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'qty':opt.qty,'class':' btn btn-block btn-default media-menu-ord-btn','style':'color:'+opt.branch_text_color+'!important;'})
				 			.prop('disabled',unavailable)
					 			// .text(opt.name)
					 		.html(opt.name + unavailable_text)
				 			.appendTo(sCol)
				 			.click(function(){

				 				if(opt.neg_inv == '0'){
				 					// hindi pede negative
				 					if(opt.menu_oh <= 0){
				 						rMsg(opt.name+' is out of stock.','error');
				 						return false;
				 					}else{

				 						if(opt.check_reorder == 1){
					 						if(opt.reorder_qty != 0){
						 						if(opt.menu_oh > 0){
								 					if(opt.menu_oh <= opt.reorder_qty){
								 						rMsg(opt.name+' inventory is near to out of stock.','warning');
								 					}
						 						}else{
						 							rMsg(opt.name+' inventory is in negative stock.','warning');
						 						}

							 				}
						 				}
				 						
				 						// if(opt.cost  == 0){
						 				// 	$('.loads-div').hide();
						 				// 	$('#menu-id-hidden').val(menu_id);
						 				// 	$('#menu-cat-id-hidden').val(opt.category);
						 				// 	$('#menu-cat-name-hidden').val(opt.category_name);
											// $('.price-div').show();
											// selectModMenu();
						 				// }else{

							 				if(opt.free == 1){
								 				$.callManager({
								 					success : function(){
												 				addTransCart(menu_id,opt);
								 							  }	
								 				});
							 				}
							 				else{
							 					addTransCart(menu_id,opt);
							 				}

							 				if(opt.qty != ''){
								 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
								 					if(data.qty >= opt.qty){
								 						$('#menu-'+menu_id).prop('disabled',true);
								 					}

								 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
								 				},'json');

								 			}
							 			// }

						 				return false;
						 				$('.scrollers-menu').remove();

				 					}
				 				}else{
					 				if(opt.check_reorder == 1){
						 				if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else{
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}

					 				// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(opt.category);
					 				// 	$('#menu-cat-name-hidden').val(opt.category_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{

						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt);
							 							  }	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt);
						 				}

						 				
						 				if(opt.qty != ''){
								 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
								 					if(data.qty >= opt.qty){
								 						$('#menu-'+menu_id).prop('disabled',true);
								 					}

								 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
								 				},'json');

								 			}
						 			// }
					 				return false;
				 				}

				 			});
				 			sCol.appendTo(div);
				 		});
				 		$('.menus-div .items-lists').after('<div class="scrollers-menu"><div class="row"><div class="col-md-3 text-left"><button id="previous-search-btn" class="btn-block counter-btn-red-gray double btn btn-default media-btn-height" style="background-color:#993300!important;width:130px"><i class="fa fa-fw fa-reply fa-2x media-previous"></i><span style="font-size:15px"> PREVIOUS</span> </button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:130px"><i class="fa fa-fw fa-chevron-left media-back-icon"></i><span class="media-back">BACK</span></button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:130px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
				 		$("#previous-search-btn").on("click" ,function(){
			 		 
		 					$('#menu_categories_page').val(1);
		 					loadMenuCategories();
				 		});
				 		$("#menu-item-scroll-down").on("click" ,function(){
				 		 //    scrolled=scrolled+100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });

		 				    var inHeight = $(".items-lists")[0].scrollHeight;
		 				    var divHeight = $(".items-lists").height();
		 				    var trueHeight = inHeight - divHeight;
		 			        if((scrolled + 150) > trueHeight){
		 			        	scrolled = trueHeight;
		 			        }
		 			        else{
		 			    	    scrolled=scrolled+150;				    	
		 			        }
		 				    // scrolled=scrolled+100;
		 					$(".items-lists").animate({
		 					        scrollTop:  scrolled
		 					},200);
				 		});
				 		$("#menu-item-scroll-up").on("click" ,function(){
				 			// scrolled=scrolled-100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });
				 			if(scrolled > 0){
				 				scrolled=scrolled-150;
				 				$(".items-lists").animate({
				 				        scrollTop:  scrolled
				 				},200);
				 			}
				 		});
				 	// });
				 	},'json');
				}
				return false;
			},

        })
        .addNavigation({
            position   : [0,0],
            toggleMode : false,
            focusClass : 'hasFocus'
        });

		$(document).on('click','.transactions-close-btn',function(){
			// $('.'+active_div).show();
			
			// loadsDiv(active_div);	
			loadMenuCategories();		
			// loadsDiv(active_div);
			// $('.loads-div').hide();
			// $('.'+active_div+'-div').show();			
			return false;
		});

		function loadMenuCategories(){
			var is_updateg = $("input[type=hidden]#is_update").val();
			cat_page = $('#menu_categories_page').val();
			ttype = $('#ttype').val();

			// alert(is_updateg);
			formData = 'cat_page='+cat_page+'&ttype='+ttype;

			if(is_updateg == "update"){
				$.post(baseUrl+'cashier/get_menu_cats_unli',formData,function(data){
			 		// alert(data);return false;
			 		showMenuCategories(data,1);
		 		// });
			 	},'json');
			}else{
				// alert(cat_page);

				if(ttype == 'mcb'){
					$.post(baseUrl+'cashier/get_menu_cats_mcb',formData,function(data){
				 		// alert(data);return false;
				 		showMenuCategories(data,1);
			 		// });
				 	},'json');
				}else{
				 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
				 	$.post(baseUrl+'cashier/get_menu_cats',formData,function(data){
				 		// alert(data);return false;
				 		showMenuCategories(data.json,data.next);
			 		// });
				 	},'json');
				}
			}

		}
		function showMenuCategories(data,ctr){
			// alert(ctr);
			$('.scrollers-category').remove();
			$('.loads-div').hide();
			$('.category-div').show();
			// $('.category-div .title').text(opt.name);
			$('.category-div .category-lists').html('');

			// $('.menus-div .items-lists').html('');




			// $('.category-btns').remove();


			var div = $('.category-div .category-lists').append('<div class="row"></div>');
				// for category -> subcategory -> menu
			// $.post(baseUrl+'cashier/get_subcategory_sorted/'+id,function(data){
		 // 		// alert(data);
		 // 		$.each(data,function(key,opt){
		 			
		 // 			var menu_sub_id = opt.id;
		 // 			var sCol = $('<div class="col-md-3"></div>');
		 // 			$('<button/>')
		 // 			.attr({'id':'menusub-'+menu_sub_id,'ref':menu_sub_id,'class':'counter-btn-green btn btn-block btn-default','style':'height:60px'})
		 // 			.text(opt.name)
		 // 			.appendTo(sCol)
		 // 			.click(function(){
		 // 				var btn = $(this);
		 // 				btn.goLoad();
		 // 				loadsDiv('subcategory',menu_sub_id,opt,id,cat_name);
		 // 				return false;
		 // 			});
		 // 			sCol.appendTo(div);
		 			
		 // 		});
		 		

		 // 	// });
		 // 	},'json');

			$.each(data,function(key,val){
				var cat_id = val['id'];
				// alert('aw');
				// if(ctr == 1){
				// 	var hashTag = window.location.hash;
				// 	// alert(hashTag);
				// 	if(hashTag != '#retail'){
				// 		loadsDiv('menus',cat_id,val,null);
				// 	}
				// }
				var sCol = $('<div class="col-md-3 col media-margin-category-negative"></div>');


	 			$('<button/>')
	 			.attr({'id':'menu-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default media-order-btns','style':'color:'+val['branch_text_color']+'!important;background-color:'+val['bcolor']+'!important'})
	 			.text(val.name)
	 			// .appendTo('.menu-cat-container')
	 			.appendTo(sCol)
	 			.click(function(){
						var mods_mandatory = $('.mods-mandatory').val();
						// alert(mods_mandatory);
						if(mods_mandatory >= "1"){
							var mod_name = $('.mod-group-name').val();
							rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
							return false;
						}


	 				$('#search-menu').val('');
	 				// loadsDiv('subcategory',cat_id,val,null);
	 				
	 				loadsDiv('menus',cat_id,val,null);
	 				return false;
	 			});

	 			sCol.appendTo(div);
				// ctr++;
			});


			$('.category-div .category-lists').after('<div class="scrollers-category media-scrollers-category"><div class="row"><div class="col-md-4 text-left"><button id="previous-btn" class="btn-block counter-btn-red-gray double btn btn-default media-btn-height" style="background-color:#993300!important;width:130px"><i class="fa fa-fw fa-reply media-previous"></i><span style="font-size:15px"> PREVIOUS</span> </button></div><div class="col-md-4 text-left"><button id="category-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:130px"><i class="fa fa-fw fa-chevron-left media-back-icon"></i><span class="media-back">BACK</span></button></div><div class="col-md-4 text-left"><button id="category-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:130px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
		 		$("#category-item-scroll-down").on("click" ,function(){
		 		 //    scrolled=scrolled+100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });

 				 //    var inHeight = $(".category-lists")[0].scrollHeight;
 				 //    var divHeight = $(".category-lists").height();
 				 //    var trueHeight = inHeight - divHeight;
 			  //       if((scrolled + 150) > trueHeight){
 			  //       	scrolled = trueHeight;
 			  //       }
 			  //       else{
 			  //   	    scrolled=scrolled+150;				    	
 			  //       }
 				 //    // scrolled=scrolled+100;
 					// $(".category-lists").animate({
 					//         scrollTop:  scrolled
 					// },200);

 					cat_page = $('#menu_categories_page').val();
 					// alert(ctr);
 					if(ctr == 'yes'){
	 					page = Number(cat_page) + 1;

	 					$('#menu_categories_page').val(page);

	 					loadMenuCategories();
 					}

 					// alert(cat_page);
		 		});
		 		$("#category-item-scroll-up").on("click" ,function(){
		 			// scrolled=scrolled-100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });
		 			// if(scrolled > 0){
		 			// 	scrolled=scrolled-150;
		 			// 	$(".category-lists").animate({
		 			// 	        scrollTop:  scrolled
		 			// 	},200);
		 			// }

		 			cat_page = $('#menu_categories_page').val();
 					page = Number(cat_page) - 1;
 					if(page >= 1){
	 					$('#menu_categories_page').val(page);
	 					loadMenuCategories();
 					}

		 		});
			
			// if(ctr < 10){
			// 	for (var i = 0; i <= (10-ctr); i++) {
			// 		$('<button/>')
		 // 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 // 			.text('')
		 // 			.appendTo('.menu-cat-container');
			// 	};
			// }
		}
		function loadItemCategories(){
		 	$.post(baseUrl+'cashier/get_item_categories',function(data){
		 		showItemCategories(data,1);
		 	},'json');
		}
		function showItemCategories(data,ctr){
			$('.category-btns').remove();
			$.each(data,function(cat_id,val){
				if(ctr == 1){
					loadsDiv('retail');
				}
	 			$('<button/>')
	 			.attr({'id':'item-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
	 			.click(function(){
	 				var formData = 'cat_id='+cat_id+'&cat_name='+val.name;
	 				loadsDiv('retail');
	 				loadRetailItemList(formData,$(this));
	 				return false;
	 			});
				ctr++;
			});
			// alert(ctr);
			if(ctr < 9){
				for (var i = 0; i <= (8-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
		}
		function scannedRetailItem(formData){
			// btn.goLoad();
			ttype = $('#ttype').val();
			formData = formData+"&ttype="+ttype;
			$.post(baseUrl+'cashier/get_item_scanned',formData,function(data){
				if(data.error  != ""){
					rMsg(data.error,'error');
				}
				else{
					// addRetailTransCart(data.item_id,data.opt);
					$.each(data.json,function(key,opt){
			 			
			 			var menu_id = opt.id;

						addTransCart(data.menu_id,opt);
			 		});
				}
				// $('.retail-title').text(data.title).show(); 
				// $('.retail-loads-div').html(data.code);
				// $.each(data.items,function(item_id,opt){
				// 	$('#retail-item-'+item_id).click(function(){
				// 		addRetailTransCart(item_id,opt);
				// 		return false;
				// 	});
				// });
				// $('#search-item').val('');
				// btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function loadRetailItemList(formData,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/get_item_lists',formData,function(data){
				$('.retail-title').text(data.title).show();
				$('.retail-loads-div').html(data.code);
				$.each(data.items,function(item_id,opt){
					$('#retail-item-'+item_id).click(function(){
						addRetailTransCart(item_id,opt);
						return false;
					});
				});
				$('#search-item').val('');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function loadRetailCustomerList(formData,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/get_customers_lists',formData,function(data){
				$('.customers-loads-div').html(data.code);
				$.each(data.items,function(customer_id,opt){
					$('#customer-item-'+customer_id).click(function(){
						$.post(baseUrl+'cashier/update_trans_customer/'+customer_id,function(data){
							$('#trans-customer').text('CUSTOMER ID: '+customer_id).show();
							rMsg('Added Customer ID','success');
						});
						return false;
					});
				});
				$('#search-item').val('');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function remeload(type_load){
			if(type_load == 'retail'){
				$('#retail-btn').removeClass('counter-btn-red');
				$('#retail-btn').addClass('counter-btn-green');
				loadsDiv('retail');
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				loadItemCategories();
				$('#scan-code').focus();
			}
			else{
				$('#retail-btn').removeClass('counter-btn-green');
				$('#retail-btn').addClass('counter-btn-red');
			}
		}
		function loadsDiv(type,id,opt,trans_id,other,mandatory,page=1){
			if(type == 'menus'){
				active_div='menus';
				remeload('menu');
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				$('.menus-div .title').text(opt.name);
				$('.menus-div .items-lists').html('');

				ttype = $('#ttype').val();

				// alert(ttype);

				// console.log(opt);
				cat_name = opt.name;
				$('.subcategory-div .subcategory-lists').html('');
				$('.subcategory-div .items-search').html('');


				var div = $('.menus-div .items-lists').append('<div class="row"></div>');
				// // for category -> subcategory -> menu
				// formData1 = 'ttype='+ttype+'&page='+page;
				// subcount = 0;
				// $.post(baseUrl+'cashier/get_subcategory_sorted/'+id,formData1,function(data){
				 // 		// alert(data);
				 // 		// subcount = data.subcount;
					// 	// alert(subcount);
				 // 		$.each(data.json,function(key,opt){
				 			
				 // 			var menu_sub_id = opt.id;
				 // 			var sCol = $('<div class="col-md-3"></div>');
				 // 			$('<button/>')
				 // 			.attr({'id':'menusub-'+menu_sub_id,'ref':menu_sub_id,'class':'counter-btn-green btn btn-block btn-default','style':'height:60px'})
				 // 			.text(opt.name)
				 // 			.appendTo(sCol)
				 // 			.click(function(){
				 // 				var btn = $(this);
				 // 				btn.goLoad();
				 // 				loadsDiv('subcategory',menu_sub_id,opt,id,cat_name);
				 // 				return false;
				 // 			});
				 // 			sCol.appendTo(div);

				 // 			subcount++;
				 			
				 // 		});
				 		

				 // 	// });
				 // 	},'json');
				
				// alert(subcount+'aaaa');

				//for category -> menu
				formData = 'ttype='+ttype+'&page='+page;
			 	$.post(baseUrl+'cashier/get_menus_sorted/'+id,formData,function(data){
			 		// alert(data);
					// data.show_img = 1;
			 		if(data.show_img == 1){
			 			$.each(data.json,function(key,opt){
				 			// alert(opt.reorder_qty);
				 			var menu_id = opt.id;

				 			menu_qty = opt.qty-opt.cart_menu_qty;
				 			
				 			var unavailable = '';

				 			if(opt.qty == ''){
				 				unavailable = false;
				 			}

				 			
				 			var unavailable_text = '';

				 			if(opt.unavailable == 1){
				 				 unavailable_text = '<br /><span style="color:#fff">NOT AVAILABLE</span>';
				 				 unavailable = true;
				 			}else if(opt.qty >= 0 && opt.qty != ''){
				 				unavailable_text = '<br /><span style="color:#fff">(<span id="menuqty-'+menu_id+'">'+menu_qty+'</span>) QTY</span>';
				 				unavailable =  menu_qty <= 0 ? true : false;
				 			}
				 			else if(menu_qty  <= 0 && opt.qty != ''){
				 				unavailable_text =  '<br /><span style="color:#fff">(0) QTY</span>';
				 				unavailable = true;
				 			}

				 			var sCol = $('<div class="col-md-3" style="margin-top:10px;position:relative;text-align: center;color: white; cursor:pointer; border-style:solid;"></div>');
				 			$('<img src='+baseUrl+opt.img_path+' width="100%" height="100%" id="menu-'+menu_id+'"><div style="position: absolute;bottom:1px;background-color:rgba(0,0,0,.6); width:100%; text-align:center;font-size:11px;">'+opt.name + ' ' + unavailable_text +'</div>')
				 			// .text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				// alert(opt.menu_oh);
				 				if(opt.neg_inv == '0'){
				 					// hindi pede negative
				 					if(opt.menu_oh <= 0){
				 						rMsg(opt.name+' is out of stock.','error');
				 						return false;
				 					}else{
				 						
				 						if(opt.check_reorder == 1){
					 						if(opt.reorder_qty != 0){
						 						if(opt.menu_oh > 0){
								 					if(opt.menu_oh <= opt.reorder_qty){
								 						rMsg(opt.name+' inventory is near to out of stock.','warning');
								 					}
						 						}else{
						 							rMsg(opt.name+' inventory is in negative stock.','warning');
						 						}

							 				}
							 			}

							 			// for 0 price rquest
				 						// if(opt.cost  == 0){
						 				// 	$('.loads-div').hide();
						 				// 	$('#menu-id-hidden').val(menu_id);
						 				// 	$('#menu-cat-id-hidden').val(id);
						 				// 	$('#menu-cat-name-hidden').val(cat_name);
											// $('.price-div').show();
											// selectModMenu();
						 				// }else{
							 				btn.goLoad();
							 				if(opt.free == 1){
								 				$.callManager({
								 					success : function(){
												 				addTransCart(menu_id,opt,btn);
								 							  },
								 					fail    : function(){
								 								btn.goLoad({load:false});
								 							  },
								 					cancel  : function(){
								 								btn.goLoad({load:false});
								 							  }		  	
								 				});
							 				}
							 				else{
							 					addTransCart(menu_id,opt,btn);
							 				}

							 				if(opt.qty != ''){
								 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
								 					if(data.qty >= opt.qty){
								 						$('#menu-'+menu_id).prop('disabled',true);
								 					}

								 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
								 				},'json');

								 			}
							 			// }
						 				return false;
				 					}
				 				}else{
					 				if(opt.check_reorder == 1){
						 				if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else{
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}

					 				// for 0 price rquest
					 				// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(id);
					 				// 	$('#menu-cat-name-hidden').val(cat_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{
						 				btn.goLoad();
						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt,btn);
							 							  },
							 					fail    : function(){
							 								btn.goLoad({load:false});
							 							  },
							 					cancel  : function(){
							 								btn.goLoad({load:false});
							 							  }		  	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt,btn);
						 				}
						 			// }

						 			if(opt.qty != ''){
						 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
						 					if(data.qty >= opt.qty){
						 						$('#menu-'+menu_id).prop('disabled',true);
						 					}

						 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
						 				},'json');

						 			}

					 				return false;
				 				}


				 			});
				 			sCol.appendTo(div);
				 			
				 		});
			 		}else{
						// var div = $('.menus-div .items-lists').append('<div class="row"></div>');
				 		
						$.each(data.jsons,function(key,opt){
				 			
				 			var menu_sub_id = opt.id;
				 			var sCol = $('<div class="col-md-3 media-margin-category"></div>');
				 			$('<button/>')
				 			.attr({'id':'menusub-'+menu_sub_id,'ref':menu_sub_id,'class':' btn btn-block btn-default media-menu-ord-btn','style':'color:'+opt.branch_text_color+'!important;'})
				 			.text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				btn.goLoad();
				 				loadsDiv('subcategory',menu_sub_id,opt,id,cat_name);
				 				return false;
				 			});
				 			sCol.appendTo(div);
				 			
				 		});


				 		$.each(data.json,function(key,opt){
				 			// alert(opt.reorder_qty);
				 			var menu_id = opt.id;
				 			menu_qty = opt.qty-opt.cart_menu_qty;
				 			
				 			var unavailable = '';

				 			if(opt.qty == ''){
				 				unavailable = false;
				 			}

				 			
				 			var unavailable_text = '';
				 			
				 			if(opt.unavailable == 1){
				 				 unavailable_text = '<br /><span style="color:#fff">NOT AVAILABLE</span>';
				 				 unavailable = true;
				 			}else if(opt.qty >= 0 && opt.qty != ''){
				 				unavailable_text = '<br /><span style="color:#fff">(<span id="menuqty-'+menu_id+'">'+menu_qty+'</span>) QTY</span>';
				 				unavailable =  menu_qty <= 0 ? true : false;
				 			}
				 			else if(menu_qty  <= 0 && opt.qty != ''){
				 				unavailable_text =  '<br /><span style="color:#fff">(0) QTY</span>';
				 				unavailable = true;
				 			}

				 			var sCol = $('<div class="col-md-3 media-margin-menu"></div>');
				 			$('<button/>')
				 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'qty':opt.qty,'class':' btn btn-block btn-default menu-btns media-menu-ord-btn','style':'color:'+opt.branch_text_color+'!important;'})
				 			.prop('disabled',unavailable)
				 			// .text(opt.name)
				 			.html(opt.name + unavailable_text)
				 			.appendTo(sCol)
				 			.click(function(){
				 				// alert('hahaha');
				 				$('.menu-btns').css("background-color", '#43B0F0!important');

				 				$('#menu-'+menu_id).css("background-color", "#999");
				 				// $('button.counter-btn-silver').css("background-color", "gray!important");
				 				var btn = $(this);
				 				// alert(opt.menu_oh);
				 				if(opt.neg_inv == '0'){
				 					// hindi pede negative
				 					if(opt.menu_oh <= 0){
				 						rMsg(opt.name+' is out of stock.','error');
				 						return false;
				 					}else{
				 						
				 						if(opt.check_reorder == 1){
					 						if(opt.reorder_qty != 0){
						 						if(opt.menu_oh > 0){
								 					if(opt.menu_oh <= opt.reorder_qty){
								 						rMsg(opt.name+' inventory is near to out of stock.','warning');
								 					}
						 						}else{
						 							rMsg(opt.name+' inventory is in negative stock.','warning');
						 						}

							 				}
							 			}

							 			// for 0 price rquest
				 						// if(opt.cost  == 0){
						 				// 	$('.loads-div').hide();
						 				// 	$('#menu-id-hidden').val(menu_id);
						 				// 	$('#menu-cat-id-hidden').val(id);
						 				// 	$('#menu-cat-name-hidden').val(cat_name);
											// $('.price-div').show();
											// selectModMenu();
						 				// }else{
							 				btn.goLoad();
							 				if(opt.free == 1){
								 				$.callManager({
								 					success : function(){
												 				addTransCart(menu_id,opt,btn);
								 							  },
								 					fail    : function(){
								 								btn.goLoad({load:false});
								 							  },
								 					cancel  : function(){
								 								btn.goLoad({load:false});
								 							  }		  	
								 				});
							 				}
							 				else{
							 					addTransCart(menu_id,opt,btn);
							 				}
							 			// }
						 				return false;
				 					}
				 				}else{
					 				if(opt.check_reorder == 1){
						 				if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else{
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}

					 				// for 0 price rquest
					 				// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(id);
					 				// 	$('#menu-cat-name-hidden').val(cat_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{
						 				btn.goLoad();
						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt,btn);
							 							  },
							 					fail    : function(){
							 								btn.goLoad({load:false});
							 							  },
							 					cancel  : function(){
							 								btn.goLoad({load:false});
							 							  }		  	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt,btn);
						 				}
						 			// }
						 			if(opt.qty != ''){
						 				$.post(baseUrl + 'cashier/count_trans_menu/'+menu_id,function(data){
						 					if(data.qty >= opt.qty){
						 						$('#menu-'+menu_id).prop('disabled',true);
						 					}

						 					$('#menuqty-'+menu_id).text(opt.qty - data.qty);
						 				},'json');

						 			}
						 			
					 				return false;
				 				}


				 			});
				 			sCol.appendTo(div);
				 			
				 		});
			 		}

			 		
			 	},'json');
			 	// });

			 		// $('.menus-div .items-lists').after('<div class="scrollers-menu"><div class="row" style="margin-top:6px;"><div class="col-md-6 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
			 		// $("#menu-item-scroll-down").on("click" ,function(){
			 		//  //    scrolled=scrolled+100;
			 		// 	// $(".items-lists").animate({
			 		// 	//         scrollTop:  scrolled

		 		$('.menus-div .items-lists').after('<div class="scrollers-menu scroller-menu-width"><div class="row"><div class="col-md-3 text-left"><button id="previous-menu-btn" class="btn-block counter-btn-red-gray double btn btn-default media-btn-height" style="background-color:#993300!important;width:130px"><i class="fa fa-fw fa-reply fa-2x media-previous"></i><span style="font-size:15px"> PREVIOUS</span> </button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:130px"><i class="fa fa-fw fa-chevron-left media-back-icon"></i><span class="media-back">BACK</span></button></div><div class="col-md-3 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:130px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
		 		$("#previous-menu-btn").on("click" ,function(){
		 		 
 					$('#menu_categories_page').val(1);
 					loadMenuCategories();
		 		});

		 		$("#menu-item-scroll-down").on("click" ,function(){
		 		 //    scrolled=scrolled+100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });

 				 //    var inHeight = $(".items-lists")[0].scrollHeight;
 				 //    var divHeight = $(".items-lists").height();
 				 //    var trueHeight = inHeight - divHeight;
 			  //       if((scrolled + 150) > trueHeight){
 			  //       	scrolled = trueHeight;
 			  //       }
 			  //       else{
 			  //   	    scrolled=scrolled+150;				    	
 			  //       }
 				 //    // scrolled=scrolled+100;
 					// $(".items-lists").animate({
 					//         scrollTop:  scrolled
 					// },200);

 					// alert(other);
 					// alert(mandatory);

 					menu_page = $('#menu_page').val();
 					formData = 'cat_id='+id;
 					$.post(baseUrl+'cashier/check_next_page/'+menu_page+'/menus',formData,function(data){
	 					// alert(data);
 						if(data == 'yes'){
		 					page = Number(menu_page) + 1;

		 					$('#menu_page').val(page);

		 					loadsDiv('menus',id,opt,null,null,null,page);
 						}
 					});


		 		});
		 		$("#menu-item-scroll-up").on("click" ,function(){
		 			// scrolled=scrolled-100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });
		 			// if(scrolled > 0){
		 			// 	scrolled=scrolled-150;
		 			// 	$(".items-lists").animate({
		 			// 	        scrollTop:  scrolled
		 			// 	},200);
		 			// }
		 			menu_page = $('#menu_page').val();
 					page = Number(menu_page) - 1;
 					if(page >= 1){
 						// alert(page);
	 					$('#menu_page').val(page);
	 					loadsDiv('menus',id,opt,null,null,null,page);
 					}
		 		});
			}
			else if(type=='subcategory'){
				ttype = $('#ttype').val();

				remeload('menu');
				$('.scrollers-subcategory').remove();
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				$('.subcategory-div .title').text(opt.name);
				$('.subcategory-div .subcategory-lists').html('');

				$('.menus-div .items-lists').html('');
				// var div2 = $('.subcategory-div .items-search').append('<div class="row"></div>');


					// var sCol2 = $('<div class="col-md-9"></div>');
		 		// 	sCol2.appendTo(div2);
					// var sCol2 = $('<div class="col-md-3"></div>');
			 	// 		$('<button/>')
			 	// 		.attr({'id':'subcat-back','class':'counter-btn-red btn btn-block btn-default'})
			 	// 		.text('Back')
			 	// 		.appendTo(sCol2)
			 	// 		.click(function(){
			 	// 			// var btn = $(this);
			 	// 			// btn.goLoad();
			 	// 			// if(opt.free == 1){
				 // 			// 	$.callManager({
				 // 			// 		success : function(){
					// 			//  				addTransCart(menu_id,opt,btn);
				 // 			// 				  },
				 // 			// 		fail    : function(){
				 // 			// 					btn.goLoad({load:false});
				 // 			// 				  },
				 // 			// 		cancel  : function(){
				 // 			// 					btn.goLoad({load:false});
				 // 			// 				  }		  	
				 // 			// 	});
			 	// 			// }
			 	// 			// else{
			 	// 			// 	addTransCart(menu_id,opt,btn);
			 	// 			// }
			 	// 			var obj = {
					//         	'name':other
					// 	    };
				 // 			loadsDiv('menus',trans_id,obj,null);
			 	// 			return false;
			 	// 		});
			 	// 		sCol2.appendTo(div2);


				var div = $('.subcategory-div .subcategory-lists').append('<div class="row"></div>');

				//for category -> menu
				// formData = 'page='+page;
				formData = 'ttype='+ttype+'&page='+page;
			 	$.post(baseUrl+'cashier/get_menus_subcat_sorted/'+id,formData,function(data){
			 		// alert(data);
					// var div = $('.menus-div .items-lists').append('<div class="row"></div>');
					if(data.show_img == 1){
				 		$.each(data.json,function(key,opt){

				 			// var sCol = $('<div class="col-md-3" style="margin-top:10px;position:relative;text-align: center;color: yellow; border-right-style:groove;"></div>');
				 			// $('<img src='+baseUrl+'uploads/menus/10.jpg width="130" height="90" id="menu-'+menu_id+'"><div style="position: absolute;bottom:2px;left: 7px;">'+opt.name+'</div>')
				 			var menu_id = opt.id;
				 			// var sCol = $('<div class="col-md-3"></div>');
				 			// $('<button/>')
				 			// .attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'','style':'height:100px; width:130px; background : url('+baseUrl+opt.img_path+'); background-size: cover; background-repeat: no-repeat; font-size:15px; margin-top:5px; color:yellow; vertical-align:text-bottom;'})
				 			var sCol = $('<div class="col-md-3" style="margin-top:10px;position:relative;text-align: center;color: white; cursor:pointer; border-style:solid;"></div>');
				 			$('<img src='+baseUrl+opt.img_path+' width="130" height="90" id="menu-'+menu_id+'"><div style="position: absolute;bottom:1px;background-color:rgba(0,0,0,.6); width:100%; text-align:center;">'+opt.name+'</div>')
				 			// .text(opt.name)
				 			// .attr({'id':'menu-'+menu_id,'ref':menu_id,'class':''})
				 			// .text('sample name')
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				// alert($(this).attr('id'));

				 				// for 0 price rquest
				 				// if(opt.cost == 0){
				 				// 	$('.loads-div').hide();
				 				// 	$('#menu-id-hidden').val(menu_id);
				 				// 	$('#menu-cat-id-hidden').val(trans_id);
				 				// 	$('#menu-cat-name-hidden').val(other);
									// $('.price-div').show();
									// selectModMenu();
				 				// }else{


					 			// 	btn.goLoad();
					 			// 	if(opt.free == 1){
						 		// 		$.callManager({
						 		// 			success : function(){
									// 	 				addTransCart(menu_id,opt,btn);
						 		// 					  },
						 		// 			fail    : function(){
						 		// 						btn.goLoad({load:false});
						 		// 					  },
						 		// 			cancel  : function(){
						 		// 						btn.goLoad({load:false});
						 		// 					  }		  	
						 		// 		});
					 			// 	}
					 			// 	else{
					 			// 		addTransCart(menu_id,opt,btn);
					 			// 	}
					 			// // }
				 				// return false;

				 				if(opt.neg_inv == '0'){
				 					// hindi pede negative
				 					if(opt.menu_oh <= 0){
				 						rMsg(opt.name+' is out of stock.','error');
				 						return false;
				 					}else{
				 						
				 						if(opt.check_reorder == 1){
					 						if(opt.reorder_qty != 0){
						 						if(opt.menu_oh > 0){
								 					if(opt.menu_oh <= opt.reorder_qty){
								 						rMsg(opt.name+' inventory is near to out of stock.','warning');
								 					}
						 						}else{
						 							rMsg(opt.name+' inventory is in negative stock.','warning');
						 						}

							 				}
							 			}

							 			// for 0 price rquest
				 						// if(opt.cost  == 0){
						 				// 	$('.loads-div').hide();
						 				// 	$('#menu-id-hidden').val(menu_id);
						 				// 	$('#menu-cat-id-hidden').val(id);
						 				// 	$('#menu-cat-name-hidden').val(cat_name);
											// $('.price-div').show();
											// selectModMenu();
						 				// }else{
							 				btn.goLoad();
							 				if(opt.free == 1){
								 				$.callManager({
								 					success : function(){
												 				addTransCart(menu_id,opt,btn);
								 							  },
								 					fail    : function(){
								 								btn.goLoad({load:false});
								 							  },
								 					cancel  : function(){
								 								btn.goLoad({load:false});
								 							  }		  	
								 				});
							 				}
							 				else{
							 					// alert('a');
							 					addTransCart(menu_id,opt,btn);
							 				}
							 			// }
						 				return false;
				 					}
				 				}else{
				 					// alert('as');
					 				if(opt.check_reorder == 1){
						 				if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else if(opt.menu_oh == 0){
					 							rMsg(opt.name+' inventory is out of stock.','warning');
					 						}else{
					 							// alert(opt.menu_oh);
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}

					 				// for 0 price rquest
					 				// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(id);
					 				// 	$('#menu-cat-name-hidden').val(cat_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{
						 				btn.goLoad();
						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt,btn);
							 							  },
							 					fail    : function(){
							 								btn.goLoad({load:false});
							 							  },
							 					cancel  : function(){
							 								btn.goLoad({load:false});
							 							  }		  	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt,btn);
						 				}
						 			// }
					 				return false;
				 				}



				 			});
				 			sCol.appendTo(div);
				 			
				 		});

					}else{
						$.each(data.json,function(key,opt){


				 			var menu_id = opt.id;
				 			// var sCol = $('<div class="col-md-3"></div>');
				 			// $('<button/>')
				 			// .attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'','style':'height:100px; width:130px; background : url('+baseUrl+'uploads/menus/2.jpg); background-size: cover; background-repeat: no-repeat; font-size:15px; margin-top:5px; color:yellow; vertical-align:text-bottom;'})
				 			var sCol = $('<div class="col-md-4 col-lg-3" style=""></div>');
			 				$('<button/>')
			 				.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default media-order-btns','style':'color:'+opt.branch_text_color+'!important;background-color:#43B0F0!important'})
			 				.text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				// alert($(this).attr('id'));

				 				// for 0 price rquest
				 				// if(opt.cost == 0){
				 				// 	$('.loads-div').hide();
				 				// 	$('#menu-id-hidden').val(menu_id);
				 				// 	$('#menu-cat-id-hidden').val(trans_id);
				 				// 	$('#menu-cat-name-hidden').val(other);
									// $('.price-div').show();
									// selectModMenu();
				 				// }else{


					 			// 	btn.goLoad();
					 			// 	if(opt.free == 1){
						 		// 		$.callManager({
						 		// 			success : function(){
									// 	 				addTransCart(menu_id,opt,btn);
						 		// 					  },
						 		// 			fail    : function(){
						 		// 						btn.goLoad({load:false});
						 		// 					  },
						 		// 			cancel  : function(){
						 		// 						btn.goLoad({load:false});
						 		// 					  }		  	
						 		// 		});
					 			// 	}
					 			// 	else{
					 			// 		addTransCart(menu_id,opt,btn);
					 			// 	}
					 			// // }
				 				// return false;

				 				if(opt.neg_inv == '0'){
				 					// hindi pede negative
				 					if(opt.menu_oh <= 0){
				 						rMsg(opt.name+' is out of stock.','error');
				 						return false;
				 					}else{
				 						
				 						if(opt.check_reorder == 1){
					 						if(opt.reorder_qty != 0){
						 						if(opt.menu_oh > 0){
								 					if(opt.menu_oh <= opt.reorder_qty){
								 						rMsg(opt.name+' inventory is near to out of stock.','warning');
								 					}
						 						}else{
						 							rMsg(opt.name+' inventory is in negative stock.','warning');
						 						}

							 				}
							 			}

							 			// for 0 price rquest
				 						// if(opt.cost  == 0){
						 				// 	$('.loads-div').hide();
						 				// 	$('#menu-id-hidden').val(menu_id);
						 				// 	$('#menu-cat-id-hidden').val(id);
						 				// 	$('#menu-cat-name-hidden').val(cat_name);
											// $('.price-div').show();
											// selectModMenu();
						 				// }else{
							 				btn.goLoad();
							 				if(opt.free == 1){
								 				$.callManager({
								 					success : function(){
												 				addTransCart(menu_id,opt,btn);
								 							  },
								 					fail    : function(){
								 								btn.goLoad({load:false});
								 							  },
								 					cancel  : function(){
								 								btn.goLoad({load:false});
								 							  }		  	
								 				});
							 				}
							 				else{
							 					// alert('a');
							 					addTransCart(menu_id,opt,btn);
							 				}
							 			// }
						 				return false;
				 					}
				 				}else{
				 					// alert('as');
					 				if(opt.check_reorder == 1){
						 				if(opt.reorder_qty != 0){
					 						if(opt.menu_oh > 0){
							 					if(opt.menu_oh <= opt.reorder_qty){
							 						rMsg(opt.name+' inventory is near to out of stock.','warning');
							 					}
					 						}else if(opt.menu_oh == 0){
					 							rMsg(opt.name+' inventory is out of stock.','warning');
					 						}else{
					 							// alert(opt.menu_oh);
					 							rMsg(opt.name+' inventory is in negative stock.','warning');
					 						}

						 				}
					 				}

					 				// for 0 price rquest
					 				// if(opt.cost  == 0){
					 				// 	$('.loads-div').hide();
					 				// 	$('#menu-id-hidden').val(menu_id);
					 				// 	$('#menu-cat-id-hidden').val(id);
					 				// 	$('#menu-cat-name-hidden').val(cat_name);
										// $('.price-div').show();
										// selectModMenu();
					 				// }else{
						 				btn.goLoad();
						 				if(opt.free == 1){
							 				$.callManager({
							 					success : function(){
											 				addTransCart(menu_id,opt,btn);
							 							  },
							 					fail    : function(){
							 								btn.goLoad({load:false});
							 							  },
							 					cancel  : function(){
							 								btn.goLoad({load:false});
							 							  }		  	
							 				});
						 				}
						 				else{
						 					addTransCart(menu_id,opt,btn);
						 				}
						 			// }
					 				return false;
				 				}



				 			});
				 			sCol.appendTo(div);			 				
				 			
				 		});
					}


			 	// });
			 	},'json');


				$('.subcategory-div .subcategory-lists').after('<div class="scrollers-subcategory media-subcategory-scroller"><div class="row"><div class="col-md-4 text-left"><button id="previous-subcategory-btn" class="btn-block counter-btn-red-gray double btn btn-default media-btn-height" style="background-color:#993300!important;width:130px"><i class="fa fa-fw fa-reply fa-2x media-previous"> </i><span style="font-size:15px"> PREVIOUS</span></button></div><div class="col-md-4 text-left"><button id="subcategory-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:130px"><i class="fa fa-fw fa-chevron-left fa-2x fa-fw"></i><span class="media-back">BACK</span></button></div><div class="col-md-4 text-left"><button id="subcategory-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:130px"><span class="media-next-sub-cat">NEXT</span><i class="fa fa-fw fa-chevron-right fa-2x fa-fw"></i></button></div></div></div>');
		 		$("#previous-subcategory-btn").on("click" ,function(){
					var obj = {
			        	'name':other
				    };
					loadsDiv('menus',trans_id,obj,null);
				});

		 		$("#subcategory-item-scroll-down").on("click" ,function(){
		 		 //    scrolled=scrolled+100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });

 				 //    var inHeight = $(".subcategory-lists")[0].scrollHeight;
 				 //    var divHeight = $(".subcategory-lists").height();
 				 //    var trueHeight = inHeight - divHeight;
 			  //       if((scrolled + 150) > trueHeight){
 			  //       	scrolled = trueHeight;
 			  //       }
 			  //       else{
 			  //   	    scrolled=scrolled+150;				    	
 			  //       }
 				 //    // scrolled=scrolled+100;
 					// $(".subcategory-lists").animate({
 					//         scrollTop:  scrolled
 					// },200);
 					subcat_menu_page = $('#subcat_menu_page').val();
 					

					formData = 'subcat_id='+id;
 					$.post(baseUrl+'cashier/check_next_page/'+subcat_menu_page+'/subcategory',formData,function(data){
	 					// alert(data);
 						if(data == 'yes'){
		 					page = Number(subcat_menu_page) + 1;
		 					$('#subcat_menu_page').val(page);
		 					// function loadsDiv(type,id,opt,trans_id,other,mandatory,page=1){
		 					// alert(page);
							loadsDiv('subcategory',id,opt,trans_id,other,null,page);
 						}
 					});


 					
		 		});
		 		$("#subcategory-item-scroll-up").on("click" ,function(){
		 			// scrolled=scrolled-100;
		 			// $(".items-lists").animate({
		 			//         scrollTop:  scrolled
		 			// });
		 			// if(scrolled > 0){
		 			// 	scrolled=scrolled-150;
		 			// 	$(".subcategory-lists").animate({
		 			// 	        scrollTop:  scrolled
		 			// 	},200);
		 			// }

		 			subcat_menu_page = $('#subcat_menu_page').val();
 					page = Number(subcat_menu_page) - 1;
 					if(page >= 1){
	 					$('#subcat_menu_page').val(page);
	 					// function loadsDiv(type,id,opt,trans_id,other,mandatory,page=1){
						loadsDiv('subcategory',id,opt,trans_id,other,null,page);
					}

		 		});

		 		// $('#subcat-back').click(function(){
		 		// 	// ops[] = other;
		 		// 	var obj = {
				 //        'name':other
				 //    };
		 		// 	loadsDiv('menus',trans_id,obj,null);
		 		// 	return false;
		 		// });

			}
			else if(type=='mods'){
				remeload('menu');
				$('.mods-div .title').text(opt.name+" Modifiers");
				$('.mods-div .mods-lists').html('');
				$('#submod_seq').val(1);
				var trans_det = opt;

				ttype = $('#ttype').val();

				var formData = 'menu_name='+trans_det.name+'&ttype='+ttype;
				if(other == "addModDefault"){
					formData += '&add_defaults=1';
				}	
				$.post(baseUrl+'cashier/get_menu_modifiers_wth_dflt/'+id+'/'+trans_id,formData,function(data){
					// alert(data);
					// console.log(data);

					var modGRP = data.group;
					var dfltGRP = data.dflts;
					var dfltSub = data.dflts2;

	
					if(!$.isEmptyObject(modGRP)){
						modgrp_cnt = 0;
						$.each(modGRP,function(mod_group_id,opt){
							modgrp_cnt++;
						});

						// $.each(dfltGRP,function(trans_mod_id,mopt){
						// 	modgrp_cnt++;

						// });
						// alert(modgrp_cnt);

						$('.loads-div').hide();
						$('.'+type+'-div').show();
						// alert(opt);
						var mandatory_div = mandatory;
						seq = 1;
						$.each(modGRP,function(mod_group_id,opt){
						// alert(nicko_test);
							if(seq == 1){
								style = '';
							}else{
								style = 'display:none;';
							}


							var row = $('<div/>').attr({'class':'mod-group mod-grp-'+seq,'id':'mod-group-'+mod_group_id,'style':style}).appendTo('.mods-div .mods-lists');
							
							// default_count = 0;
							// total_mod = 0;
							// $.each(opt.details,function(mod_id,det){
							// 	total_mod++;
							// 	if(det.default == 1){
							// 		default_count++;
							// 	}
							// });

							// alert(default_count);
							if(other != 'back_sub'){
								var set_qty=1;

								if($('#num-qty').val() != ''){
									  set_qty = $('#num-qty').val();
								}

								if(opt.mandatory == "1" && mandatory_div == "1"){
									$('<input/>').attr({
										'type':'hidden',
										'value':opt.name,
										'id':'mod-group-name-'+mod_group_id,
										'class':'mod-group-name'
									}).appendTo('#mods_mandatory_div');
									$('<input/>').attr({
										'type':'hidden',
										'value':opt.min_no*set_qty,
										'id':'mods-mandatory-'+mod_group_id,
										'class':'mods-mandatory'
									}).appendTo('#mods_mandatory_div');
								}
								$('#mods-mandatory-'+mod_group_id).val(opt.min_no*set_qty);

							}
							
							// if(default_count < total_mod){

								$('<h4/>').text(opt.name)
										  .addClass('text-center receipt media-mod-title')
										  .css({'margin-bottom':'5px'})
										  .appendTo('#mod-group-'+mod_group_id);
								var mandatory = opt.mandatory;
								var multiple = opt.multiple;
								// alert(mandatory);

								var div = $('#mod-group-'+mod_group_id);
								var divRow = $('<div/>').attr({'class':'row'});
								// var div = $('#mod-group-'+mod_group_id).append('<div class="row"></div>');
								$.each(opt.details,function(mod_id,det){

									// alert(det.default);
									if(det.default != 1){

										var sCol = $('<div class="col-md-3 media-margin-mods"></div>');
										$('<button/>')
										.attr({'id':'mod-'+mod_id,'ref':mod_id,'class':'counter-btn-silver btn btn-block btn-default mods-btns media-ui-mods-btn','style':'color:'+opt.branch_text_color+'!important;'})
										// .css({'margin':'5px','width':'130px'})
										.text(det.name)
										.appendTo(sCol)
										.click(function(){

											$('.mods-btns').css("background-color", "#43B0F0!important");

						 					$('#mod-'+mod_id).css("background-color", "#999");

						 					var set_qty =1;

											if($('#num-qty').val() != ''){
												  set_qty = $('#num-qty').val();
												  $('#num-qty').val('');
												  $('#up-txt').text('');
											}

						 					// alert('aw');
						 					var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
											// var man_checker = mandatory_num -1;
											var man_checker = mandatory_num -set_qty;
											$('#mods-mandatory-'+mod_group_id).val(man_checker);
											if(man_checker <= "0"){
												$('#mod-group-name-'+mod_group_id).remove();
												$('#mods-mandatory-'+mod_group_id).remove();
											}											

											modformData = 'trans_id='+trans_id+'&mod_id='+mod_id+'&mod_group_id='+mod_group_id+'&qty='+set_qty;

											det.qty = set_qty;

											$.post(baseUrl+'cashier/check_modifier_count',modformData,function(data2){
												
												if(data2.status == 'success'){
													// for(var i=1;i<=set_qty;i++){
														addTransModCart(trans_id,mod_group_id,mod_id,det,id,$(this),trans_det,opt.mandatory,multiple,opt.name,opt.min_no,modgrp_cnt);
													// }
												}else{
													alert(data2.message);
												}
												
											},'json');
											
											
											


											// loadsDiv('submods',mod_id,det,trans_id,"","0");
											// check if lilipat na ng div
											// curr_mod_seq = $('#mod_seq').val();
											// // alert(curr_mod_seq);
											// formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_seq_last='+modgrp_cnt+'&curr_mod_seq='+curr_mod_seq;
											// $.post(baseUrl+'cashier/check_modifier_grp',formData,function(data){

											// 	// console.log(data);
											// 	// alert(data.status);

											// 	if(data.status == 'next_div'){
											// 		mod_seq = $('#mod_seq').val();
											// 		$('.mod-group').hide();
											// 		next_seq = Number(mod_seq) + 1;
											// 		$('.mod-grp-'+next_seq).show();
											// 		$('#mod_seq').val(next_seq);

											


											// 	}else if(data.status == 'finish'){
											// 		$('.mods-mandatory').val(0);
											// 		loadsDiv('menus',data.cat_id,data.obj);
											// 	}
												// if()

											// });
											// },'json');


											return false;
										});
						 				sCol.appendTo(divRow);
						 				$('.scrollers-mods').css("display", 'none');
						 			}
					 			});
					 			div.append(divRow);
					 			// $('<hr/>').appendTo('#mod-group-'+mod_group_id);
					 		// }
					 			seq++;
				 		});
						$('.mods-div .mods-lists').after('<div class="scrollers-mods mod-scroll-width"><div class="row"><div class="col-md-3 text-left"><button id="previous-mod-btn" class="btn-block counter-btn-red-gray double btn btn-default media-mod-btns media-btn-height" style="background-color:#993300!important;width:163px"><i class="fa fa-fw fa-reply fa-2x media-previous"></i><span style="font-size:15px"> PREVIOUS</span> </button></div><div class="col-md-3 text-left"><button id="mods-item-scroll-up" class="btn-block counter-btn-orange double btn btn-default media-mod-btns media-btn-height" style="background-color:#ED7D31!important;padding-top: 6px;width:163px"><i class="fa fa-fw fa-chevron-left media-back-icon "></i><span class="media-back">BACK</span></button></div><div class="col-md-3 text-left"><button id="mods-item-scroll-down" class="btn-block counter-btn-yellow double btn btn-default media-mod-btns media-btn-height" style="background-color:#FFC000!important;padding-top: 6px;width:163px"><span class="media-next">NEXT</span> <i class="fa fa-chevron-right fa-fw media-next-icon"></i></button></div></div></div>');
				 		$("#previous-mod-btn").on("click" ,function(){

				 			var mods_mandatory = $('.mods-mandatory').val();
							// alert(mods_mandatory);
							if(mods_mandatory >= "1"){
								var mod_name = $('.mod-group-name').val();
								rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
								return false;
							}

				 			$('#menu_categories_page').val(1);
 							loadMenuCategories();
				 		});
				 		$("#mods-item-scroll-down").on("click" ,function(){
				 		 //    scrolled=scrolled+100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });

		 				    var inHeight = $(".mods-lists")[0].scrollHeight;
		 				    var divHeight = $(".mods-lists").height();
		 				    var trueHeight = inHeight - divHeight;
		 			        if((scrolled + 150) > trueHeight){
		 			        	scrolled = trueHeight;
		 			        }
		 			        else{
		 			    	    scrolled=scrolled+150;				    	
		 			        }
		 				    // scrolled=scrolled+100;
		 					$(".mods-lists").animate({
		 					        scrollTop:  scrolled
		 					},200);
				 		});
				 		$("#mods-item-scroll-up").on("click" ,function(){
				 			// scrolled=scrolled-100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });
				 			if(scrolled > 0){
				 				scrolled=scrolled-150;
				 				$(".mods-lists").animate({
				 				        scrollTop:  scrolled
				 				},200);
				 			}
				 		});
					}
					// alert('we');
					if(!$.isEmptyObject(dfltGRP)){
						$.each(dfltGRP,function(trans_mod_id,mopt){
							var set_qty=1;

							if($('#num-qty').val() != ''){
								  set_qty = $('#num-qty').val();
							}

							var mandatory_num = $('#mods-mandatory-'+mopt.mod_group_id).val();
							var man_checker = mandatory_num -set_qty;
							$('#mods-mandatory-'+mopt.mod_group_id).val(man_checker);
							if(man_checker <= "0"){
								$('#mod-group-name-'+mopt.mod_group_id).remove();
								$('#mods-mandatory-'+mopt.mod_group_id).remove();
							}
							makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,trans_det,"","default");

							if(!$.isEmptyObject(dfltSub)){
								// alert('aw');
								$.each(dfltSub,function(mod_sub_id,opt1){
									opt1.qty=set_qty;
									// var mandatory_num = $('#mods-mandatory-'+mopt.mod_group_id).val();
									// var man_checker = mandatory_num -1;
									// $('#mods-mandatory-'+mopt.mod_group_id).val(man_checker);
									// if(man_checker <= "0"){
									// 	$('#mod-group-name-'+mopt.mod_group_id).remove();
									// 	$('#mods-mandatory-'+mopt.mod_group_id).remove();
									// }
									// makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,trans_det,"","default");
									makeItemSubSubRow(mod_sub_id,trans_id,mopt.mod_id,opt1,trans_det,'',trans_mod_id);
								});	
							}

						});	
					}

					$('#num-qty').val('');
					$('#up-txt').text('');
			 	// });
			 	},'json');
			}
			else if(type=='submods'){
				remeload('menu');
				$('.submods-div .title').text(opt.name+" SubModifier");
				$('.submods-div .submods-lists').html('');
				var trans_det = opt;

				ttype = $('#ttype').val();

				var formData = 'ttype='+ttype;
				// if(other == "addModDefault"){
				// 	formData += '&add_defaults=1';
				// }
				$.post(baseUrl+'cashier/get_menu_submodifiers/'+id+'/'+trans_id+'/'+other,formData,function(data){


					// alert(data);
					// console.log(data);
					var submod = data.group;
					var dfltSub = data.dflts;
					var grouping = data.grouping;
	
					if(!$.isEmptyObject(submod)){
						$('.loads-div').hide();
						$('.'+type+'-div').show();

						var row = $('<div/>').attr({'class':'mod-sub','id':'mod-subs'}).appendTo('.submods-div .submods-lists');
						// var div = $('#mod-subs');
						// var divRow = $('<div/>').attr({'class':'row'});
						seq = 1;
						submodgrp_cnt = 0;
						$.each(grouping,function(group_name,gopt){
							// alert(group_name);
							// $('.mods-div .title').text(opt.name+" Modifiers");
							
							if(gopt.auto_cnt == gopt.qty){

							}else{
								if(seq == 1){
									style = '';
								}else{
									style = 'display:none;';
								}

								var g_row = $('<div/>').attr({'class':'grp-divs col-md-12 grp-div-'+seq,'id':'grp-div-'+gopt.group_id,'grp_qty':gopt.qty,'style':'height:auto;color:#fff;'+style}).appendTo(row);
								var title_row = $('<h3/>').attr({'class':'title text-center','style':'color:#fff','id':'grp-subs-'+gopt.group_id}).text(group_name).appendTo(g_row);

								seq++;
								submodgrp_cnt++;
							}

							
						});

						$.each(submod,function(mod_sub_id,opt){
								// alert(mod_sub_id);
								// alert(nicko_test);
								// if(opt.mandatory == "1" && mandatory_div == "1"){
								// 	$('<input/>').attr({
								// 		'type':'hidden',
								// 		'value':opt.name,
								// 		'id':'mod-group-name-'+mod_group_id,
								// 		'class':'mod-group-name'
								// 	}).appendTo('#mods_mandatory_div');
								// 	$('<input/>').attr({
								// 		'type':'hidden',
								// 		'value':opt.min_no,
								// 		'id':'mods-mandatory-'+mod_group_id,
								// 		'class':'mods-mandatory'
								// 	}).appendTo('#mods_mandatory_div');
								// }
								// $('#mods-mandatory-'+mod_group_id).val(opt.min_no);
								
								// $('<h4/>').text(opt.name)
								// 		  .addClass('text-center receipt')
								// 		  .css({'margin-bottom':'5px'})
								// 		  .appendTo('#mod-sub-'+mod_sub_id);
								// // var mandatory = opt.mandatory;
								// // var multiple = opt.multiple;

								// var div = $('#mod-sub-'+mod_sub_id);
								// var divRow = $('<div/>').attr({'class':'row'});
								// var div = $('#mod-group-'+mod_group_id).append('<div class="row"></div>');
								// $.each(opt.details,function(mod_id,det){

									var sCol = $('<div class="col-md-4 col-lg-3"></div>');
									$('<button/>')
									.attr({'id':'submod-'+mod_sub_id,'ref':mod_sub_id,'class':'counter-btn-silver btn btn-block btn-default submods-btns media-submods-btns counter','style':'color:'+opt.branch_text_color+'!important;background-color:#43B0F0;height:60px;'})
									// .css({'margin':'5px','width':'130px'})
									.text(opt.name)
									.appendTo(sCol)
									.click(function(){

										sgrp_qty = $('#grp-div-'+opt.group_id).attr('grp_qty');
										// alert(sgrp_qty);

										$('.submods-btns').css("background-color", "#43B0F0!important");

					 					$('#submod-'+mod_sub_id).css("background-color", "#999");
										// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
										// var man_checker = mandatory_num -1;
										// $('#mods-mandatory-'+mod_group_id).val(man_checker);
										// if(man_checker <= "0"){
										// 	$('#mod-group-name-'+mod_group_id).remove();
										// 	$('#mods-mandatory-'+mod_group_id).remove();
										// }

										var set_qty =1;

										if($('#num-qty').val() != ''){
											  set_qty = $('#num-qty').val();
											  $('#num-qty').val('');
											  $('#up-txt').text('');
										}

										submodformData = 'trans_id='+trans_id+'&mod_id='+id+'&group_id='+opt.group_id+'&mod_line_id='+other+
											'&qty='+set_qty+'&grp_qty='+sgrp_qty;

										opt.qty = set_qty;

										$.post(baseUrl+'cashier/check_submodifier_count',submodformData,function(data2){

											// alert(data2);
											
											if(data2.status == 'success'){
												// for(var i=1;i<=set_qty;i++){ 
													addTransSubModCart(trans_id,mod_sub_id,id,opt,$(this),trans_det,other,submodgrp_cnt,sgrp_qty);
													// alert('aw');

													// curr_submod_seq = $('#submod_seq').val();
													// formData = 'trans_id='+trans_id+'&mod_id='+id+'&submod_seq_last='+submodgrp_cnt+'&curr_submod_seq='+curr_submod_seq+'&group_id='+opt.group_id+'&mod_line_id='+other;
													// // alert(formData);
													// $.post(baseUrl+'cashier/check_submodifier_grp',formData,function(data3){

													// 	// console.log(data);
													// 	// alert(data);
													// 	if(data3.status == 'next_div'){
													// 		submod_seq = $('#submod_seq').val();
													// 		$('.grp-divs').hide();
													// 		next_seq = Number(submod_seq) + 1;
													// 		$('.grp-div-'+next_seq).show();
													// 		$('#submod_seq').val(next_seq);

													// 		// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
													// 		// var man_checker = mandatory_num -1;
													// 		// $('#mods-mandatory-'+mod_group_id).val(man_checker);
													// 		// if(man_checker <= "0"){
													// 		// 	$('#mod-group-name-'+mod_group_id).remove();
													// 		// 	$('#mods-mandatory-'+mod_group_id).remove();
													// 		// }


													// 	}else if(data3.status == 'finish'){
															
													// 		var mods_mandatory = $('.mods-mandatory').val();
															
													// 		if(mods_mandatory >= "1"){
													// 			// var mod_name = $('.mod-group-name').val();
													// 			// rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
													// 			// return false;
													// 			loadsDiv('mods',data3.menu_id,data3.obj,data3.trans_id,'back_sub');
													// 		}else{
													// 			loadsDiv('menus',data3.cat_id,'SAMPLE');
													// 		}
													// 		// checkModifierDone(trans_id,'submods');
													// 	}

													// // });
													// },'json');



												// }
											}else{
												alert(data2.message);
											}
											
										},'json');											
										// });											


										// loadsDiv('submods',mod_id,det,trans_id,"","0");
										// alert('aw');
										return false;
									});
					 				// sCol.appendTo(row);
					 				sCol.appendTo('#grp-div-'+opt.group_id);

					 			// });
					 		});


			 			// div.append(divRow);
			 			// $('<hr/>').appendTo('#mod-sub-'+mod_sub_id);
						
					}else{
						//no submod
						// alert('sssddddd');
					}

					if(!$.isEmptyObject(dfltSub)){
						$.each(dfltSub,function(mod_sub_id,opt1){
							// var mandatory_num = $('#mods-mandatory-'+mopt.mod_group_id).val();
							// var man_checker = mandatory_num -1;
							// $('#mods-mandatory-'+mopt.mod_group_id).val(man_checker);
							// if(man_checker <= "0"){
							// 	$('#mod-group-name-'+mopt.mod_group_id).remove();
							// 	$('#mods-mandatory-'+mopt.mod_group_id).remove();
							// }
							// makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,trans_det,"","default");
							makeItemSubSubRow(mod_sub_id,trans_id,id,opt1,trans_det,'',other);
						});	
					}

				// });
				},'json');


				// alert(id);
			}
			else if(type=='qty'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='remarks'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='charges'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
			else if(type=='sel-discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='choosedisc'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else{
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
		}
		var promo_ctr = 0;
		function addTransCart(menu_id,opt,btn){
			$('#mod_seq').val(1);
			ttype = $('#ttype').val();
			var cost = opt.cost;
			var goOn = false;
			if($('#buy2take1').exists()){				
				if($('#counter').hasClass('on-promo-choose')){
					var max_qty = parseFloat($('#counter').attr('promo-qty')); 
					if(promo_ctr < max_qty){
						cost = 0;
						promo_ctr++;
						if(promo_ctr == max_qty){
							goOn = true;
						}
					}
				}
			}

			if(!$('#take-all-btn').hasClass('take-all-active')){
				is_takeout = 0;
			}
			else{
				is_takeout = 1;
				// alert('aw');
				// addTakeoutChargeCart();
			}

			// alert(nocharge);
			// formData = 'ttype='+ttype+'&is_takeout='+is_takeout;
			// $.post(baseUrl+'cashier/check_charges',formData,function(data){
				// alert(data);
				var nocharge = 0;
				var charge_code = '';

				var set_qty=1;

				if($('#num-qty').val() != ''){
					  set_qty = $('#num-qty').val();
					  // $('#num-qty').val('');
					  // $('#up-txt').text('');
				}

				// var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+cost+'&no_tax='+opt.no_tax+'&qty=1&nocharge='+nocharge+'&is_takeout='+is_takeout;

				var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+cost+'&no_tax='+opt.no_tax+'&qty='+set_qty+'&nocharge='+nocharge+'&is_takeout='+is_takeout+'&category='+opt.category+'&category_name='+opt.category_name;
				var submit = $('#submit-btn');
				var settle = $('#settle-btn');
				var cash = $('#cash-btn');
				var credit = $('#credit-btn');
				$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
					// alert(nocharge);
					// alert(2);
					makeItemRow(data.id,menu_id,data.items,null,is_takeout,charge_code);

					var is_promo = false;

					// addTransPromo();

					// $.ajaxSetup({async: false});
					$.post(baseUrl+'cashier/promo_item_qty/'+data.id+'/'+$('#ttype').val(),function(promo_data){
							// makeItemRow(promo_data.id,menu_id,promo_data.items,null,is_takeout,charge_code);
							// alert(JSON.stringify(promo_data));	
							// alert(promo_data.price);								

							if(promo_data.ref_line != undefined){ 
								$('#trans-row-'+promo_data.ref_line+' .qty').text(promo_data.ref_qty);
								makeItemRow(promo_data.id,0,promo_data.items,'promo',is_takeout,'');
							}else if(promo_data.price){
								$('#trans-row-'+data.id+' .cost').text(promo_data.price);
								$('#trans-row-'+data.id).addClass('promo');
								is_promo = true;
							}else if(id != promo_data.id){  
								makeItemRow(promo_data.id,promo_data.free_menu,promo_data.items,'promo',is_takeout,charge_code);
									
								if(promo_data.sel_trans_id != undefined){
									$('#trans-row-'+promo_data.sel_trans_id).click();
								}
							}
							
							transTotal();
						},'json');

					// for popup qty

					// if(!is_promo){
					// 	swal({
					// 	  title: "Quantity",
					// 	  text: "Input quantity:",
					// 	  type: "input",
					// 	  showCancelButton: true,
					// 	  closeOnConfirm: false,
					// 	  animation: "slide-from-top",
					// 	  inputPlaceholder: "Input quantity",
					// 	  // inputClass: "swal-input"
					// 	},
					// 	function(inputValue){
					// 	  if (inputValue === null) return false;
						  
					// 	  if (inputValue === "" || !$.isNumeric(inputValue)) {
					// 	    swal.showInputError("Please input quantity");
					// 	    return false
					// 	  }

					// 	  check_inv = check_menu_qty(data.id,inputValue,'equal');
				
					// 		if(check_inv == true){
					// 			alert('Input quantity exceed available quantity');
					// 			btn.prop('disabled', false);
					// 			return false;
					// 		}
						  
					// 	  var formQty = 'value='+inputValue+'&operator=equal';
					// 	  $.post(baseUrl+'cashier/update_trans_qty/'+data.id,formQty,function(data2){ 
					// 	  		// alert($('#trans-row-'+data.id).length);

					// 			var qty = data2.qty;
					// 			$('#trans-row-'+data.id+' .qty').text(qty);
					// 			swal.close();
					// 		},'json');

					// 	  $.post(baseUrl+'cashier/promo_item_qty/'+data.id+'/'+$('#ttype').val(),function(promo_data){
					// 			// alert(id);
					// 			//  alert(promo_data.id);
					// 			if(promo_data.ref_line != undefined){ 
					// 				$('#trans-row-'+promo_data.ref_line+' .qty').text(promo_data.ref_qty);
					// 				makeItemRow(promo_data.id,0,promo_data.items,'promo',is_takeout,'');
					// 			}else if(promo_data.qty != undefined){
					// 				$('#trans-row-'+promo_data.id+' .qty').text(promo_data.qty);
					// 				$('#trans-row-'+promo_data.id).addClass('promo');
					// 			}else if(id != promo_data.id){  
					// 				makeItemRow(promo_data.id,promo_data.free_menu,promo_data.items,'promo',is_takeout,'');
					// 			}							
					// 		},'json');
					// 	  transTotal();
					// 	});
					// }

					// for popup qty end

					loadsDiv('mods',menu_id,data.items,data.id,"addModDefault","1");
					
					if(opt.promo_qty != opt.disc_qty){
						var id  =$('.selected').attr("ref");
						// var formData = 'disc_id='+opt.promo_id+'&disc_code='+opt.promo_code+'&rate='+opt.disc_rate+'&promo_qty='+opt.promo_qty+'&disc_qty='+opt.disc_qty;
						// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
						// 	transTotal();
						// });
					}					
					

					transTotal();

					if(goOn){
						$('body').goLoad2({loadTxt:'Loading...'});
						if($('#counter').hasClass('on-promo-submit')){
							submit.trigger('click');
							$('body').goLoad2({load:false});
							$('#promo-txt').hide();
							$('#counter').removeClass('on-promo-choose');
							$('#counter').removeClass('on-promo-submit');
							$('#counter').removeAttr('promo-qty');
							promo_ctr = 0;
						}
						else if($('#counter').hasClass('on-promo-settle')){
							settle.trigger('click');
						}
						else if($('#counter').hasClass('on-promo-cash')){
							cash.trigger('click');
						}
						else if($('#counter').hasClass('on-promo-credit')){
							credit.trigger('click');
						}
					}
					btn.goLoad({load:false});
				},'json');

				
				$('#cancel-search-btn').click();
			// },'json');
			// });
			
		}
		function addTransPromo(){
			$('.sel-row').each(function(){
				var ref_id = $(this).attr('ref');

				if(!$(this).hasClass('promo')){
					$.post(baseUrl+'cashier/promo_item_qty/'+ref_id+'/'+$('#ttype').val(),function(promo_data){
						// alert(id);
						//  alert(promo_data.id);
						if(promo_data.ref_line != undefined){ 
							$('#trans-row-'+promo_data.ref_line+' .qty').text(promo_data.ref_qty);
							makeItemRow(promo_data.id,0,promo_data.items,'promo',is_takeout,'');
						}else if(promo_data.qty != undefined){
							$('#trans-row-'+promo_data.id+' .qty').text(promo_data.qty);
							$('#trans-row-'+promo_data.id).addClass('promo');
						}else if(ref_id != promo_data.id){  
							makeItemRow(promo_data.id,promo_data.free_menu,promo_data.items,'promo',is_takeout,'');
						}
						
					},'json');
				}
				
			});

		}
		function addTransCart_price(menu_id,opt,menu_price,btn){
			var cost = menu_price;
			var goOn = false;
			if($('#buy2take1').exists()){				
				if($('#counter').hasClass('on-promo-choose')){
					var max_qty = parseFloat($('#counter').attr('promo-qty')); 
					if(promo_ctr < max_qty){
						cost = 0;
						promo_ctr++;
						if(promo_ctr == max_qty){
							goOn = true;
						}
					}
				}
			}			
			var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+cost+'&no_tax='+opt.no_tax+'&qty=1';
			var submit = $('#submit-btn');
			var settle = $('#settle-btn');
			var cash = $('#cash-btn');
			var credit = $('#credit-btn');
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,menu_id,data.items);
				loadsDiv('mods',menu_id,data.items,data.id,"addModDefault");
				transTotal();
				if(goOn){
					$('body').goLoad2({loadTxt:'Loading...'});
					if($('#counter').hasClass('on-promo-submit')){
						submit.trigger('click');
						$('body').goLoad2({load:false});
						$('#promo-txt').hide();
						$('#counter').removeClass('on-promo-choose');
						$('#counter').removeClass('on-promo-submit');
						$('#counter').removeAttr('promo-qty');
						promo_ctr = 0;
					}
					else if($('#counter').hasClass('on-promo-settle')){
						settle.trigger('click');
					}
					else if($('#counter').hasClass('on-promo-cash')){
						cash.trigger('click');
					}
					else if($('#counter').hasClass('on-promo-credit')){
						credit.trigger('click');
					}
				}
				btn.goLoad({load:false});
			},'json');
			
		}
		function addRetailTransCart(item_id,opt){
			var nocharge = 0;
			if(!$('#take-all-btn').hasClass('take-all-active')){
				nocharge = 0;
			}
			else{
				nocharge = 1;
			}

			var formData = 'menu_id='+item_id+'&name='+opt.name+'&cost='+opt.cost+'&no_tax=0&qty=1&retail=1&nocharge='+nocharge;
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,item_id,data.items,null,nocharge);
				// loadsDiv('mods',menu_id,data.items,data.id);
				transTotal();
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				$('#scan-code').focus();
			},'json');
		}
		function addTransModCart(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det,mandatory,multiple,mod_name,mod_min_no,modgrp_cnt){
			// var mod_qty = $('#trans-row-'+trans_id+' .qty').text();
			var formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det.name
							+'&mandatory='+mandatory
							+'&multiple='+multiple
							+'&name='+opt.name+'&cost='+opt.cost+'&qty='+opt.qty;
							// +'&name='+opt.name+'&cost='+opt.cost+'&qty='+mod_qty;
			// console.log(formData);
			if(btn != null){
				btn.prop('disabled', true);				
			}
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det,null,null,mod_name,mandatory,mod_group_id,mod_min_no);


					// check if lilipat na ng div
					curr_mod_seq = $('#mod_seq').val();
					// alert(curr_mod_seq);
					formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_seq_last='+modgrp_cnt+'&curr_mod_seq='+curr_mod_seq+'&mod_id='+mod_id;
					$.post(baseUrl+'cashier/check_modifier_grp',formData,function(data2){

						// console.log(data);
						// alert(data.status);

						if(data2.status == 'next_div'){
							mod_seq = $('#mod_seq').val();
							$('.mod-group').hide();
							next_seq = Number(mod_seq) + 1;
							$('.mod-grp-'+next_seq).show();
							$('#mod_seq').val(next_seq);

							// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
							// var man_checker = mandatory_num -1;
							// $('#mods-mandatory-'+mod_group_id).val(man_checker);
							// if(man_checker <= "0"){
							// 	$('#mod-group-name-'+mod_group_id).remove();
							// 	$('#mods-mandatory-'+mod_group_id).remove();
							// }


						}else if(data2.status == 'finish'){
							$('.mods-mandatory').val(0);
							loadsDiv('menus',data2.cat_id,data2.obj);
						}else if(data2.status == 'enter_submod'){
							loadsDiv('submods',mod_id,opt,trans_id,data.id,"0");
						}

					// });
					},'json');
					

					// checkModifierDone(trans_id,'mods');
				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// });
		}
		function addTransSubModCart(trans_id,mod_sub_id,mod_id,opt,btn,trans_det,other,submodgrp_cnt,submodgrp_qty){
			var formData = 'trans_id='+trans_id+'&mod_sub_id='+mod_sub_id
							// +'&mandatory='+mandatory
							+'&mod_id='+mod_id
							+'&mod_line_id='+other
							+'&name='+opt.name+'&price='+opt.price+'&qty='+opt.qty+'&group='+opt.group+'&group_id='+opt.group_id;
			// console.log(formData);
			// alert(formData);
			if(btn != null){
				btn.prop('disabled', true);				
			}
			$.post(baseUrl+'cashier/add_trans_submodifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					// alert(data.id);
					// alert(data.id);
					makeItemSubSubRow(data.id,trans_id,mod_id,opt,trans_det,'',other,opt.group);
					// checkModifierDone(trans_id,'submods');

					curr_submod_seq = $('#submod_seq').val();
					formData = 'trans_id='+trans_id+'&mod_id='+mod_id+'&submod_seq_last='+submodgrp_cnt+'&curr_submod_seq='+curr_submod_seq+'&group_id='+opt.group_id+'&mod_line_id='+other+'&submodgrp_qty='+submodgrp_qty;
					// alert(formData);
					$.post(baseUrl+'cashier/check_submodifier_grp',formData,function(data3){

						// console.log(data);
						// alert(data);
						if(data3.status == 'next_div'){
							submod_seq = $('#submod_seq').val();
							$('.grp-divs').hide();
							next_seq = Number(submod_seq) + 1;
							$('.grp-div-'+next_seq).show();
							$('#submod_seq').val(next_seq);

							// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
							// var man_checker = mandatory_num -1;
							// $('#mods-mandatory-'+mod_group_id).val(man_checker);
							// if(man_checker <= "0"){
							// 	$('#mod-group-name-'+mod_group_id).remove();
							// 	$('#mods-mandatory-'+mod_group_id).remove();
							// }


						}else if(data3.status == 'finish'){
							
							var mods_mandatory = $('.mods-mandatory').val();
							
							if(mods_mandatory >= "1"){
								// var mod_name = $('.mod-group-name').val();
								// rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
								// return false;
								loadsDiv('mods',data3.menu_id,data3.obj,data3.trans_id,'back_sub');
							}else{
								loadsDiv('menus',data3.cat_id,'SAMPLE');
							}
							// checkModifierDone(trans_id,'submods');
						}

					// });
					},'json');

				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// console.log(data);
			// });
		}

		function addTransModCartRemove(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det,mandatory,multiple,mod_name,mod_min_no,modgrp_cnt,qty=1){
			// var mod_qty = $('#trans-row-'+trans_id+' .qty').text();
			$('#submod_seq').val(1);
			var formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det
							+'&mandatory='+mandatory
							+'&multiple='+multiple
							+'&name='+opt.name+'&cost='+opt.cost+'&qty='+opt.qty;
							// +'&name='+opt.name+'&cost='+opt.cost+'&qty='+mod_qty;
			// console.log(formData);
			// if(btn != null){
			// 	btn.prop('disabled', true);				
			// }
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det,null,null,mod_name,mandatory,mod_group_id,mod_min_no);

					// loadMenuCategories();
					// check if lilipat na ng div
					curr_mod_seq = 1;
					// alert(curr_mod_seq);
					formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_seq_last='+modgrp_cnt+'&curr_mod_seq='+curr_mod_seq+'&mod_id='+mod_id;
					$.post(baseUrl+'cashier/check_modifier_grp',formData,function(data2){

						// console.log(data);
						// alert(data.status);

						if(data2.status == 'next_div'){
							mod_seq = $('#mod_seq').val();
							$('.mod-group').hide();
							next_seq = Number(mod_seq) + 1;
							$('.mod-grp-'+next_seq).show();
							$('#mod_seq').val(next_seq);

							// var mandatory_num = $('#mods-mandatory-'+mod_group_id).val();
							// var man_checker = mandatory_num -1;
							// $('#mods-mandatory-'+mod_group_id).val(man_checker);
							// if(man_checker <= "0"){
							// 	$('#mod-group-name-'+mod_group_id).remove();
							// 	$('#mods-mandatory-'+mod_group_id).remove();
							// }


						}else if(data2.status == 'finish'){
							$('.mods-mandatory').val(0);
							loadsDiv('menus',data2.cat_id,data2.obj);
						}else if(data2.status == 'enter_submod'){
							loadsDiv('submods',mod_id,opt,trans_id,data.id,"0");
						}

					// });
					},'json');
					

					// checkModifierDone(trans_id,'mods');
				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// });
		}

		function addTransSubModCartRemove(trans_id,mod_sub_id,mod_id,opt,btn,trans_det,other,qty=1){
			var formData = 'trans_id='+trans_id+'&mod_sub_id='+mod_sub_id
							// +'&mandatory='+mandatory
							+'&mod_id='+mod_id
							+'&mod_line_id='+other
							// +'&name='+opt.name+'&price='+opt.price+'&qty=1'+'&group='+opt.group+'&group_id='+opt.group_id;
							+'&name='+opt.name+'&price='+opt.price+'&qty='+opt.qty+'&group='+opt.group+'&group_id='+opt.group_id;
			// console.log(formData);
			// alert(formData);
			if(btn != null){
				btn.prop('disabled', true);				
			}
			$.post(baseUrl+'cashier/add_trans_submodifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					// alert(data.id);
					// alert(data.id);
					makeItemSubSubRow(data.id,trans_id,mod_id,opt,trans_det,'',other,opt.group);
					// checkModifierDone(trans_id,'submods');
					loadMenuCategories();
				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// console.log(data);
			// });
		}


		function makeItemRow(id,menu_id,opt,loaded,is_takeout=0,charge_code='',total_disc=0,is_update=''){
			// alert(nocharge);
			ttype = $('#ttype').val();
			$('.sel-row').removeClass('selected');
			var retail = "";
			if (opt.hasOwnProperty('retail')) {
				retail = 'retail-item';
			}

			$('<li/>').attr({'id':'trans-row-'+id,'trans-id':id,'ref':id,'cat_id':opt.category,'cat_name':opt.category_name,'class':'sel-row trans-row selected '+retail+' '+loaded+' '+is_update})
				.appendTo('.trans-lists')
				.click(function(){
					// var mods_mandatory = $('.mods-mandatory').val();
					// // alert(mods_mandatory);
					// if(mods_mandatory >= "1"){
					// 	var mod_name = $('.mod-group-name').val();
					// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
					// 	return false;
					// }
					selector($(this));
					// if (!opt.hasOwnProperty('retail')) {
					// 	loadsDiv('mods',menu_id,opt,id);
					// }
					// else{
					// 	remeload('retail');
					// }
					return false;
				});
			$('<span/>').attr('class','qty').text(opt.qty).css('margin-left','10px').appendTo('#trans-row-'+id);
			cg_code = '';
			if(charge_code != ''){
				// alert(nocharge);
				cg_code = '('+charge_code+') ';
			}
			$('<span/>').attr('class','charge').html(cg_code).appendTo('#trans-row-'+id);

			var namer = opt.name;
			if (opt.hasOwnProperty('retail')) {
				namer = '<i class="fa fa-shopping-cart"></i> '+opt.name;
			}
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					namer = ' <i class="fa fa-print"></i> '+namer;
			}
			console.log(opt);
			if (opt.hasOwnProperty('free_user_id')) {	
				// if(opt.free_user_id != "")
				if(opt.free_user_id != "" && opt.free_user_id != "0")
					namer = ' <i class="fa fa-asterisk"></i> '+namer;
			}
			if(is_takeout == 1 && ttype == 'dinein'){
				// alert(nocharge);
				namer = ' <i class="fa fa-magnet"></i> '+namer;
			}
			// $('<span/>').attr('class','name').html(namer).appendTo('#trans-row-'+id);
			// $('<span/>').attr('class','cost').text(opt.cost).css('margin-right','10px').appendTo('#trans-row-'+id);
			if(opt.qty > 1){
				cost_total = opt.cost * opt.qty;
				if(total_disc != 0){
					cost_total = cost_total - total_disc;
					cost_total = cost_total.toFixed(2);
				}
				$('<span/>').attr('class','name').html(namer).appendTo('#trans-row-'+id);
				$('<span/>').attr('class','cost').text(cost_total).css('margin-right','10px').appendTo('#trans-row-'+id);
				$('<span/>').attr('class','price').text('@'+opt.cost).css('margin-left','5px').appendTo('#trans-row-'+id);
			}else{
				cost_total = opt.cost;
				if(total_disc != 0){
					cost_total = opt.cost - total_disc;
					cost_total = cost_total.toFixed(2);
				}
				$('<span/>').attr('class','name').html(namer).appendTo('#trans-row-'+id);
				$('<span/>').attr('class','cost').text(cost_total).css('margin-right','10px').appendTo('#trans-row-'+id);
				$('<span/>').attr('class','price').text('').css('margin-left','5px').appendTo('#trans-row-'+id);
			}
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);

			if(loaded == 'promo'){
				$('#qty-btn,#remove-btn').prop('disabled','disabled');
			}else{
				$('#qty-btn,#remove-btn').removeAttr('disabled');
			}
		}
		function makeItemSubRow(id,trans_id,mod_id,opt,trans_det,loaded,dflt,mod_name,mandatory,mod_group_id,mod_min_no){
			var subRow = $('<li/>').attr({'id':'trans-sub-row-'+trans_id+'-'+id,'trans-id':trans_id,'trans-mod-id':id,'mod_name':mod_name,'mandatory':mandatory,'mod_group_id':mod_group_id,'ref':id,'sales-mod-id':opt.sales_mod_id,'class':'trans-sub-row sel-row '+loaded,'mod_min_no':mod_min_no,'is_def':dflt})
								   .click(function(){
										selector($(this));
										// loadsDiv('submods',mod_id,opt,trans_id,id);
										// alert('ssse');
										return false;
									});

			if(opt.qty == 1){
				var mod_name = opt.name;
			}else{
				var mod_name = opt.qty+' '+opt.name;
			}
								   
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					mod_name = ' <i class="fa fa-print"></i> '+mod_name;
			}

			$('<span/>').attr('class','name').css('margin-left','28px').html(mod_name).appendTo(subRow);
			if(parseFloat(opt.cost) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.cost*opt.qty).appendTo(subRow);

			if(dflt == "default"){
				$('#trans-row-'+trans_id).after(subRow);
			}
			else{
				$('.selected').after(subRow);
			}

			$('.sel-row').removeClass('selected');
			// selector($('#trans-sub-row-'+id));
			selector($('#trans-row-'+trans_id));
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubSubRow(id,trans_id,mod_id,opt,trans_det,loaded='',mod_line_id,group){
			var subRow = $('<li/>').attr({'id':'trans-subsub-row-'+id,'trans-id':trans_id,'trans-mod-id':mod_line_id,'trans-submod-id':id,'ref':id,'group':group,'mod_id':mod_id,'class':'trans-subsub-row sel-row'})
								   .click(function(){
										selector($(this));
										// loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});

			if(opt.qty == 1){
				var submod_name = opt.name;	
			}else{
				var submod_name = opt.qty+' '+opt.name;	
			}								
							   
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					submod_name = ' <i class="fa fa-print"></i> '+submod_name;
			}

			$('<span/>').attr('class','name').css('margin-left','50px').html('*'+submod_name).appendTo(subRow);
			if(parseFloat(opt.price) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.qty*opt.price).appendTo(subRow);

			// if(dflt == "default"){
			// 	$('#trans-row-'+trans_id).after(subRow);
			// }
			// else{
				$('#trans-sub-row-'+trans_id+'-'+mod_line_id).after(subRow);
			// }

			// $('.sel-row').removeClass('selected');
			// selector($('#trans-sub-row-'+trans_id+'-'+id));
			// if(loaded==""){
				// selector2($('#trans-sub-row-'+trans_id+'-'+id));
				// selector2($('#trans-row-'+trans_id));
			// }
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubSubRowLoad(id,trans_id,mod_id,opt,trans_det,loaded='',mod_line_id,trans_mod_id){
			var subRow = $('<li/>').attr({'id':'trans-subsub-row-'+id,'trans-id':trans_id,'trans-mod-id':trans_mod_id,'trans-submod-id':id,'ref':id,'class':'trans-subsub-row sel-row loaded'})
								   .click(function(){
										selector($(this));
										// loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});

			if(opt.qty == 1){
				var submod_name = opt.name;		
			}else{
				var submod_name = opt.qty+' '+opt.name;		
			}								   
						   
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					submod_name = ' <i class="fa fa-print"></i> '+submod_name;
			}

			$('<span/>').attr('class','name').css('margin-left','50px').html(submod_name).appendTo(subRow);
			if(parseFloat(opt.price) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.qty*opt.price).appendTo(subRow);

			// if(dflt == "default"){
			// 	$('#trans-row-'+trans_id).after(subRow);
			// }
			// else{
				$('#trans-sub-row-'+trans_id+'-'+mod_line_id).after(subRow);
			// }

			// $('.sel-row').removeClass('selected');
			// selector($('#trans-sub-row-'+trans_id+'-'+id));
			// if(loaded==""){
				// selector2($('#trans-sub-row-'+trans_id+'-'+id));
				// selector2($('#trans-row-'+trans_id));
			// }
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function selector(li){
			$('.sel-row').removeClass('selected');
			li.addClass('selected');

			if(li.hasClass('promo')){
				$('#qty-btn,#remove-btn').prop('disabled','disabled');
			}else{
				$('#qty-btn,#remove-btn').removeAttr('disabled');
			}
			
		}
		function selector2(li){
			$('.sel-row').removeClass('selected');
			li.click();
		}
		function selectModMenu(){
			var sel = $('.selected');
			if(sel.hasClass('trans-sub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
		}
		function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;
				var local_tax = data.local_tax;
				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
				if($("#local-tax-txt").exists()){
					$("#local-tax-txt").number(local_tax,2);
				}
				
				if(data.zero_rated > 0){
					$("#zero-rated-btn").removeClass('counter-btn-red');
					$("#zero-rated-btn").addClass('counter-btn-green');
					$("#zero-rated-btn").addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
				}

				$("#vat-txt").number(data.vat_txt,2);
				$("#vat-sales-txt").number(data.vat_sales_txt,2);
				$("#subtotal-txt").number(data.gross,2);
				$("#amount-due-txt").number(total,2);
				$("#discount-amount-txt").number(data.discount,2);
				$("#charge-amount-txt").number(data.charge,2);
			},'json');

			// $.post(baseUrl+'cashier/counter_trans_details',function(data){
			// 	$("#subtotal-txt").number(data.total_gross,2);
			// 	$("#vat-txt").number(data.tax < 0 ? data.tax*-1:data.tax,2);

			// 	if(data.tax < 0){
			// 		$("#vat-txt").text('('+$("#vat-txt").text()+')');
			// 	}

			// 	$("#vat-sales-txt").number(data.tax > 0 ? data.total_gross-data.tax : 0,2);
			// 	$("#discount-amount-txt").number(data.total_discount,2);
			// 	$("#charge-amount-txt").number(data.total_charges,2);

			// 	$("#discount-amount-txt").text('('+$("#discount-amount-txt").text()+')');
			// },'json');
			// 	alert(data);
			// 	console.log(data);
			// });
		}
		function loadTransCart(){
			$.post(baseUrl+'cashier/get_trans_cart/',function(data){
				
				// $is_update = $('#is_update').val();
				var is_update = $("input[type=hidden]#is_update").val();
				// alert(is_update);
				// console.log(data);
				if(!$.isEmptyObject(data)){
					var len = data.length;
					// alert(len);
					var ctr = 1;
					console.log(data);
					$.each(data,function(trans_id,opt){
						var is_promo = '';

						if(opt.is_promo == 1){
							is_promo = ' promo';
						}

						makeItemRow(trans_id,opt.menu_id,opt,'loaded' + is_promo,opt.is_takeout,opt.charge_code,opt.total_disc,is_update);
						// if(opt.type == "App Order" && opt.type != null && opt.memo != "" && opt.memo != null){
							// makeRemarksItemRow(trans_id,opt.memo);
						// }else{
							if(opt.remarks != "" && opt.remarks != null){
								makeRemarksItemRow(trans_id,opt.remarks);
							}
						// }
						var modifiers = opt.modifiers;
						if(!$.isEmptyObject(modifiers)){
							$.each(modifiers,function(trans_mod_id,mopt){
								makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,mopt,'loaded');

								var submodifiers = mopt.submodifiers;
								if(!$.isEmptyObject(submodifiers)){
									// alert(mopt.mod_line_id);
									$.each(submodifiers,function(trans_submod_id,smopt){
										if(smopt.mod_line_id == mopt.mod_line_id){
											makeItemSubSubRowLoad(trans_submod_id,trans_id,smopt.mod_id,smopt,mopt,'loaded',smopt.mod_line_id,trans_mod_id);
										}
									});
								}

							});
						}
						if(ctr == len){
							// $('#trans-row-0').click();
							// $('.selected').trigger('click');
						}
						ctr++;
					});

					$('.selected').click();
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
			// });
		}
		function loadTransChargeCart(){
			$.post(baseUrl+'cashier/get_trans_charges/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					$.each(data,function(charge_id,opt){
						makeChargeItemRow(charge_id,opt);
					});
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
		}
		function newTransaction(redirect,type){
			$.post(baseUrl+'cashier/new_trans/true/'+type,function(data){
				// if(!redirect){
				// 	$('#trans-datetime').text(data.datetime);
				// 	var tp = data.type;
				// 	$('#trans-header').text(tp.toUpperCase());

				// 	$('.trans-lists').find('li').remove();
				// 	var cat_id = $(".category-btns:first").attr('ref');
				// 	var cat_name = $(".category-btns:first").text();
				// 	var val = {'name':cat_name};
				// 	loadsDiv('menus',cat_id,val,null);
				// 	transTotal();
				// 	$('.addon-texts').text('').hide();
				// 	if(type == 'retail')
				// 		remeload('retail');
				// 	if(type=='dinein')
				// 		window.location = baseUrl+'cashier/tables';
				// 	else if(type=='delivery')
				// 		window.location = baseUrl+'cashier/delivery';
				// 	else if(type=='pickup')
				// 		window.location = baseUrl+'cashier/pickup';
				// }
				// else{



					
					if(type=='dinein'){
						formData = 'txt=dinein';
		       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
		       				// alert(data);
							// window.location = baseUrl+'cashier/counter/'+data;
							window.location = baseUrl+'cashier/tables/'+data;
		       			});
						// window.location = baseUrl+'cashier/tables';
					// else if(type=='delivery')
					// 	window.location = baseUrl+'cashier/delivery';
					// else if(type=='pickup')
					// 	window.location = baseUrl+'cashier/pickup';
					// else if(type=='eatigo')
					// 	window.location = baseUrl+'cashier/tables/eatigo';
					// else if(type=='bigdish')
					// 	window.location = baseUrl+'cashier/tables/bigdish';
					// else if(type=='zomato')
					// 	window.location = baseUrl+'cashier/tables/zomato';
					// else if(type=='reservation')
					// 	window.location = baseUrl+'cashier';
					}else{
						window.location = baseUrl+'cashier';
						// formData = 'type='+type;
				  //      	$.post(baseUrl+'cashier/check_tables',formData,function(data){
				  //      		// alert(data);
				  //      		// console.log(data);
				  //      		if(data.status){
						// 		window.location = baseUrl+'cashier/tables_other/'+type;
				  //      		}
				  //      		else{
				  //      			// rMsg(data.error,'error');
						// 		window.location = baseUrl+'cashier/counter/'+type;
				  //      		}
				  //      	},'json');	
				       	//old
						// window.location = baseUrl+'cashier/counter/'+data.type;
					}
				// }
			},'json');
		}
		function loadDefault(){
			var cat_id = $(".category-btns:first").attr('ref');
			var cat_name = $(".category-btns:first").text();
			var val = {'name':cat_name};
			loadsDiv('menus',cat_id,val,null);
		}
		function loadDiscounts(){
			$.post(baseUrl+'cashier/get_discounts',function(data){
				$('.select-discounts-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#item-disc-btn-'+id).click(function(){
						var idisc = $(this);
						$('#prcss-disc-line').attr('disc-id',opt.disc_id);
						$('#prcss-disc-line').attr('disc-code',opt.disc_code);
						// if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						// }
						// else{
						// 	$('#prcss-disc').attr('ref','all');
						// 	$('#disc-guests').attr('readOnly','true');
						// }
						if(opt.fix == 0){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						}else{
							$('#prcss-disc').attr('ref','all');
							$('#disc-guests').attr('readOnly','true');
						}
						// if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$.callManager({
								addData : 'Discount',
			 					success : function(){
									loadsDiv('discount',null,null,null);
									$('.discount-div .title').text(idisc.text());
									$('.discount-div #rate-txt').number(opt.disc_rate,2);
									$('#disc-disc-id').val(opt.disc_id);
									$('#disc-disc-rate').val(opt.disc_rate);
									$('#disc-disc-code').val(opt.disc_code);
									$('#disc-no-tax').val(opt.no_tax);
									$('#disc-fix').val(opt.fix);
									$('#disc-guests').val(opt.guest);
									$.post(baseUrl+'cashier/load_disc_line_persons/'+opt.disc_code,function(data){
										$('.disc-persons-list-div').html(data.code);
										$.each(data.items,function(code,opt){
											$("#disc-person-rem-"+code).click(function(){
												var lin = $(this).parent().parent();
												$.post(baseUrl+'cashier/remove_person_disc_line/'+opt.disc+'/'+code,function(data){
													lin.remove();
													rMsg('Person Removed.','success');

													$.each(data.affeted_row,function(code,opt){

														// txt = $('#trans-row-'+code+' .cost').text();

														// new_amt = Number(txt) + Number(opt.disc_amt);
														$('#trans-row-'+code+' .cost').text(opt.def_amt);
													});

													transTotal();
												// });
												},'json');
												return false;
											});

											$('#disc-person-'+code).click(function(){
												// alert('aw');
												$('.disc-person').removeClass('selected');
												code = $(this).attr('code');
												sname = $(this).attr('sname');
												// bday = $(this).attr('bday');

												$(this).addClass('selected');

												formData = 'code='+code+'&sname='+sname;
												$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
													// transTotal();
												});
											});

										});
									},'json');
									// });
									// if (typeof opt.name != 'undefined') {
									// 	$('#disc-cust-name').val(opt.name);
									// 	$('#disc-cust-guest').val(opt.guest);
									// 	$('#disc-guests').val(opt.guest);
									// 	$('#disc-cust-code').val(opt.code);
									// 	$('#disc-cust-bday').val(opt.bday);
									// }
			 					}	
		 					});
						// }else{
						// 	loadsDiv('discount',null,null,null);
						// 			$('.discount-div .title').text(idisc.text());
						// 			$('.discount-div #rate-txt').number(opt.disc_rate,2);
						// 			$('#disc-disc-id').val(opt.disc_id);
						// 			$('#disc-disc-rate').val(opt.disc_rate);
						// 			$('#disc-disc-code').val(opt.disc_code);
						// 			$('#disc-no-tax').val(opt.no_tax);
						// 			$('#disc-fix').val(opt.fix);
						// 			$('#disc-guests').val(opt.guest);
						// 			$.post(baseUrl+'cashier/load_disc_persons/'+opt.disc_code,function(data){
						// 				$('.disc-persons-list-div').html(data.code);
						// 				$.each(data.items,function(code,opt){
						// 					$("#disc-person-rem-"+code).click(function(){
						// 						var lin = $(this).parent().parent();
						// 						$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
						// 							lin.remove();
						// 							rMsg('Person Removed.','success');
						// 							transTotal();
						// 						});
						// 						return false;
						// 					});
						// 				});
						// 			},'json');
						// }


						
						return false;
					});
				});
			},'json');
		}
		function loadCharges(){
			// $.post(baseUrl+'cashier/get_charges',function(data){
			// 	$('.charges-lists').html(data.code);
			// 	$.each(data.ids,function(id,opt){
			// 		$('#charges-btn-'+id).click(function(){
			// 			// addChargeCart(id,opt);
			// 			// return false;

			// 			var sel = $('.selected');
			// 			var btn = $(this);
			// 			var sel_id = sel.attr("ref");
			// 			// alert(sel_id+'----'+id);
			// 			// var val = $('#times-qty').val();
			// 			// if(val == '' || val == ' '){
			// 			// 	rMsg('Please input a number.','error');
			// 			// }else if(val < 0){
			// 			// 	rMsg('Quantity should be greater than 0','error');
			// 			// 	$('#times-qty').val('');
			// 			// }else{
			// 			var formData = 'charge_id='+id;
			// 			btn.prop('disabled', true);
			// 			$.post(baseUrl+'cashier/update_menu_charge/'+sel_id,formData,function(data){
			// 				// alert(data);
			// 				// console.log(data);
			// 				// var qty = data.qty;
			// 				// text = $('#trans-row-'+sel_id+' .charge').text();
			// 				// $('#trans-row-'+sel_id+' .charge').text('');
			// 				text = '('+data.charge_code+') ';
			// 				$('#trans-row-'+sel_id+' .charge').text(text);
			// 				// if(data.qty == 1){
			// 				// 	$('#trans-row-'+id+' .price').text('');
			// 				// 	$('#trans-row-'+id+' .cost').text(data.price);
			// 				// }else{
			// 				// 	$('#trans-row-'+id+' .cost').text(data.subtotal);
			// 				// }

			// 				btn.prop('disabled', false);
			// 				transTotal();
			// 				// $('#times-qty').val('');
			// 			},'json');
			// 			// });


			// 		});
			// 	});
			// },'json');

			$.post(baseUrl+'cashier/get_charges',function(data){
				$('.charges-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#charges-btn-'+id).click(function(){
						addChargeCart(id,opt);
						return false;
					});
				});
			},'json');
		}
		function loadWaiters(){
			$.post(baseUrl+'cashier/get_waiters',function(data){
				$('.waiters-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#waiters-btn-'+id).click(function(){
						$.callFS({
							success : function(emp){
										if(id == emp['emp_id']){
											$.post(baseUrl+'cashier/update_trans/waiter_id/'+id,function(data){
												$('#trans-server-txt').text('FS: '+opt.uname).show();
												rMsg(opt.full_name+' added as Food Server','success');
											},'json');
										}
										else{
											rMsg('Wrong Pin.','error');
										}
									  }
						});
						return false;
					});
				});
			},'json');
		}
		function addChargeCart(id,row){
			var formData = 'name='+row.charge_name+'&code='+row.charge_name+'&amount='+row.charge_amount+'&absolute='+row.absolute;
			$.post(baseUrl+'wagon/add_to_wagon/trans_charge_cart/'+id,formData,function(data){
				if(data.error == null){
					makeChargeItemRow(data.id,data.items);
					// loadsDiv('mods',menu_id,data.items,data.id);
					transTotal();
				}
				else{
					rMsg(data.error,'error');
				}
			},'json');
			// });
		}
		function addTakeoutChargeCart(){
			formData = 'charge_id=3';
			$.post(baseUrl+'cashier/check_charge_cart',formData,function(data){
				if(data == 'add'){
				var formData = 'name=Packaging Charge&code=PCHG&amount=5&absolute=0';
					$.post(baseUrl+'wagon/add_to_wagon/trans_charge_cart/3',formData,function(data){
						if(data.error == null){
							//dineintakeout static
							makeChargeItemRowTO(data.id,data.items);
							// loadsDiv('mods',menu_id,data.items,data.id);
							// transTotal();
						}
						else{
							rMsg(data.error,'error');
						}
					},'json');
				}
			// });
			});
		}
		function makeChargeItemRow(id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-charge-row-'+id,'charge-id':id,'ref':id,'class':'sel-row trans-charge-row selected'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					loadsDiv('charges');
					return false;
				});
			$('<span/>').attr('class','qty').html('<i class="fa fa-tag"></i>').css('margin-left','10px').appendTo('#trans-charge-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-charge-row-'+id);
			var tx = opt.amount;
			if(opt.absolute == 0){
				tx = opt.amount+'%';
			}
			$('<span/>').attr('class','cost').text(tx).css('margin-right','10px').appendTo('#trans-charge-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeChargeItemRowTO(id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-charge-row-'+id,'charge-id':id,'ref':id,'class':'sel-row trans-charge-row'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					// loadsDiv('charges');
					return false;
				});
			$('<span/>').attr('class','qty').html('<i class="fa fa-tag"></i>').css('margin-left','10px').appendTo('#trans-charge-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-charge-row-'+id);
			var tx = opt.amount;
			if(opt.absolute == 0){
				tx = opt.amount+'%';
			}
			$('<span/>').attr('class','cost').text(tx).css('margin-right','10px').appendTo('#trans-charge-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeRemarksItemRow(id,remarks){
			$('.sel-row').removeClass('selected');
			if($('#trans-remarks-row-'+id).exists()){
				$('#trans-remarks-row-'+id).remove();
			}
			$('<li/>').attr({'id':'trans-remarks-row-'+id,'ref':id,'class':'sel-row trans-remarks-row selected'})
				.insertAfter('.trans-lists li#trans-row-'+id)
				.click(function(){
					selector($(this));
					loadsDiv('remarks');
					$('#line-remarks').val(remarks);
					return false;
				});
			// $('<span/>').attr('class','qty').html('').css('margin-left','10px').appendTo('#trans-remarks-row-'+id);
			$('<span/>').attr('class','name').css('margin-left','26px').html('<i class="fa fa-text-width"></i> '+remarks).appendTo('#trans-remarks-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function checkWagon(name){
			$.post(baseUrl+'wagon/get_wagon/'+name,function(data){
				alert(data);
			});
		}
		$('#disc-cust-name,#disc-cust-code,#disc-cust-bday,#disc-cust-guest,#line-remarks,#search-item')
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

        function checkModifierDone(id,type){
        	$.post(baseUrl+'cashier/check_modifier/'+id,function(data){
				if(data.status == 1){
					// loadsDiv('menu',);
					if(data.return=='menus'){
						$('.mods-mandatory').val(0);
						loadsDiv('menus',1,null);
					}if(data.return=='mod'){
						loadsDiv('mods',1,null);
					}else{
						$('#trans-row-'+ id).click();
						// loadsDiv('mods',1,null);
					}
					
				}
							
			},'json');
        }

        $('.num-qty').click(function(){
			var num_qty = $('#num-qty').val();

			var split = num_qty.split(".");

			if(split.length == 2 && (split[1].length == 2 || $(this).attr('num')=='.')){
				return false;
			}

			$('#num-qty').val(num_qty + ''+ $(this).attr('num'));
			$('#up-txt').text(num_qty + ''+ $(this).attr('num'));
			
			return false;
		});

		$('#clear-qty-btn').click(function(){
			$('#num-qty').val('');
			$('#up-txt').text('');
			return false;
		});

		function html_print(js_rcps){
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){	
			   		// alert(JSON.stringify(value.printer));		   
					$('#print-rcp').html(v.value);
				});
			});
		}
	<?php elseif($use_js == 'loyaltyAddJs'): ?>
		  bootbox.on('shown',function(){
			  $('#loyalty-card').focus();
		  });
	<?php elseif($use_js == 'settleJs'): ?>
		// $(':button').click(function(){
		// 	$.beep();
		// });
		// loadDivs('cash-payment',true);

		var has_balance = false;
		$('#search-payment').on('keyup',function(){
			vals = $(this).val();

			$('#ptype-div').html('');
			// var query = vals;
	  		//       window.find(query);
	        // return true;
	        $('#page_value').val(1);

			if(vals == ""){
				// alert('aw');
				// $('.pbuttons').show();
				// $('.pgbuttons').show();
				loadpaymentGroups();
				$('#pgnext-btn,#pgprev-btn').prop('disabled',false);
                $('#snext-btn,#sprev-btn').prop('disabled',false);
			}else{
				// alert('awasdfsadf');
				$('.pbuttons').hide();
				$('#paymentgroup-div').hide();
				$('#ptype-div').show();

				$('#pgnext-btn,#pgprev-btn').prop('disabled',true).hide();
                $('#snext-btn,#sprev-btn').prop('disabled',false);
                $('#prev-btn').show();

				// var div = $('#search-btn-div');
				var div = $('#ptype-div');
				
				formData = "val="+vals;

				$.post(baseUrl+'cashier/search_payments',formData,function(data){
	 				
	 				// alert(JSON.stringify(data'disabled',true));
	 				$.each(data.found,function(key,opt){
	 					if($('#'+key+'-btn').length == 1){
	 						return false;
	 					}

	 					addClss = "";
	 					// alert(opt.is_new);
	 					if(opt.is_new == 'new'){
	 						addClss = ' payment-btns';
	 					}
	 					// "style"=>'margin-bottom:10px;'
	 					// var sCol = $('<div class="col-md-3" style="margin-bottom:10px;"></div>');
	 					var sCol = $('<div class="nav-div nav-div col-md-4 col-sm-4 col-lg-4  text-left "></div>');
			 			$('<button/>')
			 			.attr({'id':key+'-btn','class':'btn-block settle-btn-green double'+addClss,'ref':key,'style':'height:73px;width:103px;'})
			 			.text(opt.stext)
			 			.appendTo(sCol);
			 			// .click(function(){
			 			// 	// menu_form(menu_id,opt.name);
				 		// 		// if(opt.free == 1){
					 	// 		// 	$.callManager({
					 	// 		// 		success : function(){
							// 		//  				// addTransCart(menu_id,opt);
					 	// 		// 				  }	
					 	// 		// 	});
				 		// 		// }
				 		// 		// else{
				 		// 		// 	// addTransCart(menu_id,opt);
				 		// 		// }
				 		// 		// $(this).click();
				 			
			 			// 	return false;
			 			// });
			 			sCol.appendTo(div);

			 			$('.payment-btns').click(function(){
				        	// alert('aaaaa');
				            ref = $(this).attr('ref');
				            // ref = 'sadfsdf';
				            var id = $('#settle').attr('sales');
				            
				            var amount = $('#settle').attr('balance');
				            formData = 'ref='+ref+'&amount='+amount;

				            // alert(ref);
				            // alert(amount);
				            // return false;
				            // $('#'+ref+'payment-div').html();
				            loadDivs(ref+'-payment',true);
				            if(ref != 'cust-deposits'){
				            	$('#'+ref+'-amt').val(amount,2);
				            }	

				            $('#cancel-'+ref+'-new-btn').click(function(){
				                // $('.'+ref+'-payment-div').html();
				                $('.'+ref+'-field').each(function(){
				                    if(!$(this).hasClass('amt')){
				                        $(this).val('');
				                    }
				                });
				                loadDivs('select-payment',false);
				                // return false;
				            });

				            //for fields
				            $('.'+ref+'-field').each(function(){
				                $(this).focus(function()
				                {
				                    $('#tbl-'+ref+'-new-target').attr('target','#'+$(this).attr('id'));
				                });
				            });
				            

				            $('#'+ref+'-enter-new-btn').addClass('new_payment');
				            $('#'+ref+'-enter-new-btn').attr('type',ref);

				            // $(this).addClass('new_payment');
				            // $(this).attr('type',ref);

				            // $('#'+ref+'-enter-btn').one("click", function(){
				            //  // event.preventDefault();
				    
				            //  var amount = $('#'+ref+'-amt').val();
				            //  // alert(id+'-'+amount);
				            //  addPayment(id,amount,ref,1);
				            //  // return false;
				            // });

				                // alert(formData);
				          //   $.post(baseUrl+'cashier/payment_view',formData,function(data){
				          //    // alert(data);

				                // $('.'+ref+'-payment-div').html(data);



				                
				          //   });

				            // paymentEnter(ref);

				            return false;

				        });

				        $('#cash-btn').click(function(){
							loadDivs('cash-payment',true);
							return false;
						});

						$('#cod-btn').click(function(){
							loadDivs('cod-payment',true);
							return false;
						});

						$('#loyalty-btn').click(function(){
							$.callLoyaltyCard();
						})

						$('#credit-card-btn').click(function(){
							loadDivs('credit-payment',true);
							var amount = $('#settle').attr('balance');
							$('#credit-amt').val(amount,2);
							return false;
						});

						$('#debit-card-btn').click(function(){
							loadDivs('debit-payment',true);
							var amount = $('#settle').attr('balance');
							$('#debit-amt').val(amount,2);
							return false;
						});
						$('#smac-card-btn').click(function(){
							loadDivs('smac-payment',true);
							var amount = $('#settle').attr('balance');
							$('#smac-amt').val(amount,2);
							return false;
						});
						$('#eplus-card-btn').click(function(){
							loadDivs('eplus-payment',true);
							var amount = $('#settle').attr('balance');
							$('#eplus-amt').val(amount,2);
							return false;
						});
						$('#loyalty-card-btn').click(function(){
							loadDivs('loyalty-payment',true);
							$('#loyalty-card-num').focus();
							var amount = $('#settle').attr('balance');
							$('#loyalty-amt').val("");
							return false;
						});
						$('#loyalty-card-num').keypress(function(e){
							if(e.keyCode == 13){
								e.preventDefault();
							}
						});

						$('#foodpanda-btn').click(function(){
							loadDivs('foodpanda-payment',true);
							var amount = $('#settle').attr('balance');
							$('#foodpanda-amt').val(amount,2);
							return false;
						});

						$('#check-btn').click(function(){
							loadDivs('check-payment',true);
							var amount = $('#settle').attr('balance');
							$('#check-amt').val(amount,2);
							return false;
						});

						// gcash
						$('#gcash-btn').click(function(){
							loadDivs('gcash-payment',true);
							var amount = $('#settle').attr('balance');
							$('#gcash-amt').val(amount,2);
							return false;
						});

						// paymaya
						$('#paymaya-btn').click(function(){
							loadDivs('paymaya-payment',true);
							var amount = $('#settle').attr('balance');
							$('#paymaya-amt').val(amount,2);
							return false;
						});

						// wechat
						$('#wechat-btn').click(function(){
							loadDivs('wechat-payment',true);
							var amount = $('#settle').attr('balance');
							$('#wechat-amt').val(amount,2);
							return false;
						});

						// alipay
						$('#alipay-btn').click(function(){
							loadDivs('alipay-payment',true);
							var amount = $('#settle').attr('balance');
							$('#alipay-amt').val(amount,2);
							return false;
						});

						$('#cust-deposits-btn').click(function(){
							loadDivs('cust-deposits-payment',true);
							return false;
						});

						$('#gift-cheque-btn').click(function(){
							loadDivs('gc-payment',true);
							$('#gc-code').blur();
							return false;
						});
						$('#coupon-btn').click(function(){
							loadDivs('coupon-payment',true);
							$('#coupon-code').blur();
							return false;
						});

						$('#sign-chit-btn').click(function(){
			
							loadDivs('sign-chit-payment',true);
							$('#manager-call-pin-login').focus();
							return false;
						});

						if(has_balance){
							$('#online-payment-btn').parent().addClass('hidden');
						}

						$('#online-payment-btn').click(function(){
						// $(document).on('click','#online-payment-btn',function(){
							loadDivs('online-payment-message',true);

							var id = $('#settle').attr('sales');

							$('.payment-btns').prop('disabled',true);
							window.location = baseUrl+'cashier/paymaya/'+ id;
							return false;
						});

	 				});
	 			},'json');
	 			// });

	 			$(div).show();
	 			$('.pgbuttons').hide();
	 			$('#sprev-btn').show();
	 			$('#snext-btn').show();
	 			// $('#prev-btn').hide();

				// var div = $('.menus-div .items-lists').append('<div class="row"></div>');
					
		 		// $.each(data,function(key,opt){
		 			
		 			// var menu_id = opt.id;
		 			// var sCol = $('<div class="col-md-3"></div>');
		 			// $('<button/>')
		 			// .attr({'id':'GrabFood-card-btn','class':'btn-block settle-btn-green double payment-btns','ref':'GrabFood'})
		 			// .text('SAMPLE')
		 			// .appendTo(sCol);
		 			// // .click(function(){
		 			// // 	// menu_form(menu_id,opt.name);
			 		// // 		// if(opt.free == 1){
				 	// // 		// 	$.callManager({
				 	// // 		// 		success : function(){
						// // 		//  				// addTransCart(menu_id,opt);
				 	// // 		// 				  }	
				 	// // 		// 	});
			 		// // 		// }
			 		// // 		// else{
			 		// // 		// 	// addTransCart(menu_id,opt);
			 		// // 		// }
			 		// // 		// $(this).click();
			 			
		 			// // 	return false;
		 			// // });
		 			// sCol.appendTo(div);
		 		// });


				// $("[id^='"+vals+"']").show();
				// alert($('.pbuttons').text());

				



			}

		});


		var hashTag = window.location.hash;
		if(hashTag == '#cash'){
			loadDivs('cash-payment',true);
		} else if(hashTag == '#credit'){
			loadDivs('credit-payment',true);
		} else if(hashTag == '#debit'){
			loadDivs('debit-payment',true);
		} else if(hashTag == '#gc'){
			loadDivs('gc-payment',true);
		}

		$('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
		$('#cancel-btn,#finished-btn').click(function(){
			if($('#settle').attr('retrack') == '1'){
					window.location = baseUrl+'cashier/retrack/';
			}
			else{
				// alert($('#settle').attr('type'));
				// return false;
				if($('#settle').attr('type') == 'dinein'){
					formData = 'txt=dinein';
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
	       				// alert(data);
						// window.location = baseUrl+'cashier/counter/'+data;
						window.location = baseUrl+'cashier/tables/'+data;
	       			});
					// window.location = baseUrl+'cashier/tables/';
				
				// else if($('#settle').attr('type') == 'delivery')
				// 	window.location = baseUrl+'cashier/delivery/';
				// else if($('#settle').attr('type') == 'pickup')
				// 	window.location = baseUrl+'cashier/pickup/';
				// else if($('#settle').attr('type') == 'App Order')
				// 	window.location = baseUrl+'cashier/';
				}else{
					window.location = baseUrl+'cashier/';
					// window.location = baseUrl+'cashier/counter/'+$('#settle').attr('type')+'#'+$('#settle').attr('type');
				}

			}

			return false;
		});
		$('#recall-btn').click(function(){
			type = $(this).attr('type');
			sale = $(this).attr("sale");
			// console.log(sale);	
			$.post(baseUrl+'cashier/check_payment/'+sale,function(data){
 				if(data.error == 'paid'){
 					rMsg('Error! Transaction already paid.','error');
 				}else{
 					formData = 'txt='+type;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
	       				// alert(data);
	       				// console.log(data);
 						// window.location = baseUrl+'cashier/counter/'+type+'/'+sale+'#'+type;
 						window.location = baseUrl+'cashier/counter/'+data+'/'+sale+'#'+data;
	       			});
 				}
 			},'json');
  			return false;
		});
		$('#transactions-btn').click(function(){
			loadDivs('transactions-payment',false);
			loadTransactions();
			return false;
		});


		$('.new_payment').click(function(){
			// alert('aw');
            type = $(this).attr('type');
            var id = $('#settle').attr('sales');
            
            var amount = $('#'+type+'-amt').val();
            // var amount = $('#settle').attr('balance');
            // alert(amount);
                // alert(id+'-'+amount);
            addPayment(id,amount,type,1);
        });


        $('.payment-btns').click(function(){
            ref = $(this).attr('ref');
            var id = $('#settle').attr('sales');
            
            var amount = $('#settle').attr('balance');
            formData = 'ref='+ref+'&amount='+amount;

            // $('#'+ref+'payment-div').html();
            loadDivs(ref+'-payment',true);
            $('#'+ref+'-amt').val(amount,2);

            $('#cancel-'+ref+'-new-btn').click(function(){
                // $('.'+ref+'-payment-div').html();
                $('.'+ref+'-field').each(function(){
                    if(!$(this).hasClass('amt')){
                        $(this).val('');
                    }
                });
                loadDivs('select-payment',false);
                // return false;
            });

            //for fields
            $('.'+ref+'-field').each(function(){
                $(this).focus(function()
                {
                    $('#tbl-'+ref+'-new-target').attr('target','#'+$(this).attr('id'));
                });
            });
            

            $('#'+ref+'-enter-new-btn').addClass('new_payment');
            $('#'+ref+'-enter-new-btn').attr('type',ref);

            // $(this).addClass('new_payment');
            // $(this).attr('type',ref);

            // $('#'+ref+'-enter-btn').one("click", function(){
            //  // event.preventDefault();
    
            //  var amount = $('#'+ref+'-amt').val();
            //  // alert(id+'-'+amount);
            //  addPayment(id,amount,ref,1);
            //  // return false;
            // });

                // alert(formData);
          //   $.post(baseUrl+'cashier/payment_view',formData,function(data){
          //    // alert(data);

                // $('.'+ref+'-payment-div').html(data);



                
          //   });

            // paymentEnter(ref);

            return false;

        });

  //       $.post(baseUrl+'cashier/total_trans',function(data){
		// 	var total = data.total;
		// 	var discount = data.discount;
		// 	var local_tax = data.local_tax;
		// 	$("#total-txt").number(total,2);
		// 	$("#discount-txt").number(discount,2);
		// 	if($("#local-tax-txt").exists()){
		// 		$("#local-tax-txt").number(local_tax,2);
		// 	}
			
		// 	if(data.zero_rated > 0){
		// 		$("#zero-rated-btn").removeClass('counter-btn-red');
		// 		$("#zero-rated-btn").addClass('counter-btn-green');
		// 		$("#zero-rated-btn").addClass('zero-rated-active');
		// 		$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
		// 		$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
		// 	}

		// 	$("#amount-due-txt").number(total,2);
		// },'json');

		$.post(baseUrl+'cashier/counter_trans_details',function(data){
			$("#subtotal-txt").number(data.total_gross,2);
			$("#vat-txt").number(data.tax < 0 ? data.tax*-1:data.tax,2);

			if(data.tax < 0){
				$("#vat-txt").text('('+$("#vat-txt").text()+')');
			}

			// $("#vat-sales-txt").number(data.tax > 0 ? data.total_gross-data.tax : 0,2);
			$("#vat-sales-txt").number(data.vat_sales_txt,2);
			$("#discount-amount-txt").number(data.total_discount,2);
			$("#charge-amount-txt").number(data.total_charges,2);

			$("#discount-amount-txt").text('('+$("#discount-amount-txt").text()+')');
		},'json');

		$('#cash-btn').click(function(){
			loadDivs('cash-payment',true);
			return false;
		});

		$('#cod-btn').click(function(){
			loadDivs('cod-payment',true);
			return false;
		});

		$('#loyalty-btn').click(function(){
			$.callLoyaltyCard();
		})

		$('#credit-card-btn').click(function(){
			loadDivs('credit-payment',true);
			var amount = $('#settle').attr('balance');
			$('#credit-amt').val(amount,2);
			return false;
		});

		$('#online-payment-btn').click(function(){
		// $(document).on('click','#online-payment-btn',function(){
			loadDivs('online-payment-message',true);

			var id = $('#settle').attr('sales');

			$('.payment-btns').prop('disabled',true);
			window.location = baseUrl+'cashier/paymaya/'+ id;
			return false;
		});
		
		// paymaya
		if($('.paymaya-inp').attr('paymaya_error') == 'error1'){
			 swal("Online payment failed.",'','error');

		}else if($('.paymaya-inp').attr('paymaya_error') == 'error2'){
			 swal("Unable to connect to online payment.",'','error');

		}else if($('.paymaya-inp').val() != ''){
			loadDivs('paymaya-payment',true);
			var amount = $('#settle').attr('balance');			
			$('.paymaya-inp').val($('.paymaya-inp').attr('paymaya_ref'));
			$('#paymaya-amt').val(amount,2);
		}

		// if($('#paymaya-20').attr('paymaya_error') == 'error1'){
		// 	 swal("Online payment failed.",'','error');

		// }else if($('#paymaya-20').attr('paymaya_error') == 'error2'){
		// 	 swal("Unable to connect to online payment.",'','error');

		// }else if($('#paymaya-20').val() != ''){
		// 	loadDivs('paymaya-payment',true);
		// 	var amount = $('#settle').attr('balance');			
		// 	$('#paymaya-20').val($('#paymaya-20').attr('paymaya_ref'));
		// 	$('#paymaya-amt').val(amount,2);
		// }
		// if($('#paymaya-num').attr('paymaya_error') == 'error1'){
		// 	 swal("Online payment failed.",'','error');

		// }else if($('#paymaya-num').attr('paymaya_error') == 'error2'){
		// 	 swal("Unable to connect to online payment.",'','error');

		// }else if($('#paymaya-num').val() != ''){
		// 	loadDivs('paymaya-payment',true);
		// 	var amount = $('#settle').attr('balance');			
		// 	$('#paymaya-num').val($('#paymaya-num').attr('paymaya_ref'));
		// 	$('#paymaya-amt').val(amount,2);
		// }

		document.onkeyup = KeyCheck;

		function KeyCheck(e)
		{
		   var KeyID = (window.event) ? event.keyCode : e.keyCode;
		   if(KeyID == 13)
		   {
		     	str = $('#credit-card-num').val();
				if (str.indexOf("^") !== -1) {
				    // `str` contains "geordie" *exactly* (doesn't catch "Geordie" or similar)
				    formData = 'str='+encodeURIComponent(str)+'&cut=^';
				    // alert(formData);
				    $.post(baseUrl+'cashier/credit_no_fix',formData,function(data){
				    	// alert(data);
				    	$('#credit-card-num').val(data);
				    });

				    // $('#credit-app-code').focus();
				}

				if (str.indexOf(">") !== -1) {
				    // `str` contains "geordie" *exactly* (doesn't catch "Geordie" or similar)
				    formData = 'str='+encodeURIComponent(str)+'&cut=>';
				    // alert(formData);
				    $.post(baseUrl+'cashier/credit_no_fix',formData,function(data){
				    	// alert(data);
				    	$('#credit-card-num').val(data);
				    });

				    // $('#credit-app-code').focus();
				}
		   }

		}

		document.onkeydown = KeyCheck2;
		function KeyCheck2(e)
		{
			var KeyID = (window.event) ? event.keyCode : e.keyCode;
			if(KeyID == 220){
		   		return false;
		   	}
		   // alert(KeyID);
		}

		$('#debit-card-btn').click(function(){
			loadDivs('debit-payment',true);
			var amount = $('#settle').attr('balance');
			$('#debit-amt').val(amount,2);
			return false;
		});
		$('#smac-card-btn').click(function(){
			loadDivs('smac-payment',true);
			var amount = $('#settle').attr('balance');
			$('#smac-amt').val(amount,2);
			return false;
		});
		$('#eplus-card-btn').click(function(){
			loadDivs('eplus-payment',true);
			var amount = $('#settle').attr('balance');
			$('#eplus-amt').val(amount,2);
			return false;
		});
		$('#loyalty-card-btn').click(function(){
			loadDivs('loyalty-payment',true);
			$('#loyalty-card-num').focus();
			var amount = $('#settle').attr('balance');
			$('#loyalty-amt').val("");
			return false;
		});
		$('#loyalty-card-num').keypress(function(e){
			if(e.keyCode == 13){
				e.preventDefault();
			}
		});

		$('#foodpanda-btn').click(function(){
			loadDivs('foodpanda-payment',true);
			var amount = $('#settle').attr('balance');
			$('#foodpanda-amt').val(amount,2);
			return false;
		});

		$('#check-btn').click(function(){
			loadDivs('check-payment',true);
			var amount = $('#settle').attr('balance');
			$('#check-amt').val(amount,2);
			return false;
		});

		// picc
		$('#picc-btn').click(function(){
			loadDivs('picc-payment',true);
			var amount = $('#settle').attr('balance');
			$('#picc-amt').val(amount,2);
			return false;
		});

		// gcash
		$('#gcash-btn').click(function(){
			loadDivs('gcash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#gcash-amt').val(amount,2);
			return false;
		});

		// paymaya
		$('#paymaya-btn').click(function(){
			loadDivs('paymaya-payment',true);
			var amount = $('#settle').attr('balance');
			$('#paymaya-amt').val(amount,2);
			return false;
		});

		// wechat
		$('#wechat-btn').click(function(){
			loadDivs('wechat-payment',true);
			var amount = $('#settle').attr('balance');
			$('#wechat-amt').val(amount,2);
			return false;
		});

		// alipay
		$('#alipay-btn').click(function(){
			loadDivs('alipay-payment',true);
			var amount = $('#settle').attr('balance');
			$('#alipay-amt').val(amount,2);
			return false;
		});

		//egift
		$('#egift-btn').click(function(){
			loadDivs('egift-payment',true);
			var amount = $('#settle').attr('balance');
			$('#egift-amt').val(amount,2);
			return false;
		});

		// paypal
		$('#paypal-btn').click(function(){
			loadDivs('paypal-payment',true);
			var amount = $('#settle').attr('balance');
			$('#paypal-amt').val(amount,2);
			return false;
		});

		//grabfood payment
		$('#grabfood-btn').click(function(){
			loadDivs('grabfood-payment',true);
			var amount = $('#settle').attr('balance');
			$('#grabfood-amt').val(amount,2);
			return false;
		});

		// lalafood
		$('#lalafood-btn').click(function(){
			loadDivs('lalafood-payment',true);
			var amount = $('#settle').attr('balance');
			$('#lalafood-amt').val(amount,2);
			return false;
		});

		// grabmart
		$('#grabmart-btn').click(function(){
			loadDivs('grabmart-payment',true);
			var amount = $('#settle').attr('balance');
			$('#grabmart-amt').val(amount,2);
			return false;
		});

		// pickaroo
		$('#pickaroo-btn').click(function(){
			loadDivs('pickaroo-payment',true);
			var amount = $('#settle').attr('balance');
			$('#pickaroo-amt').val(amount,2);
			return false;
		});

		// mofopaymongo
		$('#mofopaymongo-btn').click(function(){
			loadDivs('mofopaymongo-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofopaymongo-amt').val(amount,2);
			return false;
		});

		// grabpaybdo
		$('#grabpaybdo-btn').click(function(){
			loadDivs('grabpaybdo-payment',true);
			var amount = $('#settle').attr('balance');
			$('#grabpaybdo-amt').val(amount,2);
			return false;
		});

		// loyalty
		$('#loyalty-btn').click(function(){
			loadDivs('loyalty-payment',true);
			var amount = $('#settle').attr('balance');
			$('#loyalty-amt').val(amount,2);
			return false;
		});

		// pmayaewallet
		$('#pmayaewallet-btn').click(function(){
			loadDivs('pmayaewallet-payment',true);
			var amount = $('#settle').attr('balance');
			$('#pmayaewallet-amt').val(amount,2);
			return false;
		});

		// pmayaewallet
		$('#pmayacredcard-btn').click(function(){
			loadDivs('pmayacredcard-payment',true);
			var amount = $('#settle').attr('balance');
			$('#pmayacredcard-amt').val(amount,2);
			return false;
		});

		// tmgbtransfer
		$('#tmgbtransfer-btn').click(function(){
			loadDivs('tmgbtransfer-payment',true);
			var amount = $('#settle').attr('balance');
			$('#tmgbtransfer-amt').val(amount,2);
			return false;
		});

		// fppickupcash
		$('#fppickupcash-btn').click(function(){
			loadDivs('fppickupcash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#fppickupcash-amt').val(amount,2);
			return false;
		});

		// fppickonlne
		$('#fppickonlne-btn').click(function(){
			loadDivs('fppickonlne-payment',true);
			var amount = $('#settle').attr('balance');
			$('#fppickonlne-amt').val(amount,2);
			return false;
		});

		// gfselfpickup
		$('#gfselfpickup-btn').click(function(){
			loadDivs('gfselfpickup-payment',true);
			var amount = $('#settle').attr('balance');
			$('#gfselfpickup-amt').val(amount,2);
			return false;
		});

		// receivables
		$('#receivables-btn').click(function(){
			loadDivs('receivables-payment',true);
			var amount = $('#settle').attr('balance');
			$('#receivables-amt').val(amount,2);
			return false;
		});

		// hlmogobt
		$('#hlmogobt-btn').click(function(){
			loadDivs('hlmogobt-payment',true);
			var amount = $('#settle').attr('balance');
			$('#hlmogobt-amt').val(amount,2);
			return false;
		});

		// hlmogocash
		$('#hlmogocash-btn').click(function(){
			loadDivs('hlmogocash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#hlmogocash-amt').val(amount,2);
			return false;
		});

		// hlmogogcash
		$('#hlmogogcash-btn').click(function(){
			loadDivs('hlmogogcash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#hlmogogcash-amt').val(amount,2);
			return false;
		});

		// mofo
		$('#mofopmaya-btn').click(function(){
			loadDivs('mofopmaya-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofopmaya-amt').val(amount,2);
			return false;
		});

		// fasttrack
		$('#fasttrack-btn').click(function(){
			loadDivs('fasttrack-payment',true);
			var amount = $('#settle').attr('balance');
			$('#fasttrack-amt').val(amount,2);
			return false;
		});

		// mofocash
		$('#mofocash-btn').click(function(){
			loadDivs('mofocash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofocash-amt').val(amount,2);
			return false;
		});

		// dineinpmayacrd
		$('#dineinpmayacrd-btn').click(function(){
			loadDivs('dineinpmayacrd-payment',true);
			var amount = $('#settle').attr('balance');
			$('#dineinpmayacrd-amt').val(amount,2);
			return false;
		});

		// dineoutpmayacrd
		$('#dineoutpmayacrd-btn').click(function(){
			loadDivs('dineoutpmayacrd-payment',true);
			var amount = $('#settle').attr('balance');
			$('#dineoutpmayacrd-amt').val(amount,2);
			return false;
		});

		// smmallonline
		$('#smmallonline-btn').click(function(){
			loadDivs('smmallonline-payment',true);
			var amount = $('#settle').attr('balance');
			$('#smmallonline-amt').val(amount,2);
			return false;
		});

		// mofopmayadinein
		$('#mofopmayadinein-btn').click(function(){
			loadDivs('mofopmayadinein-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofopmayadinein-amt').val(amount,2);
			return false;
		});

		// mofocashdinein
		$('#mofocashdinein-btn').click(function(){
			loadDivs('mofocashdinein-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofocashdinein-amt').val(amount,2);
			return false;
		});

		// bdocard
		$('#bdocard-btn').click(function(){
			loadDivs('bdocard-payment',true);
			var amount = $('#settle').attr('balance');
			$('#bdocard-amt').val(amount,2);
			return false;
		});

		// mofogcash
		$('#mofogcash-btn').click(function(){
			loadDivs('mofogcash-payment',true);
			var amount = $('#settle').attr('balance');
			$('#mofogcash-amt').val(amount,2);
			return false;
		});

		// sendbill
		$('#sendbill-btn').click(function(){
			loadDivs('sendbill-payment',true);
			var amount = $('#settle').attr('balance');
			$('#sendbill-amt').val(amount,2);
			return false;
		});

		// bdopay
		$('#bdopay-btn').click(function(){
			loadDivs('bdopay-payment',true);
			var amount = $('#settle').attr('balance');
			$('#bdopay-amt').val(amount,2);
			return false;
		});

		$('#online-deal-btn').click(function(){
			loadDivs('online-deal-payment',true);
			var amount = $('#settle').attr('balance');
			$('#online-deal-amt').val(amount,2);
			return false;
		});
		$('#cust-deposits-btn').click(function(){
			loadDivs('cust-deposits-payment',true);
			return false;
		});
		$('#cust-deposits-search').on('keyup',function(){
			show_search();
		});	
		$('#cust-deposits-submit-btn').click(function(){
			var amount = $('#cust-deposits-amt').val();
			var id = $('#settle').attr('sales');
			if(amount != ""){
				console.log($.isNumeric($('#cust-deposits-amt').val().replace(/,/g ,"")));
				if (! $.isNumeric($('#cust-deposits-amt').val().replace(/,/g ,"")) ) {
					rMsg("Invalid amount","error");
				}
				else{
					var new_amt = parseFloat($('#cust-deposits-amt').val().replace(/,/g ,""));
					if(new_amt > 0){
						addPayment(id,amount,'deposit');
					}
					else{
						rMsg('Invalid Amount','error');				
					}
				}
			}
			else{
				rMsg('Invalid Amount','error');
			}
			return false;
		});	
		$('#gift-cheque-btn').click(function(){
			loadDivs('gc-payment',true);
			$('#gc-code').blur();
			return false;
		});
		$('#coupon-btn').click(function(){
			loadDivs('coupon-payment',true);
			$('#coupon-code').blur();
			return false;
		});
		$('#manager-call-pin-login').keyup(function(e){
			if(e.keyCode == 13){
		 	   $('#manager-submit-btn').trigger("click");
			}
		});	
		$('#manager-submit-btn').click(function(){
			var pin = $('#manager-call-pin-login').val();
			var formData = 'pin='+pin;
			var amount = $('#settle').attr('balance');
			var id = $('#settle').attr('sales');
			var btn = $('#manager-submit-btn');
			btn.goLoad();
			$.post(baseUrl+'cashier/manager_go_login',formData,function(data){
				if (typeof data.error_msg === 'undefined'){
			      	var man = data.manager;
			      	var man_id = man.manager_id;
			      	var type = 'chit';
			      	var formData = 'manager_id='+man_id+'&manager_username='+data.manager_username;
			      	amount = amount.replace(/,/g ,"");
			      	$.post(baseUrl+'cashier/add_payment/'+id+'/'+amount+'/'+type,formData,function(data){
						if(data.error == ""){
							rMsg('Success! Payment Submitted.','success');
							$('#amount-tendered-txt').number(data.tendered,2);
							$('#change-due-txt').number(data.change,2);
							$('#balance-due-txt').number(data.balance,2);
							$('#settle').attr('balance',data.balance);
							$('#cash-input').val('');
							loadDivs('after-payment',false);

							if(data.balance != 0){
								$('#finished-btn').attr('disabled','disabled');
								$('#print-btn').attr('disabled','disabled');
							}else{
								$('#finished-btn').removeAttr('disabled');
								$('#print-btn').removeAttr('disabled');

								$('#transactions-btn').attr('disabled','disabled');
								$('#recall-btn').attr('disabled','disabled');
								$('#cancel-btn').attr('disabled','disabled');
								$('#discount-btn').attr('disabled','disabled');
								$('.pgbuttons').attr('disabled','disabled');
								
								$('.pgbuttons').show();
								$('#ptype-div,#snext-btn,#sprev-btn,#prev-btn').hide();
								$('.payment-divs').hide();
							}
						} else {

						}
						btn.goLoad({load:false});
					},'json');
				}
				else{
					rMsg(data.error_msg,'error');
					btn.goLoad({load:false});
				}
			},'json');
			return false;
		});	
		$('#sign-chit-btn').click(function(){
			
			loadDivs('sign-chit-payment',true);
			$('#manager-call-pin-login').focus();
			return false;
		});

		$('#product-test-btn').click(function(){
			
			loadDivs('product-test-payment',true);
			$('#manager-call-pin-login').focus();
			return false;
		});
		$('#product-submit-btn').click(function(){
			var pin = $('#product-call-pin-login').val();
			var formData = 'pin='+pin;
			var amount = $('#settle').attr('balance');
			var id = $('#settle').attr('sales');
			var btn = $('#product-submit-btn');
			btn.goLoad();
			$.post(baseUrl+'cashier/manager_go_login',formData,function(data){
				if (typeof data.error_msg === 'undefined'){
			      	var man = data.manager;
			      	var man_id = man.manager_id;
			      	var type = 'producttest';
			      	var formData = 'manager_id='+man_id+'&manager_username='+data.manager_username;
			      	amount = amount.replace(/,/g ,"");
			      	$.post(baseUrl+'cashier/add_payment/'+id+'/'+amount+'/'+type,formData,function(data){
						if(data.error == ""){
							rMsg('Success! Payment Submitted.','success');
							$('#amount-tendered-txt').number(data.tendered,2);
							$('#change-due-txt').number(data.change,2);
							$('#balance-due-txt').number(data.balance,2);
							$('#settle').attr('balance',data.balance);
							$('#cash-input').val('');
							loadDivs('after-payment',false);

							if(data.balance != 0){
								$('#finished-btn').attr('disabled','disabled');
								$('#print-btn').attr('disabled','disabled');
							}else{
								$('#finished-btn').removeAttr('disabled');
								$('#print-btn').removeAttr('disabled');

								$('#transactions-btn').attr('disabled','disabled');
								$('#recall-btn').attr('disabled','disabled');
								$('#cancel-btn').attr('disabled','disabled');
								$('#discount-btn').attr('disabled','disabled');
								$('.pgbuttons').attr('disabled','disabled');
								
								$('.pgbuttons').show();
								$('#ptype-div,#snext-btn,#sprev-btn,#prev-btn').hide();
							}
						} else {

						}
						btn.goLoad({load:false});
					},'json');
				}
				else{
					rMsg(data.error_msg,'error');
					btn.goLoad({load:false});
				}
			},'json');
			return false;
		});	
		$('.amounts-btn').click(function(){
			var val = $(this).attr('val');
			var cash = $('#cash-input').val();
			if(cash == ""){
				$('#cash-input').val(val);
				
				var bal = parseFloat($.number($('#settle').attr('balance'),2).replaceAll(',',''));
				var nval =  parseFloat($.number(val,2).replaceAll(',',''));
				if(nval >= bal){
					$('#cash-enter-btn').click();
				}
			}
			else{
				var tot = parseFloat(val) + parseFloat(cash);
				$('#cash-input').val(tot);
			}

			return false;
		});
		$('#cash-exact-btn,#cash-next-btn').click(function(){
			$('#cash-input').val($.number($('#settle').attr('balance'),2));
			return false;
		});
		$('#exact-amount-btn').click(function(){
			$('#cash-input').val($(this).attr('amount'));
			return false;
		});
		$('#cash-enter-btn').click(function(){
			var amount = $('#cash-input').val().replace(/,/g ,"");
			if(amount != ""){
				if (! $.isNumeric(amount) ) {
					rMsg("Please enter a valid amount","error");
					return false;
				}

				var id = $('#settle').attr('sales');
				addPayment(id,amount,'cash');
			}
			return false;
		});
		$('.amounts-btn-cod').click(function(){
			var val = $(this).attr('val');
			var cash = $('#cod-input').val();
			if(cash == ""){
				$('#cod-input').val(val);
			}
			else{
				var tot = parseFloat(val) + parseFloat(cash);
				$('#cod-input').val(tot);
			}
			return false;
		});
		$('#cod-exact-btn,#cod-next-btn').click(function(){
			$('#cod-input').val($.number($('#settle').attr('balance'),2));
			return false;
		});
		$('#exact-amount-btn').click(function(){
			$('#cash-input').val($(this).attr('amount'));
			return false;
		});
		$('#cod-enter-btn').click(function(){
			var amount = $('#cod-input').val().replace(/,/g ,"");
			if(amount != ""){
				if (! $.isNumeric(amount) ) {
					rMsg("Please enter a valid amount","error");
					return false;
				}

				var id = $('#settle').attr('sales');
				addPayment(id,amount,'cod');
			}
			return false;
		});
		/* LOYALTY PAYMENT */
		$('#loyalty-card-num,#loyalty-amt').focus(function(){
			$('#tbl-loyalty-target').attr('target','#'+$(this).attr('id'));
		});
		$('#loyalty-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#loyalty-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#loyalty-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'loyalty');
			return false;
		});
		$('#loyalty-card-num,#loyalty-app-code,#loyalty-amt').focus(function()
		{
			$('#tbl-loyalty-target').attr('target','#'+$(this).attr('id'));
		});

		//PANDA payment
		$('#foodpanda-enter-btn').on('click',function(event){
			event.preventDefault();
			
			var amount = $('#foodpanda-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'foodpanda');
			return false;
		});

		/* CHECK PAYMENT */
 		$('#check-card-num,#check-amt').focus(function(){
 			$('#tbl-check-target').attr('target','#'+$(this).attr('id'));
 		});
 		$('#check-enter-btn').on('click',function(event){
 			event.preventDefault();
 			if (! $.isNumeric($('#check-amt').val().replace(/,/g ,"")) ) {
 				rMsg("Please enter a valid amount","error");
 				return false;
 			}
 			var amount = $('#check-amt').val();
 			var id = $('#settle').attr('sales');
 			addPayment(id,amount,'check');
 			return false;
 		});
 		/* End of CHECK PAYMENT */

		//PICC payment
		$('#picc-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#picc-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}

			if($('#picc-name').val() == ""){
				rMsg("Please enter a Name","error");
				$('#picc-name').focus();
				return false;
			}


			var amount = $('#picc-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'picc');
			return false;
		});

		//Gcash payment
		$('#gcash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#gcash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'gcash');
			return false;
		});

		//Paymaya payment
		$('#paymaya-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#paymaya-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'paymaya');
			return false;
		});

		//wechat payment
		$('#wechat-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#wechat-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'wechat');
			return false;
		});

		//alipay payment
		$('#alipay-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#alipay-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'alipay');
			return false;
		});

		//egift payment
		$('#egift-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#egift-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}

			if($('#egift-code-num').val() == ""){
				rMsg("Please enter a Code","error");
				$('#egift-code-num').focus();
				return false;
			}

			if($('#egift-code-approval').val() == ""){
				rMsg("Please enter a code approval","error");
				$('#egift-code-approval').focus();
				return false;
			}

			if($('#egift-ref-amt').val() == ""){
				rMsg("Please enter a Ref amount","error");
				$('#egift-ref-amt').focus();
				return false;
			}


			var amount = $('#egift-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'egift');
			return false;
		});

		//paypyal payment
		$('#paypal-num').focus(function(){
			$('#tbl-paypal-target').attr('target','#'+$(this).attr('id'));
		});
		$('#paypal-enter-btn').on('click',function(event){
			event.preventDefault();

			if($('#paypal-num').val() == ""){
				rMsg("Please enter a Reference","error");
				$('#paypal-num').focus();
				return false;
			}

			var amount = $('#paypal-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'paypal');
			return false;
		});


		//grab payment
		$('#grabfood-enter-btn').on('click',function(event){
			event.preventDefault();

			if($('#cust-name').val() == ""){
				rMsg("Please enter a Customer Name","error");
				$('#cust-name').focus();
				return false;
			}

			if($('#booking-no').val() == ""){
				rMsg("Please enter a Booking #","error");
				$('#booking-no').focus();
				return false;
			}

			if($('#driver').val() == ""){
				rMsg("Please enter a Driver","error");
				$('#driver').focus();
				return false;
			}
			
			var amount = $('#grabfood-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'grabfood');
			return false;
		});


		//lalafood payment
		$('#lalafood-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#lalafood-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'lalafood');
			return false;
		});

		//grabmart payment
		$('#grabmart-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#grabmart-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'grabmart');
			return false;
		});

		//pickaroo payment
		$('#pickaroo-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#pickaroo-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'pickaroo');
			return false;
		});

		//mofopaymongo payment
		$('#paymongo-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofopaymongo-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofopaymongo');
			return false;
		});

		//loyalty payment
		$('#loyalty-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#loyalty-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'loyalty');
			return false;
		});

		//grabpaybdo payment
		$('#grabpaybdo-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#grabpaybdo-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'grabpaybdo');
			return false;
		});

		//pmayaewallet payment
		$('#pmayaewallet-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#pmayaewallet-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'pmayaewallet');
			return false;
		});

		//pmayacredcard
		$('#pmayacredcard-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#pmayacredcard-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'pmayacredcard');
			return false;
		});

		//tmgbtransfer
		$('#tmgbtransfer-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#tmgbtransfer-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'tmgbtransfer');
			return false;
		});

		//fppickupcash
		$('#fppickupcash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#fppickupcash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'fppickupcash');
			return false;
		});

		//fppickonlne
		$('#fppickonlne-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#fppickonlne-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'fppickonlne');
			return false;
		});

		//gfselfpickup
		$('#gfselfpickup-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#gfselfpickup-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'gfselfpickup');
			return false;
		});

		//receivables
		$('#receivables-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#receivables-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'receivables');
			return false;
		});

		//hlmogobt
		$('#hlmogobt-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#hlmogobt-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'hlmogobt');
			return false;
		});

		//hlmogocash
		$('#hlmogocash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#hlmogocash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'hlmogocash');
			return false;
		});

		//hlmogogcash
		$('#hlmogogcash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#hlmogogcash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'hlmogogcash');
			return false;
		});

		//mofo
		$('#mofopmaya-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofopmaya-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofopmaya');
			return false;
		});

		//fasttrack
		$('#fasttrack-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#fasttrack-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'fasttrack');
			return false;
		});

		//mofocash
		$('#mofocash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofocash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofocash');
			return false;
		});

		//dineinpmayacrd
		$('#dineinpmayacrd-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#dineinpmayacrd-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'dineinpmayacrd');
			return false;
		});

		//dineoutpmayacrd
		$('#dineoutpmayacrd-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#dineoutpmayacrd-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'dineoutpmayacrd');
			return false;
		});

		//smmallonline
		$('#smmallonline-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#smmallonline-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'smmallonline');
			return false;
		});

		//mofopmayadinein
		$('#mofopmayadinein-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofopmayadinein-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofopmayadinein');
			return false;
		});

		//mofocashdinein
		$('#mofocashdinein-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofocashdinein-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofocashdinein');
			return false;
		});

		//bdocard
		$('#bdocard-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#bdocard-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'bdocard');
			return false;
		});

		//mofogcash
		$('#mofogcash-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#mofogcash-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'mofogcash');
			return false;
		});

		//sendbill
		$('#sendbill-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#sendbill-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'sendbill');
			return false;
		});

		//bdopay
		$('#bdopay-enter-btn').on('click',function(event){
			event.preventDefault();

			var amount = $('#bdopay-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'bdopay');
			return false;
		});

		/* DEBIT PAYMENT */
		$('#debit-card-num,#debit-amt').focus(function(){
			$('#tbl-debit-target').attr('target','#'+$(this).attr('id'));
		});
		$('#debit-enter-btn').on('click',function(event){

			var card_num = $('#debit-card-num').val();
			var approval_code = $('#debit-app-code').val();
			if(card_num == ""){
				rMsg('Card Number cannot be empty.','error');
				return false;
			}
			if(approval_code == ""){
				rMsg('Approval code cannot be empty.','error');
				return false;
			}


			event.preventDefault();
			if (! $.isNumeric($('#debit-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#debit-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'debit');
			return false;
		});
		/* End of DEBIT PAYMENT */
		$('#smac-card-num,#smac-amt').focus(function(){
			$('#tbl-smac-target').attr('target','#'+$(this).attr('id'));
		});
		$('#smac-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#smac-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#smac-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'smac');
			return false;
		});
		$('#smac-card-num,#smac-app-code,#smac-amt').focus(function()
		{
			$('#tbl-smac-target').attr('target','#'+$(this).attr('id'));
		});
		$('#eplus-card-num,#eplus-amt').focus(function(){
			$('#tbl-eplus-target').attr('target','#'+$(this).attr('id'));
		});
		$('#eplus-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#eplus-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#eplus-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'eplus');
			return false;
		});
		$('#eplus-card-num,#eplus-app-code,#eplus-amt').focus(function()
		{
			$('#tbl-eplus-target').attr('target','#'+$(this).attr('id'));
		});
		$('#online-deal-card-num,#online-deal-amt').focus(function(){
			$('#tbl-online-deal-target').attr('target','#'+$(this).attr('id'));
		});
		$('#online-deal-enter-btn').on('click',function(event){
			event.preventDefault();
			if (! $.isNumeric($('#online-deal-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#online-deal-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'online');
			return false;
		});
		$('#online-deal-card-num,#online-deal-code,#online-deal-amt').focus(function()
		{
			$('#tbl-online-deal-target').attr('target','#'+$(this).attr('id'));
		});
		/* End of eplus PAYMENT */
		/* CREDIT PAYMENT */
		$('.credit-type-btn').on('click',function(event)
		{
			event.preventDefault();
			//alert($(this).val());
			$('#credit-type-hidden').val($(this).val());
			$('.credit-type-btn').attr('style','background-color:#2daebf !important;');
			$(this).attr('style','background-color:#007d9a !important;');
		});
		$('.debit-type-btn').on('click',function(event)
		{
			event.preventDefault();
			//alert($(this).val());
			$('#debit-type-hidden').val($(this).val());
			$('.debit-type-btn').attr('style','background-color:#2daebf !important;');
			$(this).attr('style','background-color:#007d9a !important;');
		});
		$('#credit-card-num,#credit-app-code,#credit-amt').focus(function()
		{
			$('#tbl-credit-target').attr('target','#'+$(this).attr('id'));
		});
		$('#debit-card-num,#debit-app-code,#debit-amt').focus(function()
		{
			$('#tbl-debit-target').attr('target','#'+$(this).attr('id'));
		});
		$('#credit-enter-btn').on('click',function(event){
			var card_num = $('#credit-card-num').val();
			var card_type = $('#credit-type-hidden').val();
			if(card_num == ""){
				rMsg('Card Number cannot be empty.','error');
				return false;
			}
			if(card_type == ""){
				rMsg('No Card type Selected.','error');
				return false;
			}

			event.preventDefault();
			if (! $.isNumeric($('#credit-amt').val().replace(/,/g ,"")) ) {
				rMsg("Please enter a valid amount","error");
				return false;
			}
			var amount = $('#credit-amt').val();
			var id = $('#settle').attr('sales');
			addPayment(id,amount,'credit');
			return false;
		});
		/* End of CREDIT PAYMENT */
		/* GIFT CHEQUE */


		$('#gc-enter-btn').on('click',function(event)
		{
			event.preventDefault();
			// var validation = $('#hid-validation').val();
			// if(validation == 1){
				var m_mode = $(this).attr('mode');
			// }
			// else{
			// 	var m_mode = 'finalize';
			// }
			if (m_mode == 'search') {
				var code = $('#gc-code').val();
				var code_to = $('#gc-code-to').val();
				var description_id = $('#gc-type').val();
				$.post(baseUrl + 'cashier/search_gift_card',{'code_from':code,'code_to':code_to,'description_id':description_id},function(data)
				{
					if (typeof data.error != "undefined") {
						rMsg(data.error,"error");
					} else {
						$('#hid-gc-id').val(data.gc_id);
						$('#hid-gc-id_to').val(data.gc_id_to);
						$('#gc-amount').val(data.amount);
						$('#gc-code').val(data.card_no);
						$('#gc-enter-btn').html("Enter");
						$('#gc-enter-btn').attr('mode','finalize');
					}
				},'json');
			} else if (m_mode == 'finalize') {
				var amount = $('#gc-amount').val();
				var id = $('#settle').attr('sales');
				addPayment(id,amount,'gc');
			}
		});
		$('#gc-code,#gc-code-to,#gc-type').blur(function(event)
		{
			event.preventDefault();
			$('#gc-enter-btn').html('<i class="fa fa-search fa-lg"></i> Search');
			$('#gc-enter-btn').attr('mode','search');
		});
		/* End of GIFT CHEQUE */
		/* COUPON */
		$('#coupon-enter-btn').on('click',function(event)
		{
			event.preventDefault();
			var m_mode = $(this).attr('mode');
			if (m_mode == 'search') {
				var code = $('#coupon-code').val();
				$.post(baseUrl + 'cashier/search_coupon/'+code,{},function(data)
				{
					if (typeof data.error != "undefined") {
						rMsg(data.error,"error");
					} else {
						$('#hid-coupon-id').val(data.coupon_id);
						$('#coupon-amount').val(data.amount);
						$('#coupon-code').val(data.card_no);
						$('#coupon-enter-btn').html("Enter");
						$('#coupon-enter-btn').attr('mode','finalize');
					}
				},'json');
				// alert(data);
				// });
			} else if (m_mode == 'finalize') {
				var amount = $('#coupon-amount').val();
				var id = $('#settle').attr('sales');
				addPayment(id,amount,'coupon');
			}
		});
		$('#coupon-code').blur(function(event)
		{
			event.preventDefault();
			$('#coupon-enter-btn').html('<i class="fa fa-search fa-lg"></i> Search');
			$('#coupon-enter-btn').attr('mode','search');
		});
		/* End of COUPON */
		$('#cancel-cash-btn,#transactions-close-btn,.transactions-close-btn,#cancel-debit-btn,#cancel-smac-btn,#cancel-eplus-btn,#cancel-online-deal-btn,#cancel-cust-deposits-btn,#cancel-credit-btn,#cancel-gc-btn,#cancel-coupon-btn,#cancel-loyalty-btn,#cancel-foodpanda-btn,#cancel-picc-btn,#cancel-gcash-btn,#cancel-paymaya-btn,#cancel-wechat-btn,#cancel-alipay-btn,#cancel-egift-btn,#cancel-paypal-btn,#cancel-grabfood-btn,#cancel-lalafood-btn,#cancel-grabmart-btn,#cancel-pickaroo-btn,#cancel-mofopaymongo-btn,#cancel-grabpaybdo-btn,#cancel-pmayaewallet-btn,#cancel-pmayacredcard-btn,#cancel-tmgbtransfer-btn,#cancel-fppickupcash-btn,#cancel-fppickonlne-btn,#cancel-gfselfpickup-btn,#cancel-receivables-btn,#cancel-hlmogobt-btn,#cancel-hlmogocash-btn,#cancel-hlmogogcash-btn,#cancel-cod-btn,#cancel-mofopmaya-btn,#cancel-fasttrack-btn,#cancel-mofocash-btn,#cancel-dineinpmayacrd-btn,#cancel-dineoutpmayacrd-btn,#cancel-smmallonline-btn,#cancel-mofopmayadinein-btn,#cancel-mofocashdinein-btn,#cancel-bdocard-btn,#cancel-mofogcash-btn,#cancel-sendbill-btn,#cancel-bdopay-btn,#cancel-loyalty-btn,#cancel-signchit-btn').click(function(){
			// loadDivs('select-payment',false);
			loadDivs('cash-payment',true);
			return false;
		});
		$('#add-payment-btn').click(function(){
			// loadDivs('select-payment',true);
			loadDivs('cash-payment',true);
			$('#online-payment-btn').parent().addClass('hidden');
			has_balance=true;
			return false;
		});
		$('#print-btn').click(function(event){
			var sales_id = $(this).attr('ref');
			$.post(baseUrl+'cashier/print_sales_receipt_justin/'+sales_id,'',function(data)
			{
				if(data.js_rcps){
					$.ajaxSetup({async: false});										
					html_print(data.js_rcps);
				}
				rMsg(data.msg,'success');
			},'json');
			event.preventDefault();
		});

		$('#series-btn').click(function(event){
			$('#gc-enter-btn').attr('mode','search');
			$('#gc-enter-btn').html("Search");

			$('#gc-code-to').removeClass('hidden');
			$(this).addClass('hidden');
			return false;
		});

		function addPayment(id,amount,type,new_payment=0){
			var formData = {};
			amount = amount.replace(/,/g ,"");

			if(type != 'cash'){
				$('.btn-enter').html('Enter');
			}

			$('.btn-enter').goLoad();
			
			if (type == 'credit') {
				formData = 'card_type='+$('#credit-type-hidden').val()+
						'&card_number='+$('#credit-card-num').val()+
						'&approval_code='+$('#credit-app-code').val();
			} else if (type == 'debit') {
				formData = 'card_type='+$('#debit-type-hidden').val()+'&card_number='+$('#debit-card-num').val()+'&approval_code='+$('#debit-app-code').val();
			} else if (type == 'smac') {
				formData = 'card_number='+$('#smac-card-num').val();	
			} else if (type == 'eplus') {
				formData = 'card_number='+$('#eplus-card-num').val();	
			} else if (type == 'loyalty') {
				formData = 'card_number='+$('#loyalty-card-num').val();	
			} else if (type == 'online') {
				formData = 'card_number='+$('#online-deal-card-num').val();	
			} else if (type == 'gc') {
				formData = 'gc_id='+$('#hid-gc-id').val()+'&gc_code='+$('#gc-code').val()+'&gc_id_to='+$('#hid-gc-id-to').val()+'&gc_code_to='+$('#gc-code-to').val()+'&description_id='+$('#gc-type').val();
			} else if (type == 'coupon') {
				formData = 'coupon_id='+$('#hid-coupon-id').val()+'&coupon_code='+$('#coupon-code').val();
			} else if (type == 'deposit') {
				formData = 'customer_id='+$('#cust-deposits-cust-id').val();
			} else if (type == 'foodpanda') {
				formData = 'card_number='+$('#order-code-num').val();
			}else if (type == 'check') {
				formData = 'check_no='+$('#check-card-num').val()+'&bank='+$('#check-bank').val()+'&cdate='+$('#check-date').val();
  			}else if (type == 'picc') {
				formData = 'name='+$('#picc-name').val()+'&company='+$('#picc-company').val();
  			}else if (type == 'gcash') {
				formData = 'card_number='+$('#gcash-num').val();
  			}else if (type == 'paymaya') {
				formData = 'card_number='+$('#paymaya-num').val();
  			}else if (type == 'wechat') {
				formData = 'card_number='+$('#wechat-num').val();
  			}else if (type == 'alipay') {
				formData = 'card_number='+$('#alipay-num').val();
  			} else if (type == 'egift') {
				formData = 'code='+$('#egift-code-num').val()+'&approval='+$('#egift-code-approval').val()+'&ref_amt='+$('#egift-ref-amt').val();
  			}else if (type == 'paypal') {
				formData = 'card_number='+$('#paypal-num').val();
  			}else if (type == 'grabfood') {
				formData = 'reference='+$('#cust-name').val()+'&card_number='+$('#booking-no').val()+'&card_type='+$('#driver').val();
			}else if (type == 'lalafood') {
				formData = 'card_number='+$('#lalafood-num').val();
  			}else if (type == 'grabmart') {
				formData = 'card_number='+$('#grabmart-num').val();
  			}else if (type == 'pickaroo') {
				formData = 'card_number='+$('#pickaroo-num').val();
  			}else if (type == 'mofopaymongo') {
				formData = 'card_number='+$('#mofopaymongo-num').val();
  			}else if (type == 'grabpaybdo') {
				formData = 'card_number='+$('#grabpaybdo-num').val();
  			}else if (type == 'pmayaewallet') {
				formData = 'card_number='+$('#pmayaewallet-num').val();
  			}else if (type == 'pmayacredcard') {
				formData = 'card_number='+$('#pmayacredcard-num').val();
  			}else if (type == 'tmgbtransfer') {
				formData = 'card_number='+$('#tmgbtransfer-num').val();
  			}else if (type == 'fppickupcash') {
				formData = 'card_number='+$('#fppickupcash-num').val();
  			}else if (type == 'fppickonlne') {
				formData = 'card_number='+$('#fppickonlne-num').val();
  			}else if (type == 'gfselfpickup') {
				formData = 'card_number='+$('#gfselfpickup-num').val();
  			}else if (type == 'receivables') {
				formData = 'card_number='+$('#receivables-num').val();
  			}else if (type == 'hlmogobt') {
				formData = 'card_number='+$('#hlmogobt-num').val();
  			}else if (type == 'hlmogocash') {
				formData = 'card_number='+$('#hlmogocash-num').val();
  			}else if (type == 'hlmogogcash') {
				formData = 'card_number='+$('#hlmogogcash-num').val();
  			}else if (type == 'mofopmaya') {
				formData = 'card_number='+$('#mofopmaya-num').val();
  			}else if (type == 'fasttrack') {
				formData = 'card_number='+$('#fasttrack-num').val();
  			}else if (type == 'mofocash') {
				formData = 'card_number='+$('#mofocash-num').val();
  			}else if (type == 'dineinpmayacrd') {
				formData = 'card_number='+$('#dineinpmayacrd-num').val();
  			}else if (type == 'dineoutpmayacrd') {
				formData = 'card_number='+$('#dineoutpmayacrd-num').val();
  			}else if (type == 'smmallonline') {
				formData = 'card_number='+$('#smmallonline-num').val();
  			}else if (type == 'mofopmayadinein') {
				formData = 'card_number='+$('#mofopmayadinein-num').val();
  			}else if (type == 'mofocashdinein') {
				formData = 'card_number='+$('#mofocashdinein-num').val();
  			}else if (type == 'bdocard') {
				formData = 'card_number='+$('#bdocard-num').val();
  			}else if (type == 'mofogcash') {
				formData = 'card_number='+$('#mofogcash-num').val();
  			}else if (type == 'sendbill') {
				formData = 'card_number='+$('#sendbill-num').val();
  			}else if (type == 'bdopay') {
				formData = 'card_number='+$('#bdopay-num').val();
  			}else if (type == 'loyalty') {
				formData = 'card_number='+$('#loyalty-num').val();
  			}else if (type=='arclearingbilling' || type=='arclearingpromo') {
				formData = 'customer_id='+$('#cust-deposits-cust-id').val();

				if($('#cust-deposits-cust-id').val() == ''){
					alert('Customer required. Customer not in customer list.');
					$('.btn-enter').goLoad({load:false});
					return false;
				}

				blance = $('#cust-balance-val').val();
				// alert(parseInt(amount));
				// alert(parseInt(blance));

				if(parseInt(amount) > parseInt(blance)){
					alert('Reached the credit limit.');
					$('.btn-enter').goLoad({load:false});
					return false;
				}

			} 

  			if(new_payment == 1){
  				if(type=='arclearingbilling' || type=='arclearingpromo'){
  					formData += "&";
  				}else{
  					formData = "";
  				}
                
                ctr = 1;
                $('.'+type+'-field').each(function(){

                    if(!$(this).hasClass('amt')){

                        f_id = $(this).attr('id');
                        f_val = $(this).val();
                        if(ctr == 1){
                            formData = formData+f_id+'='+f_val;
                        }else{
                            formData = formData+'&'+f_id+'='+f_val;
                        }

                        ctr++;

                    }
                });
            }
  			
			<?php if(MALL_ENABLED && MALL == 'megamall'): ?>
				$('body').goLoad2({loadTxt:'Creating SM File...'});
			<?php elseif(MALL_ENABLED && MALL == 'miaa'): ?>
				$('body').goLoad2({loadTxt:'Creating Miaa File...'});
			<?php elseif(MALL_ENABLED && MALL == 'araneta'): ?>
				$('body').goLoad2({loadTxt:'Creating Araneta File...'});
			<?php else: ?>
				$('body').goLoad2();
			<?php endif; ?>

			var retake = $('#settle').attr('retrack');
			if (typeof retake !== typeof undefined && retake !== false) {
				if(formData){
					formData += '&trans_retake='+retake;
				}else{
					formData += 'trans_retake='+retake;
				}
			}
			// alert(formData);
			// return false;
			$.post(baseUrl+'cashier/add_payment/'+id+'/'+amount+'/'+type+'/'+new_payment,formData,function(data){
				$('body').goLoad2({load:false});		
				// console.log(data);		return false;
				if(data.error == ""){
					rMsg('Success! Payment Submitted.','success');
					$('#amount-tendered-txt').number(data.tendered,2);
					$('#change-due-txt').number(data.change,2);
					$('#balance-due-txt').number(data.balance,2);
					$('#settle').attr('balance',data.balance);
					$('#cash-input').val('');
					loadDivs('after-payment',false);

					if(data.balance != 0){
						$('#finished-btn').attr('disabled','disabled');
						$('#print-btn').attr('disabled','disabled');
						$('#discount-btn').attr('disabled','disabled');
					}else{
						$('#finished-btn').removeAttr('disabled');
						$('#print-btn').removeAttr('disabled');

						$('#transactions-btn').attr('disabled','disabled');
						$('#recall-btn').attr('disabled','disabled');
						$('#cancel-btn').attr('disabled','disabled');
						$('#loyalty-btn').attr('disabled','disabled');
						$('#add-payment-btn').attr('disabled','disabled');
						$('#discount-btn').attr('disabled','disabled');
						$('.pgbuttons').attr('disabled','disabled');
						
						$('.pgbuttons').show();
						$('#ptype-div,#snext-btn,#sprev-btn,#prev-btn').hide();

						$('.payment-divs').hide();
					}
					
					if(data.js_rcps){
						$.ajaxSetup({async: false});										
						html_print(data.js_rcps);
					}
				} else {
					rMsg(data.error,'error');
				}
				$('.btn-enter').goLoad({load:false});
			},'json').fail( function(xhr, textStatus, errorThrown) {
				$('.btn-enter').goLoad({load:false});
		        alert(xhr.responseText);
		    });
		}
		function deletePayment(pay_id,sales_id){
			$('#void-payment-btn-'+pay_id).click(function(){
				$.post(baseUrl+'cashier/delete_payment/'+pay_id+'/'+sales_id,function(data){
					console.log(data);
					if(data.error == 'paid'){
  						rMsg('Error! Transaction already paid.','error');
  					}else{
  						$('#balance-due-txt').number(data.balance,2);
  						$('#settle').attr('balance',data.balance);
  						$('#pay-row-div-'+pay_id).remove();

  						if(data.total_paid == 0){
  							$('#discount-btn').removeAttr('disabled');
  						}
  					}
				},'json');
				return false;
			});
		}
		function loadTransactions(){
			var id = $('#settle').attr('sales');
			$.post(baseUrl+'cashier/settle_transactions/'+id,function(data){
				$('.transactions-payment-div .body').html(data.code);
				$('.transactions-payment-div .body').perfectScrollbar({suppressScrollX: true});
				$.each(data.ids,function(key,pay_id){
					deletePayment(pay_id,id);
				});
			},'json');
		}
		function loadDivs(type,check){
			var go = true
			if(check){
				go = checkBal();
			}
			if(go){
				if(type == 'cust-deposits-payment'){
					var txt = $('#cust-deposits-search').val();
					var ul = $('#cust-search-list');
					ul.find('li').remove();
					// $('#cust-deposits-cust-name').val("");
					// $('#cust-deposits-amt').val("");
					// $('#cust-deposits-cust-id').val("");
				}
				$('.loads-div').hide();
				$('#debit-card-num').val("");
				$('#credit-card-num').val("");
				$('#loyalty-card-num').val("");
				$('#check-card-num').val("");
				$('#gc-code').val("");
				$('#gc-code-to').val('');
				$('#gc-amount').val('');
				$('#coupon-code').val("");
				$('#picc-name').val("");
				$('#gcash-num').val("");
				$('#paymaya-num').val("");
				$('#wechat-num').val("");
				$('#alipay-num').val("");
				$('.'+type+'-div').show();

				$('#gc-code-to').addClass('hidden');
				$('#series-btn').removeClass('hidden');
			}
		}
		function checkBal(){
			var bal = $('#settle').attr('balance');
			if(bal == ""){
				balance = 0;
			}
			else
				var balance = parseFloat(bal.replace(',','.').replace(' ',''));
			if(balance < 0){
				rMsg('Error! No more to pay.','error');
				return false;
			}
			else
				return true;
		}
		function show_search(){
			var txt = $('#cust-deposits-search').val();
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
										$.post(baseUrl+'cashier/get_custs_deposit_amount/'+cust_id,function(data){											
											var cust = data.result;
											if(!$.isEmptyObject(cust)){
												$('#cust-deposits-cust-name').val(cust.full_name);
												$('#cust-deposits-amt').val($.number(cust.amount,2) );
												$('#cust-deposits-cust-id').val(cust.cust_id);
											}
											else{												
												$('#cust-deposits-cust-name').val("");
												$('#cust-deposits-amt').val("");
												$('#cust-deposits-cust-id').val("");
											}
											selDeSel(li);
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
		$('#cash-input').addClass('disable-input-enter');

		$('#print-pdf-btn').click(function(){
			// var id = $('#print-div').attr('ref-id');
			var id = $(this).attr('ref');
			var btn = $(this);
			btn.goLoad();
			if(id != ""){
				// var report_type = $("#report_type").val();
				var pdf = "print_pdf_sales";

				var formData = 'calendar_range='+$('#calendar_range').val()+'&gc_type='+$('#gc-type').val();
				
				
				// if($('#calendar_range').val() == ""){
				// 	rMsg('Enter Date Range','error');
				// }
				// else{
					// $.rProgressBar();				
					window.open(baseUrl+'reporting/print_pdf_sales/'+id+'/0', "popupWindow", "width=600,height=600,scrollbars=yes");
					btn.goLoad({load:false});
					return false;
				// }
				// $.post(baseUrl+'reporting/print_pdf_sales/'+id+'/0',function(data){
				// 	// $('#print-div').html(data);
				// 	btn.goLoad({load:false});
				// });
			}
			return false;
		});

		$('.pgbuttons').click(function(){
			var pg_id = $(this).attr('id');
			$('.'+pg_id).show();
			$('.pgbuttons').hide();
			$('.nav-div').show();

			$('#p_group_id').val(pg_id);
			loadpaymentType(1);
		});

		$('#prev-btn').click(function(){
			// $('.pgbuttons').show();
			// $('.nav-div').hide();
			// $('.ptype').hide();
			// $('#ptype-div').hide();
			$('#pg_page_value').val(1);
			loadpaymentGroups(1);
			loadDivs('cash-payment',true);
		});

		$('#discount-btn').click(function(){
			// loadDivs('credit-payment-div2',true); alert($('.credit-payment-div2').length);
			loadDivs('choosedisc',true);
			return false;
		});

		$('#add-discount-btn').click(function(){

			//check if may line discount na
			formData = 'button=alldisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){
				// alert(data);
				if(data.error != ""){
					rMsg(data.error,'error');
				}else{
					// $.callManager({
						// addData : 'Discount',
	 				// 	success : function(){
	 						
							$.rPopForm({
								loadUrl : 'discount/discount_settle_pop',
								passTo	: '',
								// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
								title	: '<font size="4">Discount Form</font>',
								noButton: 1,
								wide	: 1,
								// rform 	: 'guide_form',
								onComplete : function(){
												
											 }
							});
							
	 				// 	}	
 					// });

				}
						
			},'json');
			// });


			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			// loadsDiv('sel-discount',null,null,null);
			// loadDiscounts();
			return false;
		});
		$('#add-discount-line-btn').click(function(){
			// $.rPopForm({
			// 	loadUrl : 'discount/discount_pop',
			// 	passTo	: '',
			// 	// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
			// 	title	: '<font size="4">Discount Form</font>',
			// 	noButton: 1,
			// 	wide	: 1,
			// 	// rform 	: 'guide_form',
			// 	onComplete : function(){
								
			// 				 }
			// });

			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			formData = 'button=linedisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){

				if(data.error != ""){
					rMsg(data.error,'error');
				}else{
				 // alert($('.sel-discount-div').length);
					loadsDiv('sel-discount',null,null,null);
					loadDiscounts();
				}
						
			},'json');


			return false;
		});

		$('.ps-container li').click(function(){
				$('.ps-container li').removeClass('selected');
				$(this).addClass('selected');
				return false;
		});

		function loadsDiv(type,id,opt,trans_id,other,mandatory){
			if(type=='sel-discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				// selectModMenu();
			}
			else if(type=='choosedisc'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				// selectModMenu();
			}
			else{
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
		}

		function loadDiscounts(){
			$.post(baseUrl+'cashier/get_discounts_settle',function(data){
				$('.select-discounts-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#item-disc-btn-'+id).click(function(){
						var idisc = $(this);
						$('#prcss-disc-line').attr('disc-id',opt.disc_id);
						$('#prcss-disc-line').attr('disc-code',opt.disc_code);
						// if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						// }
						// else{
						// 	$('#prcss-disc').attr('ref','all');
						// 	$('#disc-guests').attr('readOnly','true');
						// }
						if(opt.fix == 0){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						}else{
							$('#prcss-disc').attr('ref','all');
							$('#disc-guests').attr('readOnly','true');
						}

							// $.callManager({
							// 	addData : 'Discount',
			 			// 		success : function(){
									loadsDiv('discount',null,null,null);
									$('.discount-div .title').text(idisc.text());
									$('.discount-div #rate-txt').number(opt.disc_rate,2);
									$('#disc-disc-id').val(opt.disc_id);
									$('#disc-disc-rate').val(opt.disc_rate);
									$('#disc-disc-code').val(opt.disc_code);
									$('#disc-no-tax').val(opt.no_tax);
									$('#disc-fix').val(opt.fix);
									$('#disc-guests').val(opt.guest);
									$.post(baseUrl+'cashier/load_disc_line_persons/'+opt.disc_code,function(data){
										$('.disc-persons-list-div').html(data.code);
										$.each(data.items,function(code,opt){
											$("#disc-person-rem-"+code).click(function(){
												var lin = $(this).parent().parent();
												$.post(baseUrl+'cashier/remove_person_disc_line_settle/'+opt.disc+'/'+code,function(data){
													lin.remove();
													rMsg('Person Removed.','success');

													$.each(data.affeted_row,function(code,opt){

														// txt = $('#trans-row-'+code+' .cost').text();

														// new_amt = Number(txt) + Number(opt.disc_amt);
														$('#trans-row-'+code+' .cost').text(opt.def_amt);
													});
													location.reload();
													// transTotal();
												// });
												},'json');
												return false;
											});

											$('#disc-person-'+code).click(function(){
												// alert('aw');
												$('.disc-person').removeClass('selected');
												code = $(this).attr('code');
												sname = $(this).attr('sname');
												// bday = $(this).attr('bday');

												$(this).addClass('selected');

												formData = 'code='+code+'&sname='+sname;
												$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
													// transTotal();
												});
											});

										});
									},'json');
			 				// 	}	
		 					// });

						
						return false;
					});
				});
			},'json');
		}

		$('#add-disc-person-line-btn').click(function(){
			// $('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				// alert(guests);
				// var ref = $(this).attr('ref');
				var ref = $('#prcss-disc-line').attr('disc-code');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

					var disc_code = $('#disc-disc-code').val();
					if(disc_code == "SNDISC" || disc_code == "PWDISC"){
						var disc_cust_code = $('#disc-cust-code').val();
						console.log(disc_cust_code.length);
						if(disc_cust_code == ""){
							rMsg('Card Number cannot be empty','error');
							return false;
						}
					}
				$.post(baseUrl+'cashier/add_person_disc_line',formData,function(data){
					// alert(data);
						$('#add-disc-person-btn').goLoad({load:false});
						if(data.error==""){
							$('.disc-persons-list-div').html(data.code);
							$.each(data.items,function(code,opt){
								$("#disc-person-rem-"+code).click(function(){
									var lin = $(this).parent().parent();
									$.post(baseUrl+'cashier/remove_person_disc_line_settle/'+opt.disc+'/'+code,function(data){
										lin.remove();
										rMsg('Person Removed.','success');
										
										$.each(data.affeted_row,function(code,opt){

											// txt = $('#trans-row-'+code+' .cost').text();

											// new_amt = Number(txt) + Number(opt.disc_amt);
											$('#trans-row-'+code+' .cost').text(opt.def_amt);
										});

										location.reload();
									// });
									},'json');
									return false;
								});
								$('#disc-person-'+code).click(function(){
									$('.disc-person').removeClass('selected');
									code = $(this).attr('code');
									sname = $(this).attr('sname');
									remarks = $(this).attr('remarks');
									// bday = $(this).attr('bday');

									$(this).addClass('selected');

									formData = 'code='+code+'&sname='+sname+'&remarks='+remarks;
									$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
										// transTotal();
									});
								});
							});
							// transTotal();
						}
						else{
							rMsg(data.error,'error');
						}
				},'json');
				// });
			}
			return false;
		});

		$('#prcss-disc-line').click(function(){
			// alert('ss');
			var sel_p = $('.disc-person');
			var disc_id = $(this).attr('disc-id');
			var disc_code = $(this).attr('disc-code');
			// var disc_remarks = "";

			// if(sel_p.hasClass('selected')){
			var select = false;
			// if(disc_id == 1 || disc_id == 2){
				if(sel_p.hasClass('selected')){
					select = true;
				}
			// }else{
			// 	select = true;
			// }
			// alert('aw');
			$('.disc-person').each(function(){
				if($(this).hasClass('selected')){
					// select = true;
					remarks = ($(this).attr('remarks'));
					// var disc_remarks = $(this).attr('remarks');
				}
			});
			// alert('ew');


			// alert(remarks);

			if(select == true){

				var sel = $('.selected');
				var btn = $(this);
				var id = sel.attr("ref");

				if(id==''){ 
					id = '0';
				}


				if(id){

					
					var formData = 'disc_id='+disc_id+'&disc_code='+disc_code+'&disc_remarks='+remarks;
					// var formData = 'disc_id='+disc_id+'&disc_code='+disc_code;
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/discount_item_settle/'+id,formData,function(data){
						
						// $('#trans-row-'+id).text(data.discounted_amt);
						

						btn.prop('disabled', false);
						

						$.post(baseUrl+'cashier/submit_trans',function(data){
												
							if(data.error != null){
								rMsg(data.error,'error');
								// btn.prop('disabled', false);
							}
							else{
								rMsg('Discount has been added','success');
								location.reload();
							}

							// transTotal();
			   				
			   				
						},'json').fail( function(xhr, textStatus, errorThrown) {
								   alert(xhr.responseText);
								});	
					},'json');

				}else{
					rMsg('Please select an item','error');
				}
			
			}else{
				rMsg('Please select a person','error');

			}
			// $('#qty-btn-done').click();
			return false;
		});

		$('#disc-cust-name,#disc-cust-code,#disc-cust-bday,#disc-cust-guest,#line-remarks,#search-item')
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

        $('#disc-cust-remarks').click(function(){
			$.callDiscReasons({
				submit : function(reason){
					// console.log(reason);
					var free_reason = reason;
					// if(free_reason == ""){
					// 	rMsg('Please select/input a reason','error');
					// 	return false;
					// }else{
						var formData = 'free_reason='+free_reason;

						// $('body').goLoad2();
						$('#disc-cust-remarks').val(free_reason);
						// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
						// 	// console.log(data);
						// 	$('#trans-row-'+id+' .cost').text(0);
						// 	var text = $('#trans-row-'+id).find('.name').text();
						// 	// alert(text);
						// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text + " <br>&nbsp;&nbsp;&nbsp;<span class='label label-sm label-warning'>"+free_reason+"</span>");
						// 	transTotal();
						// 	rMsg('Updated Menu as free','success');
						// 	$('body').goLoad2({load:false});
						// });
					// }
					
					// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
					// 	// alert(data);
					// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
					// 		sel.prev().addClass('selected');
					// 		sel.remove();
					// 		if(cart == 'trans_cart' && retail === false){
					// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
					// 				var cat_id = $(".category-btns:first").attr('ref');
					// 				var cat_name = $(".category-btns:first").text();
					// 				var val = {'name':cat_name};
					// 				loadsDiv('menus',cat_id,val,null);
					// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
					// 			});
					// 		}
					// 		$('.counter-center .body').perfectScrollbar('update');
					// 		transTotal();
					// 	},'json');
					// });

				}
			});
		});

		function loadtransTypeSearch(val){

			// page = $('#page_value').val();
			// page = $('#page_value').val();
			formData = 'val='+val;
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_trans_type_search',formData,function(data){
		 		// alert(data);
		 		// alert(data.error);return false;
		 		if(data.error == ''){
		 			showTransType(data.buttons,1);
		 			$('#page_value').val(page);
		 		}
		 		// alert(data);
	 		// });
		 	},'json');
				

		}

		loadpaymentGroups();
        function loadpaymentGroups(page=1){
            // page = $('#page_value').val();
            // page = $('#page_value').val();
            var id = $('#settle').attr('sales');
            formData = 'id='+id+'&page='+page;
            // $.post(baseUrl+'cashier/get_menu_categories',function(data){
            $.post(baseUrl+'cashier/get_payment_group',formData,function(data){
                // alert(data.error);//return false;
                if(data.error == ''){
                    showPaymentGroups(data.buttons,1);
                    $('#page_value').val(page);

                    // $('#next-btn').show();
                    // $('#back-btn').show();
                    
                    $('#pgnext-btn,#pgprev-btn').hide();
                    $('#snext-btn,#sprev-btn').hide();
                    $('#prev-btn').hide();
                    if(data.count > 5){
                        $('#pgnext-btn,#pgprev-btn').show();
                    }

                    $('#paymentgroup-div').show();
                    $('#ptype-div').hide();
            // $('.nav-div').hide();
            // $('.ptype').hide();
                }else if($('#pg_page_value').val() > 1){
                	$('#pg_page_value').val($('#pg_page_value').val() - 1);
                }

                $('.pgbuttons').off('click');
                $('.pgbuttons').click(function(){ 
					var pg_id = $(this).attr('ref');
					$('#p_group_id').val(pg_id);
					loadpaymentType(1);
				});
                // alert(data);
            // });
            },'json');  

        }

        function showPaymentGroups(data,ctr){   
            $('#paymentgroup-div').html('');
            // alert(1);
            // $('.menus-div .items-lists').html('');
            var div = $('#paymentgroup-div');
            
 
            $.each(data,function(key,val){
                
                var sCol = $('<div class="nav-div nav-div col-md-4 col-sm-4 col-lg-3  text-left "></div>');
                // var sCol = $('<div class="col-md-5" style="border-right-style:groove"></div>');


                $('<button/>')
                .attr({'id':val['text_id']+'-btn','ref':val['text_id'],'class':'btn-block settle-btn-teal double pgbuttons '
                	,'style':'height:72px;width:110px'
                    // ,'style':'height:60px;width:130px;margin-top:1px;'
                })
                .text(val['text'])
                // .appendTo('.menu-cat-container')
                .appendTo(sCol);
                // .click(function(){
                    //  var mods_mandatory = $('.mods-mandatory').val();
                    //  // alert(mods_mandatory);
                    //  if(mods_mandatory >= "1"){
                    //      var mod_name = $('.mod-group-name').val();
                    //      rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
                    //      return false;
                    //  }


                //  $('#search-menu').val('');
                //  // loadsDiv('subcategory',cat_id,val,null);
                //  loadsDiv('menus',cat_id,val,null);
                //  return false;
                // });
                sCol.appendTo(div);
                


                // ctr++;
            });
        }

		function loadpaymentType(page=1){
			// page = $('#page_value').val();
			// page = $('#page_value').val();
			pg_id=$('#p_group_id').val();
			var id = $('#settle').attr('sales');
			formData = 'id='+id+'&page='+page+'&pg_id='+pg_id+'&val='+$('#search-payment').val();
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_payment_type',formData,function(data){
		 		// alert(data.error);//return false;
		 		if(data.buttons.length == 0){
		 			alert('No available payment method.');
		 			return false;
		 		}
		 		if(data.error == ''){
		 			showPaymentType(data.buttons,1);
		 			$('#page_value').val(page);
		 			$('#paymentgroup-div').hide();

		 			// $('#next-btn').show();
		 			// $('#back-btn').show();
		 			$('#pgnext-btn,#pgprev-btn').hide();
		 			// $('#snext-btn,#sprev-btn').hide();
		 			$('#prev-btn').show();
		 			if(data.count > 5){
		 				$('#snext-btn,#sprev-btn').show();
		 			}
			// $('.nav-div').hide();
			// $('.ptype').hide();
		 		}
		 		// alert(data);
	 		// });
		 	},'json');	

		}

		function showPaymentType(data,ctr){	
			$('#ptype-div').html('');

			// $('.menus-div .items-lists').html('');
			var div = $('#ptype-div');
			
 
			$.each(data,function(key,val){
				
				var sCol = $('<div class="nav-div nav-div col-md-4 col-sm-4 col-lg-4  text-left "></div>');
				// var sCol = $('<div class="col-md-5" style="border-right-style:groove"></div>');


	 			$('<button/>')
	 			.attr({'id':val['text_id']+'-btn','ref':val['text_id'],'class':'btn-block settle-btn-green double payment-btns pbuttons '+val['new_trans_type']
	 				,'style':'height:73px;width:103px;'
	 			})
	 			.text(val['text'])
	 			// .appendTo('.menu-cat-container')
	 			.appendTo(sCol);
	 			// .click(function(){
					// 	var mods_mandatory = $('.mods-mandatory').val();
					// 	// alert(mods_mandatory);
					// 	if(mods_mandatory >= "1"){
					// 		var mod_name = $('.mod-group-name').val();
					// 		rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
					// 		return false;
					// 	}


	 			// 	$('#search-menu').val('');
	 			// 	// loadsDiv('subcategory',cat_id,val,null);
	 			// 	loadsDiv('menus',cat_id,val,null);
	 			// 	return false;
	 			// });
	 			sCol.appendTo(div);
	 			


				// ctr++;
			});

			$('.payment-btns').click(function(){
				        	// alert('aaaaa');
				            ref = $(this).attr('ref');
				            // ref = 'sadfsdf';
				            var id = $('#settle').attr('sales');
				            
				            var amount = $('#settle').attr('balance');
				            formData = 'ref='+ref+'&amount='+amount;

				            // alert(ref);
				            // alert(amount);
				            // return false;
				            // $('#'+ref+'payment-div').html();
				            loadDivs(ref+'-payment',true);

				            if(ref != 'cust-deposits'){
				            	$('#'+ref+'-amt').val(amount,2);
				            }				            

				            $('#cancel-'+ref+'-new-btn').click(function(){
				                // $('.'+ref+'-payment-div').html();
				                $('.'+ref+'-field').each(function(){
				                    if(!$(this).hasClass('amt')){
				                        $(this).val('');
				                    }
				                });
				                loadDivs('select-payment',false);
				                // return false;
				            });

				            //for fields
				            $('.'+ref+'-field').each(function(){
				                $(this).focus(function()
				                {
				                    $('#tbl-'+ref+'-new-target').attr('target','#'+$(this).attr('id'));
				                });
				            });
				            

				            $('#'+ref+'-enter-new-btn').addClass('new_payment');
				            $('#'+ref+'-enter-new-btn').attr('type',ref);

				            // $(this).addClass('new_payment');
				            // $(this).attr('type',ref);

				            // $('#'+ref+'-enter-btn').one("click", function(){
				            //  // event.preventDefault();
				    
				            //  var amount = $('#'+ref+'-amt').val();
				            //  // alert(id+'-'+amount);
				            //  addPayment(id,amount,ref,1);
				            //  // return false;
				            // });

				                // alert(formData);
				          //   $.post(baseUrl+'cashier/payment_view',formData,function(data){
				          //    // alert(data);

				                // $('.'+ref+'-payment-div').html(data);



				                
				          //   });

				            // paymentEnter(ref);

				            return false;

				        });

				        $('#cash-btn').click(function(){
							loadDivs('cash-payment',true);
							return false;
						});

						$('#cod-btn').click(function(){
							loadDivs('cod-payment',true);
							return false;
						});

						$('#loyalty-btn').click(function(){
							$.callLoyaltyCard();
						})

						$('#credit-card-btn').click(function(){
							loadDivs('credit-payment',true);
							var amount = $('#settle').attr('balance');
							$('#credit-amt').val(amount,2);
							return false;
						});

						$('#debit-card-btn').click(function(){
							loadDivs('debit-payment',true);
							var amount = $('#settle').attr('balance');
							$('#debit-amt').val(amount,2);
							return false;
						});
						$('#smac-card-btn').click(function(){
							loadDivs('smac-payment',true);
							var amount = $('#settle').attr('balance');
							$('#smac-amt').val(amount,2);
							return false;
						});
						$('#eplus-card-btn').click(function(){
							loadDivs('eplus-payment',true);
							var amount = $('#settle').attr('balance');
							$('#eplus-amt').val(amount,2);
							return false;
						});
						$('#loyalty-card-btn').click(function(){
							loadDivs('loyalty-payment',true);
							$('#loyalty-card-num').focus();
							var amount = $('#settle').attr('balance');
							$('#loyalty-amt').val("");
							return false;
						});
						$('#loyalty-card-num').keypress(function(e){
							if(e.keyCode == 13){
								e.preventDefault();
							}
						});

						$('#foodpanda-btn').click(function(){
							loadDivs('foodpanda-payment',true);
							var amount = $('#settle').attr('balance');
							$('#foodpanda-amt').val(amount,2);
							return false;
						});

						$('#check-btn').click(function(){
							loadDivs('check-payment',true);
							var amount = $('#settle').attr('balance');
							$('#check-amt').val(amount,2);
							return false;
						});

						// gcash
						$('#gcash-btn').click(function(){
							loadDivs('gcash-payment',true);
							var amount = $('#settle').attr('balance');
							$('#gcash-amt').val(amount,2);
							return false;
						});

						// paymaya
						$('#paymaya-btn').click(function(){
							loadDivs('paymaya-payment',true);
							var amount = $('#settle').attr('balance');
							$('#paymaya-amt').val(amount,2);
							return false;
						});

						// wechat
						$('#wechat-btn').click(function(){
							loadDivs('wechat-payment',true);
							var amount = $('#settle').attr('balance');
							$('#wechat-amt').val(amount,2);
							return false;
						});

						// alipay
						$('#alipay-btn').click(function(){
							loadDivs('alipay-payment',true);
							var amount = $('#settle').attr('balance');
							$('#alipay-amt').val(amount,2);
							return false;
						});

						$('#cust-deposits-btn').click(function(){
							loadDivs('cust-deposits-payment',true);
							return false;
						});

						$('#gift-cheque-btn').click(function(){
							loadDivs('gc-payment',true);
							$('#gc-code').blur();
							return false;
						});
						$('#coupon-btn').click(function(){
							loadDivs('coupon-payment',true);
							$('#coupon-code').blur();
							return false;
						});

						$('#sign-chit-btn').click(function(){
			
							loadDivs('sign-chit-payment',true);
							$('#manager-call-pin-login').focus();
							return false;
						});

						$('#online-payment-btn').click(function(){
						// $(document).on('click','#online-payment-btn',function(){
							loadDivs('online-payment-message',true);

							var id = $('#settle').attr('sales');

							$('.payment-btns').prop('disabled',true);
							window.location = baseUrl+'cashier/paymaya/'+ id;
							return false;
						});
						
						// paymaya
						// if($('#paymaya-num').attr('paymaya_error') == 'error1'){
						// 	 swal("Online payment failed.",'','error');

						// }else if($('#paymaya-num').attr('paymaya_error') == 'error2'){
						// 	 swal("Unable to connect to online payment.",'','error');

						// }else 
						// if($('#paymaya-num').val() != ''){
						// 	loadDivs('paymaya-payment',true);
						// 	var amount = $('#settle').attr('balance');			
						// 	$('#paymaya-num').val($('#paymaya-num').attr('paymaya_ref'));
						// 	$('#paymaya-amt').val(amount,2);
						// }

						if($('.paymaya-inp').val() != ''){
							loadDivs('.paymaya-payment',true);
							var amount = $('#settle').attr('balance');			
							$('.paymaya-inp').val($('.paymaya-inp').attr('paymaya_ref'));
							$('#paymaya-amt').val(amount,2);
						}

						// if($('#paymaya-20').val() != ''){
						// 	loadDivs('paymaya-payment',true);
						// 	var amount = $('#settle').attr('balance');			
						// 	$('#paymaya-20').val($('#paymaya-20').attr('paymaya_ref'));
						// 	$('#paymaya-20').val(amount,2);
						// }

						if(has_balance){
							$('#online-payment-btn').parent().addClass('hidden');
						}

			$('#ptype-div').show();
		}

		$('#pgnext-btn').click(function(){
			page = $('#pg_page_value').val();
			page = Number(page) + 1;
			$('#pg_page_value').val(page);
			// alert(page);
			loadpaymentGroups(page);
		});

		$('#pgprev-btn').click(function(){
			page = $('#pg_page_value').val();
			if(page != 1){
				page = Number(page) - 1;
				$('#pg_page_value').val(page)
				// alert(page);
				loadpaymentGroups(page);
			}
		});
		
		$('#snext-btn').click(function(){
			page = $('#page_value').val();
			page = Number(page) + 1;
			// $('#page_value').val(page);
			// alert(page);
			loadpaymentType(page);
		});

		$('#sprev-btn').click(function(){
			page = $('#page_value').val();
			// if(page != 1){
				page = Number(page) - 1;
				// $('#page_value').val(page)
				// alert(page);
				loadpaymentType(page);
			// }
		});

		///auto fill docus
		$('.full_name').keydown(function(){

	   		var opts = $('#customers').find('option');
	   		var arr = [];
			opts.each(function(){
				// var val = $(this).attr('value');
				var val = "";
				var txt = $(this).text();
				arr.push({"code":val,"items":txt});
			});	
			

			$('.full_name').typeaheadmap({ "source":arr, 
			    "key": "items", 
			    "value": "code", 
			    "listener": function(k, v) {
			    	// $('#cust-deposits-cust-id').val(v);
			    				formData = 'val='+k+'&type=name';
			    				$.post(baseUrl+'cashier/get_customer_dets',formData,function(data){
						       		// alert(data);
						       		// console.log(data);
						       		$('#cust-deposits-cust-id').val(data.cust_id);
						       		$('#cust-balance-val').val(data.balance);
						       		$('#balance-cust-txt').text(data.balancetxt);
						       	},'json');
						       	// });
							}		
			});

		});

		function html_print(js_rcps){ 
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){	
			   		// alert(JSON.stringify(value.printer));		   
					$('#print-rcp').html(v.value);
				});
			});
        	
        }

		// $('.full_name')
  //       .keyboard({
  //           alwaysOpen: false,
  //           usePreview: false,
		// 	autoAccept : true,
		// 	change   : function(e, keyboard, el) { 
		// 		$('.full_name').focus().keydown();
		// 	},

  //       })
  //       .addNavigation({
  //           position   : [0,0],
  //           toggleMode : false,
  //           focusClass : 'hasFocus'
  //       });
	<?php elseif($use_js == 'splitJs1'): ?>
		var scrolled=0;
		var transScroll=0;
		$('.counter-split-right .actions-div').perfectScrollbar({suppressScrollX: true});
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		loadTransCart();
		$('#save-split-btn').click(function(){
			var btn = $(this);
			if(btn.attr('locavore') == 'yes'){
				$.callManager({
					success : function(data){
						// alert(data.manager_id);
						// console.log(data);
						
						var sales_id = $('#counter').attr('sale');
						if(btn.attr('by') == 'select-items'){
							btn.goLoad();
							$.post(baseUrl+'cashier/save_split/'+sales_id+'/'+data.manager_username,function(data){
								console.log(data);
								if(data.error == "")
									window.location = baseUrl+'cashier';
								else{
									rMsg(data.error,'error');
									btn.goLoad({load:false});
								}
							// alert(data);
							// });
							},'json');
						}
						else if(btn.attr('by') == 'even-split'){
							var num = parseFloat($('#even-spit-num').text());
							var sales_id = $('#counter').attr('sale');
							// btn.goLoad();
							$.post(baseUrl+'cashier/even_split/'+num+'/'+sales_id,function(data){
								if(data.error != ""){
								// 	window.location = baseUrl+'cashier';
								// else{
									rMsg(data.error,'error');
									btn.goLoad({load:false});
								}else{
									window.location = baseUrl+'cashier';
								}
								// btn.goLoad({load:false});
							},'json');
							// alert(data);
							// });
						}
						else{
							rMsg('Select Split Action','error');
						}
					}
				});
			}
		});

		$(document).on('click','.pgbuttons',function(){
			var pg_id = $(this).attr('id');
			$('.'+pg_id).show();
			$('.pgbuttons').hide();
			$('.nav-div').show();
		});

		$('#prev-btn').click(function(){
			$('.pgbuttons').show();
			$('.nav-div').hide();
			$('.ptype').hide();
			loadDivs('cash-payment',true);
		});

		$('#discount-btn').click(function(){
			// loadDivs('credit-payment-div2',true); alert($('.credit-payment-div2').length);
			loadDivs('choosedisc',true);
			return false;
		});

		$('#add-discount-btn').click(function(){

			//check if may line discount na
			formData = 'button=alldisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){
				// alert(data);
				if(data.error != ""){
					rMsg(data.error,'error');
				}else{
					// $.callManager({
						// addData : 'Discount',
	 				// 	success : function(){
	 						
							$.rPopForm({
								loadUrl : 'discount/discount_settle_pop',
								passTo	: '',
								// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
								title	: '<font size="4">Discount Form</font>',
								noButton: 1,
								wide	: 1,
								// rform 	: 'guide_form',
								onComplete : function(){
												
											 }
							});
							
	 				// 	}	
 					// });

				}
						
			},'json');
			// });


			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			// loadsDiv('sel-discount',null,null,null);
			// loadDiscounts();
			return false;
		});
		$('#add-discount-line-btn').click(function(){
			// $.rPopForm({
			// 	loadUrl : 'discount/discount_pop',
			// 	passTo	: '',
			// 	// title	: '<font size="4">Guest: 5</br>Allocation: 1 SC / 1 PWD / 1 Diplomat / 1 Zero Rated / 1 Regular 500 Discount</br>Total Amount Payable: 7,589.00</br></font>',
			// 	title	: '<font size="4">Discount Form</font>',
			// 	noButton: 1,
			// 	wide	: 1,
			// 	// rform 	: 'guide_form',
			// 	onComplete : function(){
								
			// 				 }
			// });

			// $(document).on("click", ".show_form", function(event){
			// 	$("#div_form_disc").show();
			// });


			// var mods_mandatory = $('.mods-mandatory').val();
			// // alert(mods_mandatory);
			// if(mods_mandatory >= "1"){
			// 	var mod_name = $('.mod-group-name').val();
			// 	rMsg('You must select '+mods_mandatory+' \''+mod_name+'\'','error');
			// 	return false;
			// }
			formData = 'button=linedisc';
			$.post(baseUrl+'cashier/check_discount',formData,function(data){

				if(data.error != ""){
					rMsg(data.error,'error');
				}else{
				 // alert($('.sel-discount-div').length);
					loadsDiv('sel-discount',null,null,null);
					loadDiscounts();
				}
						
			},'json');


			return false;
		});

		$('.ps-container li').click(function(){
				$('.ps-container li').removeClass('selected');
				$(this).addClass('selected');
				return false;
		});

		function loadsDiv(type,id,opt,trans_id,other,mandatory){
			if(type=='sel-discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				// selectModMenu();
			}
			else if(type=='choosedisc'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				// selectModMenu();
			}
			else{
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
		}

		function loadDiscounts(){
			$.post(baseUrl+'cashier/get_discounts_settle',function(data){
				$('.select-discounts-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#item-disc-btn-'+id).click(function(){
						var idisc = $(this);
						$('#prcss-disc-line').attr('disc-id',opt.disc_id);
						$('#prcss-disc-line').attr('disc-code',opt.disc_code);
						// if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						// }
						// else{
						// 	$('#prcss-disc').attr('ref','all');
						// 	$('#disc-guests').attr('readOnly','true');
						// }
						if(opt.fix == 0){
							$('#prcss-disc').attr('ref','equal');
							$('#disc-guests').removeAttr('readOnly');
						}else{
							$('#prcss-disc').attr('ref','all');
							$('#disc-guests').attr('readOnly','true');
						}

							// $.callManager({
							// 	addData : 'Discount',
			 			// 		success : function(){
									loadsDiv('discount',null,null,null);
									$('.discount-div .title').text(idisc.text());
									$('.discount-div #rate-txt').number(opt.disc_rate,2);
									$('#disc-disc-id').val(opt.disc_id);
									$('#disc-disc-rate').val(opt.disc_rate);
									$('#disc-disc-code').val(opt.disc_code);
									$('#disc-no-tax').val(opt.no_tax);
									$('#disc-fix').val(opt.fix);
									$('#disc-guests').val(opt.guest);
									$.post(baseUrl+'cashier/load_disc_line_persons/'+opt.disc_code,function(data){
										$('.disc-persons-list-div').html(data.code);
										$.each(data.items,function(code,opt){
											$("#disc-person-rem-"+code).click(function(){
												var lin = $(this).parent().parent();
												$.post(baseUrl+'cashier/remove_person_disc_line_settle/'+opt.disc+'/'+code,function(data){
													lin.remove();
													rMsg('Person Removed.','success');

													$.each(data.affeted_row,function(code,opt){

														// txt = $('#trans-row-'+code+' .cost').text();

														// new_amt = Number(txt) + Number(opt.disc_amt);
														$('#trans-row-'+code+' .cost').text(opt.def_amt);
													});
													location.reload();
													// transTotal();
												// });
												},'json');
												return false;
											});

											$('#disc-person-'+code).click(function(){
												// alert('aw');
												$('.disc-person').removeClass('selected');
												code = $(this).attr('code');
												sname = $(this).attr('sname');
												// bday = $(this).attr('bday');

												$(this).addClass('selected');

												formData = 'code='+code+'&sname='+sname;
												$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
													// transTotal();
												});
											});

										});
									},'json');
			 				// 	}	
		 					// });

						
						return false;
					});
				});
			},'json');
		}

		$('#add-disc-person-line-btn').click(function(){
			// $('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				// alert(guests);
				// var ref = $(this).attr('ref');
				var ref = $('#prcss-disc-line').attr('disc-code');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

					var disc_code = $('#disc-disc-code').val();
					if(disc_code == "SNDISC" || disc_code == "PWDISC"){
						var disc_cust_code = $('#disc-cust-code').val();
						console.log(disc_cust_code.length);
						if(disc_cust_code == ""){
							rMsg('Card Number cannot be empty','error');
							return false;
						}
					}
				$.post(baseUrl+'cashier/add_person_disc_line',formData,function(data){
					// alert(data);
						$('#add-disc-person-btn').goLoad({load:false});
						if(data.error==""){
							$('.disc-persons-list-div').html(data.code);
							$.each(data.items,function(code,opt){
								$("#disc-person-rem-"+code).click(function(){
									var lin = $(this).parent().parent();
									$.post(baseUrl+'cashier/remove_person_disc_line_settle/'+opt.disc+'/'+code,function(data){
										lin.remove();
										rMsg('Person Removed.','success');
										
										$.each(data.affeted_row,function(code,opt){

											// txt = $('#trans-row-'+code+' .cost').text();

											// new_amt = Number(txt) + Number(opt.disc_amt);
											$('#trans-row-'+code+' .cost').text(opt.def_amt);
										});

										location.reload();
									// });
									},'json');
									return false;
								});
								$('#disc-person-'+code).click(function(){
									$('.disc-person').removeClass('selected');
									code = $(this).attr('code');
									sname = $(this).attr('sname');
									remarks = $(this).attr('remarks');
									// bday = $(this).attr('bday');

									$(this).addClass('selected');

									formData = 'code='+code+'&sname='+sname+'&remarks='+remarks;
									$.post(baseUrl+'cashier/select_discount_person',formData,function(data){
										// transTotal();
									});
								});
							});
							// transTotal();
						}
						else{
							rMsg(data.error,'error');
						}
				},'json');
				// });
			}
			return false;
		});

		$('#prcss-disc-line').click(function(){
			// alert('ss');
			var sel_p = $('.disc-person');
			var disc_id = $(this).attr('disc-id');
			var disc_code = $(this).attr('disc-code');
			// var disc_remarks = "";

			// if(sel_p.hasClass('selected')){
			var select = false;
			// if(disc_id == 1 || disc_id == 2){
				if(sel_p.hasClass('selected')){
					select = true;
				}
			// }else{
			// 	select = true;
			// }
			// alert('aw');
			$('.disc-person').each(function(){
				if($(this).hasClass('selected')){
					// select = true;
					remarks = ($(this).attr('remarks'));
					// var disc_remarks = $(this).attr('remarks');
				}
			});
			// alert('ew');


			// alert(remarks);

			if(select == true){

				var sel = $('.selected');
				var btn = $(this);
				var id = sel.attr("ref");

				if(id==''){ 
					id = '0';
				}


				if(id){

					
					var formData = 'disc_id='+disc_id+'&disc_code='+disc_code+'&disc_remarks='+remarks;
					// var formData = 'disc_id='+disc_id+'&disc_code='+disc_code;
					btn.prop('disabled', true);
					$.post(baseUrl+'cashier/discount_item_settle/'+id,formData,function(data){
						
						// $('#trans-row-'+id).text(data.discounted_amt);
						

						btn.prop('disabled', false);
						

						$.post(baseUrl+'cashier/submit_trans',function(data){
												
							if(data.error != null){
								rMsg(data.error,'error');
								// btn.prop('disabled', false);
							}
							else{
								rMsg('Discount has been added','success');
								location.reload();
							}

							// transTotal();
			   				
			   				
						},'json').fail( function(xhr, textStatus, errorThrown) {
								   alert(xhr.responseText);
								});	
					},'json');

				}else{
					rMsg('Please select an item','error');
				}
			
			}else{
				rMsg('Please select a person','error');

			}
			// $('#qty-btn-done').click();
			return false;
		});
	<?php elseif($use_js == 'splitJs'): ?>
		var scrolled=0;
		var transScroll=0;
		$('.counter-split-right .actions-div').perfectScrollbar({suppressScrollX: true});
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		loadTransCart();
		$('#save-split-btn').click(function(){
			var btn = $(this);
			if(btn.attr('locavore') == 'yes'){
				$.callManager({
					success : function(data){
						// alert(data.manager_id);
						// console.log(data);
						
						var sales_id = $('#counter').attr('sale');
						if(btn.attr('by') == 'select-items'){
							btn.goLoad();
							$.post(baseUrl+'cashier/save_split/'+sales_id+'/'+data.manager_username,function(data){
								console.log(data);
								if(data.error == "")
									window.location = baseUrl+'cashier';
								else{
									rMsg(data.error,'error');
									btn.goLoad({load:false});
								}
							// alert(data);
							// });
							},'json');
						}
						else if(btn.attr('by') == 'even-split'){
							var num = parseFloat($('#even-spit-num').text());
							var sales_id = $('#counter').attr('sale');
							// btn.goLoad();
							$.post(baseUrl+'cashier/even_split/'+num+'/'+sales_id,function(data){
								if(data.error != ""){
								// 	window.location = baseUrl+'cashier';
								// else{
									rMsg(data.error,'error');
									btn.goLoad({load:false});
								}else{
									window.location = baseUrl+'cashier';
								}
								// btn.goLoad({load:false});
							},'json');
							// alert(data);
							// });
						}
						else{
							rMsg('Select Split Action','error');
						}

					}
				});
			}else{
				var sales_id = $('#counter').attr('sale');
				if(btn.attr('by') == 'select-items'){
					btn.goLoad();
					$.post(baseUrl+'cashier/save_split/'+sales_id,function(data){
						console.log('select-items');
						console.log(data);
						if(data.error == "")
							window.location = baseUrl+'cashier';
						else{
							rMsg(data.error,'error');
							btn.goLoad({load:false});
						}
					// alert(data);
					// });
					},'json');
				}
				else if(btn.attr('by') == 'even-split'){
					var num = parseFloat($('#even-spit-num').text());
					var sales_id = $('#counter').attr('sale');
					// btn.goLoad();
					$.post(baseUrl+'cashier/even_split/'+num+'/'+sales_id,function(data){
						console.log('even-split');
						console.log(data);
						if(data.error != ""){
						// 	window.location = baseUrl+'cashier';
						// else{
							rMsg(data.error,'error');
							btn.goLoad({load:false});
						}else{
							window.location = baseUrl+'cashier';
						}
						// btn.goLoad({load:false});
					},'json');
					// alert(data);
					// });
				}
				else{
					rMsg('Select Split Action','error');
				}
			}
			return false;
		});
		$('.split-bys').click(function(){
			var load = $(this).attr('ref');
			$('#save-split-btn').attr('by',load);
			var btn = $(this);
			clearTransSplitCart(btn);
			loadDivs(load);
			return false;
		});
		$('#add-sel-block-btn').click(function(){
			newSelBlock();
			return false;
		});
		$('#cancel-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#even-up-btn,#even-down-btn').click(function(){
			var num = parseFloat($('#even-spit-num').text());
			var go = $(this).attr('num');
			if(go == 'up'){
				num += 1;
			}
			else{
				if(num > 2){
					num -= 1;
				}
			}
			$('#even-spit-num').text(num);
			return false;
		});
		$("#refresh-btn").click(function(){
			var btn = $(this);
			clearTransSplitCart(btn);
			return false;
		});
		function loadDivs(type){
			$('.loads-div').hide();
			$('.'+type+'-div').show();
		}
		function newSelBlock(){
			if($('#hid-num').exists()){
				var num = parseFloat($('#hid-num').val());
				num += 1;
				$('#hid-num').val(num);
			}
			else{
				$('<input>').attr({'type':'hidden','id':'hid-num'}).val(0).appendTo('.select-items-div');
				var num = 0;
			}
			$.post(baseUrl+'cashier/new_split_block/'+num,function(data){
				// var num = data.num;
				$('#add-btn-div').before(data.code);
				$('.counter-split-right .actions-div').perfectScrollbar('update');
				// alert(num);
				addDelFunc(num);
			},'json');
			// });
		}
		function addDelFunc(num){
			$('#sel-div-'+num+' .add-btn').click(function(){
				var sel = $('.selected');
				if(sel.exists()){
					if(sel.hasClass('trans-sub-row')){
						selectModMenu();
					}
					else if(sel.hasClass('trans-remarks-row')){
						selectModMenu();
					}
					else if(sel.hasClass('trans-subsub-row')){
						selectModMenu();
					}
					var id = sel.attr('trans-id');
					var btn = $(this);
					addToTransSplitCart(num,id,btn);
				}
				return false;
			});
			$('#sel-div-'+num+' .del-btn').click(function(){
				var sel = $('#sel-div-'+num+' .splicted');
				if(sel.exists()){
					var id = sel.attr('trans-id');
					var btn = $(this);
					minusToTransSplitCart(num,id,btn);
				}
				return false;
			});
			$('#sel-div-'+num+' .remove-btn').click(function(){
				var sel = $('#sel-div-'+num);
				if(sel.exists()){
					var btn = $(this);
					btn.goLoad();
					$.post(baseUrl+'cashier/remove_split_block/'+num,function(data){
						// alert(data);
						sel.parent().remove();
						$.each(data.content,function(id,qty){
							$('#trans-row-'+id).show();
							$('.trans-sub-row[trans-id="'+id+'"]').show();
							$('.trans-remarks-row[trans-id="'+id+'"]').show();
							$('#trans-row-'+id).find('.qty').text(qty);
							selector($('#trans-row-'+id));
							$('#even-spit-num').text('2');
						});
						btn.goLoad({load:false});
					// });
					},'json');
				}
				return false;
			});
		}
		function addToTransSplitCart(num,id,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/add_split_block/'+num+'/'+id,function(data){
				// alert(data);
				var sel = $('#trans-row-'+id).clone();
				if($('#trans-split-row-'+num+'-'+id).exists()){
					$('#trans-split-row-'+num+'-'+id).find('.qty').text(data.split_qty);
					splictor($('#trans-split-row-'+num+'-'+id));
				}
				else{
					var ul = $('#sel-div-'+num+' ul');
					sel.attr('id','trans-split-row-'+num+'-'+id);
					sel.attr('ref',num);
					sel.removeClass('trans-row');
					sel.removeClass('sel-row');
					sel.removeClass('selected');
					sel.addClass('trans-split-row');
					sel.addClass('split-row');
					sel.find('.qty').text(data.split_qty);
					sel.appendTo(ul).click(function(){
						splictor($(this));
						return false;
					});
					splictor($('#trans-split-row-'+num+'-'+id));
					if($('.trans-sub-row[trans-id="'+id+'"]').exists()){
						$('.trans-sub-row[trans-id="'+id+'"]').each(function(){
							var li = $(this).clone();
							li.addClass('trans-split-row-'+num+'-'+id);
							li.removeClass('trans-sub-row');
							li.removeClass('sel-row');
							li.appendTo(ul);
						});
					}
					if($('.trans-remarks-row[trans-id="'+id+'"]').exists()){
						$('.trans-remarks-row[trans-id="'+id+'"]').each(function(){
							var li = $(this).clone();
							li.addClass('trans-split-row-'+num+'-'+id);
							li.removeClass('trans-remarks-row');
							li.removeClass('sel-row');
							li.appendTo(ul);
						});
					}
					// if($('.trans-subsub-row[trans-id="'+id+'"]').exists()){
					// 	$('.trans-subsub-row[trans-id="'+id+'"]').each(function(){
					// 		var li = $(this).clone();
					// 		li.addClass('trans-split-row-'+num+'-'+id);
					// 		li.removeClass('trans-subsub-row');
					// 		li.removeClass('sel-row');
					// 		li.appendTo(ul);
					// 	});
					// }
				}
				if(data.from_qty <= 0){
					$('#trans-row-'+id).hide();
					$('#trans-row-'+id).removeClass('selected');
					$('.trans-sub-row[trans-id="'+id+'"]').hide();
					$('.trans-sub-row[trans-id="'+id+'"]').removeClass('selected');
					$('.trans-remarks-row[trans-id="'+id+'"]').hide();
					$('.trans-remarks-row[trans-id="'+id+'"]').removeClass('selected');
					// $('.trans-subsub-row[trans-id="'+id+'"]').hide();
					// $('.trans-subsub-row[trans-id="'+id+'"]').removeClass('selected');
				}
				else{
					$('#trans-row-'+id).find('.qty').text(data.from_qty);
				}
				btn.goLoad({load:false});
			},'json');
			// });
		}
		function minusToTransSplitCart(num,id,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/minus_split_block/'+num+'/'+id,function(data){
				if(data.from_qty > 0){
					$('#trans-row-'+id).show();
					$('.trans-sub-row[trans-id="'+id+'"]').show();
					$('.trans-subsub-row[trans-id="'+id+'"]').show();
					$('.trans-remarks-row[trans-id="'+id+'"]').show();
					$('#trans-row-'+id).find('.qty').text(data.from_qty);
					selector($('#trans-row-'+id));
				}
				if(data.split_qty <= 0){
					var sel = $('#sel-div-'+num+' .splicted');
					$('.trans-split-row-'+num+'-'+id).remove();
					sel.remove();
				}
				else{
					$('#trans-split-row-'+num+'-'+id).find('.qty').text(data.split_qty);
				}
				btn.goLoad({load:false});
			},'json');
			// });
		}
		function clearTransSplitCart(btn){
			var sel = $('.sel-div');
			btn.goLoad();
			$.post(baseUrl+'cashier/clear_split',function(data){
				sel.parent().remove();
				$('#hid-num').remove();
				$.each(data.content,function(id,qty){
					$('#trans-row-'+id).show();
					$('.trans-sub-row[trans-id="'+id+'"]').show();
					$('.trans-subsub-row[trans-id="'+id+'"]').show();
					$('.trans-remarks-row[trans-id="'+id+'"]').show();
					$('#trans-row-'+id).find('.qty').text(qty);
					selector($('#trans-row-'+id));
				});
				btn.goLoad({load:false});
			},'json');
		}
		function addTransCart(menu_id,opt){
			var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,menu_id,data.items);
				loadsDiv('mods',menu_id,data.items,data.id);
				transTotal();
			},'json');
		}
		function addTransModCart(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det){
			var formData = 'trans_id='+trans_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det.name+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg('Modifier is Already Added!','error');
				}
				else{
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det)
				}
				btn.prop('disabled', false);
				transTotal();
			},'json');
		}
		function makeItemRow(id,menu_id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-row-'+id,'trans-id':id,'ref':id,'class':'sel-row trans-row selected'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					return false;
				});
			$('<span/>').attr('class','qty').text(opt.qty).css('margin-left','10px').appendTo('#trans-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-row-'+id);
			$('<span/>').attr('class','cost').text(opt.cost).css('margin-right','10px').appendTo('#trans-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubRow(id,trans_id,mod_id,opt,trans_det){
			var subRow = $('<li/>').attr({'id':'trans-sub-row-'+trans_id+'-'+id,'trans-id':trans_id,'trans-mod-id':id,'ref':id,'class':'trans-sub-row sel-row'})
								   .click(function(){
										selector($(this));

										return false;
									});
			$('<span/>').attr('class','name').css('margin-left','26px').text(opt.name).appendTo(subRow);
			if(parseFloat(opt.cost) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').text(opt.cost).appendTo(subRow);
			$('.selected').after(subRow);
			$('.sel-row').removeClass('selected');
			selector($('#trans-sub-row-'+trans_id+'-'+id));
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeRemarksItemRow(id,remarks){
			$('.sel-row').removeClass('selected');
			if($('#trans-remarks-row-'+id).exists()){
				$('#trans-remarks-row-'+id).remove();
			}
			$('<li/>').attr({'id':'trans-remarks-row-'+id,'ref':id,'trans-id':id,'class':'sel-row trans-remarks-row selected'})
				.insertAfter('.trans-lists li#trans-row-'+id)
				.click(function(){
					selector($(this));
					return false;
				});
			// $('<span/>').attr('class','qty').html('').css('margin-left','10px').appendTo('#trans-remarks-row-'+id);
			$('<span/>').attr('class','name').css('margin-left','26px').html('<i class="fa fa-text-width"></i> '+remarks).appendTo('#trans-remarks-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function selector(li){
			$('.sel-row').removeClass('selected');
			li.addClass('selected');
		}
		function splictor(li){
			$('.split-row').removeClass('splicted');
			li.addClass('splicted');
		}
		function selectModMenu(){
			var sel = $('.selected');
			if(sel.hasClass('trans-sub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
			if(sel.hasClass('trans-remarks-row')){
				var trans_id = sel.attr("ref");
				selector($('#trans-row-'+trans_id));
			}
			if(sel.hasClass('trans-subsub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
		}
		function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;

				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
			},'json');
		}
		function loadTransCart(){
			$.post(baseUrl+'cashier/get_trans_cart/',function(data){
				
				// alert(data);
				// console.log(data);

				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					$.each(data,function(trans_id,opt){
						makeItemRow(trans_id,opt.menu_id,opt);
						if(opt.remarks != "" && opt.remarks != null){
							makeRemarksItemRow(trans_id,opt.remarks);
						}
						var modifiers = opt.modifiers;
						if(!$.isEmptyObject(modifiers)){
							$.each(modifiers,function(trans_mod_id,mopt){
								// alert(mopt.mod_line_id);
								makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,mopt);
								var submodifiers = mopt.submodifiers;
								if(!$.isEmptyObject(submodifiers)){
								// alert('aw');
									$.each(submodifiers,function(trans_submod_id,smopt){
										if(smopt.mod_line_id == mopt.mod_line_id){
											// alert(mopt.mod_line_id+' === '+smopt.mod_line_id);
											makeItemSubSubRowLoad(trans_submod_id,trans_id,smopt.mod_id,smopt,mopt,'loaded',smopt.mod_line_id,trans_mod_id);
										}
									});
								}

							});
						}
						ctr++;
					});
				}
				transTotal();
			// });
			},'json');
		}
		function checkWagon(name){
			$.post(baseUrl+'wagon/get_wagon/'+name,function(data){
				alert(data)
			// },'json');
			});
		}
		function makeItemSubSubRowLoad(id,trans_id,mod_id,opt,trans_det,loaded='',mod_line_id,trans_mod_id){
			var subRow = $('<li/>').attr({'id':'trans-subsub-row-'+id,'trans-id':trans_id,'trans-mod-id':trans_mod_id,'trans-submod-id':id,'ref':id,'class':'trans-subsub-row sel-row'})
								   .click(function(){
										selector($(this));
										// loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});

			var submod_name = opt.name;					   
			// if (opt.hasOwnProperty('kitchen_slip_printed')) {
			// 	if(opt.kitchen_slip_printed == 1)
			// 		submod_name = ' <i class="fa fa-print"></i> '+submod_name;
			// }
			// alert(subRow);

			$('<span/>').attr('class','name').css('margin-left','50px').html(submod_name).appendTo(subRow);
			if(parseFloat(opt.price) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.price).appendTo(subRow);

			// if(dflt == "default"){
			// 	$('#trans-row-'+trans_id).after(subRow);
			// }
			// else{
				$('#trans-sub-row-'+trans_id+'-'+mod_line_id).append(subRow);
			// }

			// $('.sel-row').removeClass('selected');
			// selector($('#trans-sub-row-'+trans_id+'-'+id));
			// if(loaded==""){
				// selector2($('#trans-sub-row-'+trans_id+'-'+id));
				// selector2($('#trans-row-'+trans_id));
			// }
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
	<?php elseif($use_js == 'combineJs'): ?>
		var scrolled=0;
		var transScroll=0;
		$('.counter-split-right .actions-div').perfectScrollbar({suppressScrollX: true});
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		$('.orders-list-combine').perfectScrollbar({suppressScrollX: true});
		$('.orders-to-combine').perfectScrollbar({suppressScrollX: true});
		loadTransCart();
		loadMenuCategories();
		$('#combine-btn').click(function(){

			if($(this).attr('locavore') == 'yes'){
				$.callManager({
					success : function(data){
						$('#combine-btn').goLoad();
						$.post(baseUrl+'cashier/save_combine/'+data.manager_username,function(data){
							window.location = baseUrl+'cashier';
						});
					}
				});
			}else{
				$(this).goLoad();
				$.post(baseUrl+'cashier/save_combine',function(data){
					// alert(data);
					// console.log(data);
					window.location = baseUrl+'cashier';
				});
			}


			return false;
		});
		$('#cancel-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#clear-btn').click(function(){
			$('.combine-row').remove();
			$.post(baseUrl+'wagon/clear_wagon/trans_combine_cart/',function(data){
				$('#refresh-btn').trigger('click');
			});
			return false;
		});
		$('#refresh-btn').click(function(){
			var terminal = $('.orders-list-combine').attr('terminal');
			var types = $('.orders-list-combine').attr('types');
			loadOrders(terminal,types);
			return false;
		});
		$('.my-all-btns').click(function(){
			var terminal = $(this).attr('ref');
			var types = $('.orders-list-combine').attr('types');
			$('.orders-list-combine').attr('terminal',terminal);
			loadOrders(terminal,types);
			return false;
		});
		function loadMenuCategories(){
				var data = {
					"All TYPES": {"id":"all"},
					"DINE IN": {"id":"dinein"},
					"DELIVERY": {"id":"delivery"},
					"COUNTER": {"id":"counter"},
					// "RETAIL": {"id":"retail"},
					"PICKUP": {"id":"pickup"},
					"TAKEOUT": {"id":"takeout"},
					"DRIVE-THRU": {"id":"drivethru"},
				}
				var ctr = 1;
				$.each(data,function(txt,opt){
		 			$('<button/>')
		 			.attr({'id':opt.id+'-btn','ref':opt.id,'class':'types-btns btn-block category-btns counter-btn-teal double btn btn-default'})
		 			.text(txt)
		 			.appendTo('.type-container')
		 			.click(function(){
		 				var terminal = $('.orders-list-combine').attr('terminal');
		 				loadOrders(terminal,opt.id);
						$('.orders-list-combine').attr('types',opt.id);
		 				loadOrders(terminal,opt.id);		 				return false;
		 			});
					if(ctr == 1){
						$('#'+opt.id+'-btn').trigger('click');
					}
					ctr++;
				});
		}
		function loadOrders(terminal,types){
			$('.orders-list-combine').html('<center><div style="padding-top:20px"><i class="fa fa-spinner fa-lg fa-fw fa-spin aw"></i></div></center>');
			$.post(baseUrl+'cashier/orders/'+terminal+'/open/'+types+'/null/none/0/combineList',function(data){
				$('.orders-list-combine').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						addDelFunc(id,val);
					});
					$('.orders-list-combine').perfectScrollbar('update');
				}
			},'json');
			// alert(data);
			// });
		}
		function addDelFunc(id,val){
			$('#add-to-btn-'+id).click(function(){
				var btn = $(this);
				var formData = 'sales_id='+id+'&balance='+val.amount;
				var clone = $('#order-btnish-'+id).clone();
				var orig = $('#order-btnish-'+id).clone();
				btn.goLoad();
				$.post(baseUrl+'wagon/add_to_wagon/trans_combine_cart',formData,function(data){
					var com_id = data.id;
					var btn = $('<button/>')
								.html('<i class="fa fa-times fa-lg fa-fw"></i>')
								.attr({'id':'remove-combine-btn-'+id,'ref':id,'class':'btn-block counter-btn-red'})
								.click(function(){

									var rBtn = $(this);
									rBtn.goLoad();
									$.post(baseUrl+'wagon/delete_to_wagon/trans_combine_cart/'+com_id,function(data){
										$('.orders-list-combine .orders-list-div-btnish:first-child').before(orig);
										$('#combine-row-'+com_id).remove();
										addDelFunc(id,val);
										$('.orders-list-combine').perfectScrollbar('update');
										rBtn.goLoad({load:false});
									},'json');
									return false;
								});
					clone
					.attr('id','combine-row-'+com_id)
					.addClass('combine-row')
					.find('.add-btn-row').remove();
					clone.find('.order-btn-right-container').append(btn);
					clone.appendTo('.orders-to-combine');
					$('#order-btnish-'+id).remove();
					$('.orders-to-combine.orders-to-combine').perfectScrollbar('update');
					btn.goLoad({load:false});
				},'json');
				return false;
			});
		}
		function loadDivs(type){
			$('.loads-div').hide();
			$('.'+type+'-div').show();
		}
		function addTransCart(menu_id,opt){
			var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,menu_id,data.items);
				loadsDiv('mods',menu_id,data.items,data.id);
				transTotal();
			},'json');
		}
		function addTransModCart(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det){
			var formData = 'trans_id='+trans_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det.name+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg('Modifier is Already Added!','error');
				}
				else{
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det)
				}
				btn.prop('disabled', false);
				transTotal();
			},'json');
		}
		function makeItemRow(id,menu_id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-row-'+id,'trans-id':id,'ref':id,'class':'sel-row trans-row selected'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));

					return false;
				});
			$('<span/>').attr('class','qty').text(opt.qty).css('margin-left','10px').appendTo('#trans-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-row-'+id);
			$('<span/>').attr('class','cost').text(opt.cost).css('margin-right','10px').appendTo('#trans-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubRow(id,trans_id,mod_id,opt,trans_det){
			var subRow = $('<li/>').attr({'id':'trans-sub-row-'+trans_id+'-'+id,'trans-id':trans_id,'trans-mod-id':id,'ref':id,'class':'trans-sub-row sel-row'})
								   .click(function(){
										selector($(this));

										return false;
									});
			$('<span/>').attr('class','name').css('margin-left','26px').text(opt.name).appendTo(subRow);
			if(parseFloat(opt.cost) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').text(opt.cost).appendTo(subRow);
			$('.selected').after(subRow);
			$('.sel-row').removeClass('selected');
			selector($('#trans-sub-row-'+trans_id+'-'+id));
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeRemarksItemRow(id,remarks){
			$('.sel-row').removeClass('selected');
			if($('#trans-remarks-row-'+id).exists()){
				$('#trans-remarks-row-'+id).remove();
			}
			$('<li/>').attr({'id':'trans-remarks-row-'+id,'ref':id,'class':'sel-row trans-remarks-row selected'})
				.insertAfter('.trans-lists li#trans-row-'+id)
				.click(function(){
					selector($(this));
					return false;
				});
			// $('<span/>').attr('class','qty').html('').css('margin-left','10px').appendTo('#trans-remarks-row-'+id);
			$('<span/>').attr('class','name').css('margin-left','26px').html('<i class="fa fa-text-width"></i> '+remarks).appendTo('#trans-remarks-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function selector(li){
			$('.sel-row').removeClass('selected');
			li.addClass('selected');
		}
		function selectModMenu(){
			var sel = $('.selected');
			if(sel.hasClass('trans-sub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
		}
		function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;

				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
			},'json');
		}
		function loadTransCart(){
			$.post(baseUrl+'cashier/get_trans_cart/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					$.each(data,function(trans_id,opt){
						makeItemRow(trans_id,opt.menu_id,opt);
						if(opt.remarks != "" && opt.remarks != null){
							makeRemarksItemRow(trans_id,opt.remarks);
						}
						var modifiers = opt.modifiers;
						if(!$.isEmptyObject(modifiers)){
							$.each(modifiers,function(trans_mod_id,mopt){
								makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,mopt);
								var submodifiers = mopt.submodifiers;
								if(!$.isEmptyObject(submodifiers)){
								// alert('aw');
									// alert(mopt.mod_line_id);
									$.each(submodifiers,function(trans_submod_id,smopt){
										if(smopt.mod_line_id == mopt.mod_line_id){
										// alert('aw');
											makeItemSubSubRowLoad(trans_submod_id,trans_id,smopt.mod_id,smopt,mopt,'loaded',smopt.mod_line_id,trans_mod_id);
										}
									});
								}



							});
						}
						ctr++;
					});
				}
				transTotal();
			},'json');
		}
		function checkWagon(name){
			$.post(baseUrl+'wagon/get_wagon/'+name,function(data){
				alert(data)
			// },'json');
			});
		}
		function makeItemSubSubRowLoad(id,trans_id,mod_id,opt,trans_det,loaded='',mod_line_id,trans_mod_id){
			var subRow = $('<li/>').attr({'id':'trans-subsub-row-'+id,'trans-id':trans_id,'trans-mod-id':trans_mod_id,'trans-submod-id':id,'ref':id,'class':'trans-subsub-row sel-row'})
								   .click(function(){
										selector($(this));
										// loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});
			var submod_name = opt.name;					   
			// if (opt.hasOwnProperty('kitchen_slip_printed')) {
			// 	if(opt.kitchen_slip_printed == 1)
			// 		submod_name = ' <i class="fa fa-print"></i> '+submod_name;
			// }
			// alert(subRow);

			$('<span/>').attr('class','name').css('margin-left','50px').html(submod_name).appendTo(subRow);
			if(parseFloat(opt.price) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.price).appendTo(subRow);

			// if(dflt == "default"){
			// 	$('#trans-row-'+trans_id).after(subRow);
			// }
			// else{
				$('#trans-sub-row-'+trans_id+'-'+mod_line_id).append(subRow);
			// }

			// $('.sel-row').removeClass('selected');
			// selector($('#trans-sub-row-'+trans_id+'-'+id));
			// if(loaded==""){
				// selector2($('#trans-sub-row-'+trans_id+'-'+id));
				// selector2($('#trans-row-'+trans_id));
			// }
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
	<?php elseif($use_js == 'tablesJs'): ?>
	    $('#guest-input').keypress(function(event){
          if(event.keyCode == 13){
           $('#guest-enter-btn').trigger('click');
          }
        });
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#back-btn,#back-occ-btn').click(function(){
			loadDivs('select-table');
			return false;
		});
		$('#guest-enter-btn').click(function(){
			var type = $('#dine_type').val();
			var tbl = $('#select-table').attr('ref');
			var tbl_name = $('#select-table').attr('ref_name');
			var guest = $('#guest-input').val();
			var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
			if($.isNumeric(guest)){
				// $.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
					// window.location = baseUrl+'cashier/counter/'+type;
				// },'json');
				// swal({
				//   title: 'Please confirm that the guest count is '+ guest +'.',
				//   // text: "You won't be able to revert this!",
				//   icon: 'warning',
				//   showCancelButton: true,
				//   confirmButtonColor: '#3085d6',
				//   cancelButtonColor: '#d33',
				//   confirmButtonText: 'Yes , I confirm!'
				// },
				// function(isConfirm){
				// 	if(isConfirm){
				// 		var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
				// 		$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
				// 			window.location = baseUrl+'cashier/counter/'+type;
				// 		},'json');
				// 	}else{
				// 		return false;
				// 	}
				// });

				var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
				$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
					formData = 'txt='+type;
	       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
	       				// alert(data);
						// window.location = baseUrl+'cashier/counter/'+type;
						window.location = baseUrl+'cashier/counter/'+data;
	       			});
				},'json');
			}
			else{
				rMsg('Invalid guest number.','error');
			}
			return false;
		});
		$('#start-new-btn').click(function(){
			loadDivs('no-guest');
			return false;
		});
		$.post(baseUrl+'cashier/get_branch_details',function(data){
			var img = data.layout;
			if(img != "" ){
				var img_real_width=0,
				    img_real_height=0;
				$("<img/>")
				.attr("src", img)
			    .attr("id", "image-layout")
			    .load(function(){
		           img_real_width = this.width;
		           img_real_height = this.height;
		           $(this).appendTo('#image-con');
		           $("<div/>")
				    .attr("class", "rtag")
				    .attr("id", "rtag-div")
				    .css("height", img_real_height)
				    .css("width", img_real_width)
				    .appendTo('#image-con');
					loadMarks();

				});
			}
		},'json');

		
		function updateTblStatus(){
			$.post(baseUrl+'cashier/get_tbl_status',{type:'dinein'},function(data){
				// alert(data); console.log(data);
				$.each(data,function(tbl_id,val){
					var mark = $('#mark-'+tbl_id);
					mark.removeClass('marker-green');
					mark.removeClass('marker-orange');
					mark.removeClass('marker-red');
					mark.addClass('marker-'+val.stat);
					mark.unbind('click');
					if(val.stat == 'green'){
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('no-guest');
			    					$('#guest-input').focus();
								}	
							},'json');	
						});
					}
					else{
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									
									$.post(baseUrl+'cashier/check_ifcombined/'+tbl_id,function(data2){
										
										if(data2.data == 'combined'){
											$("#occ-num").text(val.name);
											$("#combined-msg").text('(Combined to table '+data2.main_tbl_name+')');
											$('#select-table').attr('ref',tbl_id);
											$('#select-table').attr('ref_name',val.name);
											loadDivs('occupied');
											get_table_orders(data2.main_tbl_id);
										}else{
											$("#occ-num").text(val.name);
											$('#select-table').attr('ref',tbl_id);
											$('#select-table').attr('ref_name',val.name);
											loadDivs('occupied');
											get_table_orders(tbl_id);
										}
									},'json');
								}else{
									rMsg(data.error,'error');
								}	
							},'json');	
						});
					}
				});
			// });	
			},'json');	
			setTimeout(function(){
		  		updateTblStatus();
			}, 3000);	
		}	
		// checkOccupied();
		function loadMarks(){
			var type = $('#dine_type').val();
			// alert(type);
			$.post(baseUrl+'cashier/get_tables_other/true/'+null+'/'+type,function(data){
				// console.log(data);
				// alert(data);
				$.each(data,function(tbl_id,val){
					$('<a/>')
	    			.attr('href','#')
	    			// .attr('class','marker-red')
	    			.attr('class','markers marker-'+val.stat)
	    			.attr('id','mark-'+tbl_id)
	    			.attr('ref',tbl_id)
	    			.css('top',val.top+'px')
	    			.css('left',val.left+'px')
	    			.html('<h5 style="text-align:center;padding-top:7px;color:#fff">'+val.name+'</h5>')
	    			.appendTo('#rtag-div')
	    			.click(function(e){
	    				$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
		    				if(data.error == ""){
			    				if(val.stat == 'red'){
			    					$("#occ-num").text(val.name);
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('occupied');
			    					get_table_orders(tbl_id);

			    				}
			    				else{
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('no-guest');
			    					$('#guest-input').focus();
			    				}
		    				}
		    				else{
		    					rMsg(data.error,'error');
		    				}
	    				},'json');
	    				return false;
    				});
				});
				// checkOccupied();
				updateTblStatus();
			},'json');
			// });
		}
		function get_table_orders(tbl_id){
			$('.occ-orders-div').html('<br><center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
			$.post(baseUrl+'cashier/get_table_orders/true/'+tbl_id,function(data){
				$('.occ-orders-div').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						$('#order-btn-'+id).click(function(){

							//check if settled na
							$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
								if(data1 == 1){
									rMsg('This transaction has been settled.','error');
								}else{
									$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
										if(data.error == ""){
											formData = 'txt='+val.type;
							       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data2){
							       				// alert(data);
							       				// console.log(data);
												// window.location = baseUrl+'cashier/counter/'+val.type+'/'+id;
												window.location = baseUrl+'cashier/counter/'+data2+'/'+id;
							       			});
										}else{
											rMsg(data.error,'error');
										}	
									},'json');
								}
							});
							return false;
						});
						$("#transfer-btn-"+id).click(function(){

							// if($("#transfer-btn-"+id).attr('locavore') == 'yes'){

								$.callManager({
									success : function(data2){
										// alert('aw');
										$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
											// alert(id);
											if(data1 == 1){
												rMsg('This transaction has been settled.','error');
											}else{
												bootbox.dialog({
												  message: baseUrl+'cashier/transfer_tables/'+id,
												  // title: 'Somthing',
												  className: 'manager-call-pop',
												  buttons: {
												    submit: {
												      label: "Transfer",
												      className: "btn  pop-manage pop-manage-green",
												      callback: function() {
												        var sales_id = id;
												        var to_table = $('#to-table').val();
												       	$.post(baseUrl+'cashier/go_transfer_table/'+sales_id+'/'+to_table+'/'+data2.manager_username,function(data){
												       		// alert(data);
												       		if(data == ""){
												       			location.reload();
												       		}
												       	});
												        // return true;
												      }
												    },
												    cancel: {
												      label: "CANCEL",
												      className: "btn pop-manage pop-manage-red",
												      callback: function() {
												        // Example.show("uh oh, look out!");
												      }
												    }
												  }
												});
											}
										});

									}
								});

							// }else{
							// 	$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
							// 		if(data1 == 1){
							// 			rMsg('This transaction has been settled.','error');
							// 		}else{
							// 			bootbox.dialog({
							// 			  message: baseUrl+'cashier/transfer_tables',
							// 			  // title: 'Somthing',
							// 			  className: 'manager-call-pop',
							// 			  buttons: {
							// 			    submit: {
							// 			      label: "Transfer",
							// 			      className: "btn  pop-manage pop-manage-green",
							// 			      callback: function() {
							// 			        var sales_id = id;
							// 			        var to_table = $('#to-table').val();
							// 			       	$.post(baseUrl+'cashier/go_transfer_table/'+sales_id+'/'+to_table,function(data){
							// 			       		if(data == ""){
							// 			       			location.reload();
							// 			       		}
							// 			       	});
							// 			        // return true;
							// 			      }
							// 			    },
							// 			    cancel: {
							// 			      label: "CANCEL",
							// 			      className: "btn pop-manage pop-manage-red",
							// 			      callback: function() {
							// 			        // Example.show("uh oh, look out!");
							// 			      }
							// 			    }
							// 			  }
							// 			});
							// 		}
							// 	});
							// }

							return false;
						});
						$('#print-btn-'+id).click(function(){
							$.post(baseUrl+'cashier/print_sales_receipt/'+id,'',function(data){
								rMsg(data.msg,'success');
							},'json');
							return false;
						});	
						$('#print-os-btn-'+id).click(function(){

							$.post(baseUrl+'cashier/check_printer_online',function(data){

								if(data.ping_error == 0){
									
									$.post(baseUrl+'cashier/print_os/'+id+'/0/1','',function(data){
										rMsg('Order Slip Reprinted.','success');
									});

								}else{
									// alert(id);

									$.rPopForm({
										loadUrl : 'cashier/printer_option',
										passTo	: 'cashier/print_setup_update_db',
										// title	: '<center>Hello ,</br>What task would you like me to do for you?</center>',
										// noButton: 1,
										hide : 1,
										rform 	: 'printer-setup-form',
										onComplete : function(data){
														// goTo('menu/subcategories');
														// alert(data);
														var prev = $('#now-btn').attr('type');
														var ur = 0;
														if(prev == 'all_trans'){
															ur = 1;
														}
														$.post(baseUrl+'cashier/print_os/'+id+'/0/1/'+ur,'',function(data){
															// alert(data);
															// console.log(data);
															rMsg('Order Slip Reprinted.','success');
														});

													 }
									});
									
								}
								

							},'json');

							return false;
						});	

						$("#combine-btn-"+id).click(function(){

							$.callManager({
								success : function(data2){
									// alert('aw');
									$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
										// alert(id);
										if(data1 == 1){
											rMsg('This transaction has been settled.','error');
										}else{
											bootbox.dialog({
											  message: baseUrl+'cashier/combine_tables/'+id,
											  // title: 'Somthing',
											  className: 'manager-call-pop',
											  buttons: {
											    submit: {
											      label: "Combine",
											      className: "btn  pop-manage pop-manage-green",
											      callback: function() {
											        var sales_id = id;
											        var to_table = $('#to-table').val();
											       	$.post(baseUrl+'cashier/go_combine_table/'+sales_id+'/'+to_table+'/'+data2.manager_username,function(data){
											       		// alert(data);
											       		if(data == ""){
											       			location.reload();
											       		}
											       	});
											        // return true;
											      }
											    },
											    cancel: {
											      label: "CANCEL",
											      className: "btn pop-manage pop-manage-red",
											      callback: function() {
											        // Example.show("uh oh, look out!");
											      }
											    }
											  }
											});
										}
									});

								}
							});


							return false;
						});
					});
				}
			},'json');
		}
		function checkOccupied(){
			// alert('here');
			$.post(baseUrl+'cashier/check_occupied_tables',function(data){
				console.log(data);
				var occ = data.occ;
				var ucc = data.ucc;
				$.each(occ,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-green')){
						// alert(tbl['id']);
						mark.removeClass('marker-green');
						mark.addClass('marker-red');
						mark.unbind('click');
						mark.click(function(e){
							$("#occ-num").text(tbl['name']);
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('occupied');
							get_table_orders(tbl['id']);
							return false;
						});
					}
				});
				$.each(ucc,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-red')){
						// alert(tbl['id']);
						mark.removeClass('marker-red');
						mark.addClass('marker-green');
						mark.unbind('click');
						mark.click(function(e){
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('no-guest');
							return false;
						});
					}
				});
			},'json');	
				// alert(data);
			// });
			setTimeout(function(){
		  		checkOccupied();
			}, 1000);	
		}
		function checkUnOccupied(){
		}
		function loadDivs(type){
			$('.loads-div').hide();
			$('.'+type+'-div').show();
		}
	<?php elseif($use_js == 'tablesOtherJs'): ?>
	    $('#guest-input').keypress(function(event){
          if(event.keyCode == 13){
           $('#guest-enter-btn').trigger('click');
          }
        });
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#back-btn,#back-occ-btn').click(function(){
			loadDivs('select-table');
			return false;
		});
		$('#guest-enter-btn').click(function(){
			var type = $('#dine_type').val();
			var tbl = $('#select-table').attr('ref');
			var tbl_name = $('#select-table').attr('ref_name');
			var guest = $('#guest-input').val();
			var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
			if($.isNumeric(guest)){
				// $.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
					// window.location = baseUrl+'cashier/counter/'+type;
				// },'json');
				swal({
				  title: 'Please confirm that the guest count is '+ guest +'.',
				  // text: "You won't be able to revert this!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes , I confirm!'
				},
				function(isConfirm){
					if(isConfirm){
						var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
						$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
							window.location = baseUrl+'cashier/counter/'+type;
						},'json');
					}else{
						return false;
					}
				});
			}
			else{
				rMsg('Invalid guest number.','error');
			}
			return false;
		});
		$('#start-new-btn').click(function(){
			loadDivs('no-guest');
			return false;
		});
		$.post(baseUrl+'cashier/get_branch_details',function(data){
			// var img = data.layout;
			var img = baseUrl+'uploads/static_layout2.png';
			if(img != "" ){
				var img_real_width=0,
				    img_real_height=0;
				$("<img/>")
				.attr("src", img)
			    .attr("id", "image-layout")
			    .load(function(){
		           img_real_width = this.width;
		           img_real_height = this.height;
		           $(this).appendTo('#image-con');
		           $("<div/>")
				    .attr("class", "rtag")
				    .attr("id", "rtag-div")
				    .css("height", img_real_height)
				    .css("width", img_real_width)
				    .appendTo('#image-con');
					loadMarks();

				});
			}
		},'json');

		
		function updateTblStatus(){
			$.post(baseUrl+'cashier/get_tbl_status',function(data){
				$.each(data,function(tbl_id,val){
					var mark = $('#mark-'+tbl_id);
					mark.removeClass('marker-green');
					mark.removeClass('marker-orange');
					mark.removeClass('marker-red');
					mark.addClass('marker-'+val.stat);
					mark.unbind('click');
					if(val.stat == 'green'){
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					// loadDivs('no-guest');
			    					// $('#guest-input').focus();

			    					var type = $('#dine_type').val();
			    					var formData = 'type='+type+'&table='+tbl_id+'&table_name='+val.name+'&guest=1';
			    					// alert(formData);
									$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
										window.location = baseUrl+'cashier/counter/'+type;
									},'json');


								}	
							},'json');	
						});
					}
					else{
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									$("#occ-num").text(val.name);
									$('#select-table').attr('ref',tbl_id);
									$('#select-table').attr('ref_name',val.name);
									loadDivs('occupied');
									get_table_orders(tbl_id);
								}else{
									rMsg(data.error,'error');
								}	
							},'json');	
						});
					}
				});
			},'json');	
			setTimeout(function(){
		  		updateTblStatus();
			}, 3000);	
		}	
		// checkOccupied();
		function loadMarks(){
			var type = $('#dine_type').val();
			// alert(type);
			$.post(baseUrl+'cashier/get_tables_other/true/'+null+'/'+type,function(data){
				// alert(data);
				$.each(data,function(tbl_id,val){
					$('<a/>')
	    			.attr('href','#')
	    			// .attr('class','marker-red')
	    			.attr('class','markers marker-'+val.stat)
	    			.attr('id','mark-'+tbl_id)
	    			.attr('ref',tbl_id)
	    			.css('top',val.top+'px')
	    			.css('left',val.left+'px')
	    			.html('<h5 style="text-align:center;padding-top:7px;color:#fff">'+val.name+'</h5>')
	    			.appendTo('#rtag-div')
	    			.click(function(e){
	    				$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
		    				if(data.error == ""){
			    				if(val.stat == 'red'){
			    					$("#occ-num").text(val.name);
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('occupied');
			    					get_table_orders(tbl_id);

			    				}
			    				else{
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					// loadDivs('no-guest');
			    					// $('#guest-input').focus();
			    				}
		    				}
		    				else{
		    					rMsg(data.error,'error');
		    				}
	    				},'json');
	    				return false;
    				});
				});
				// checkOccupied();
				updateTblStatus();
			},'json');
			// });
		}
		function get_table_orders(tbl_id){
			$('.occ-orders-div').html('<br><center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
			$.post(baseUrl+'cashier/get_table_orders_other/true/'+tbl_id,function(data){
				$('.occ-orders-div').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						$('#order-btn-'+id).click(function(){

							//check if settled na
							$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
								if(data1 == 1){
									rMsg('This transaction has been settled.','error');
								}else{
									$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
										if(data.error == ""){
											window.location = baseUrl+'cashier/counter/'+val.type+'/'+id;
										}else{
											rMsg(data.error,'error');
										}	
									},'json');
								}
							});
							return false;
						});
						$("#transfer-btn-"+id).click(function(){

							// if($("#transfer-btn-"+id).attr('locavore') == 'yes'){

								$.callManager({
									success : function(data2){
										// alert('aw');
										$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
											// alert(id);
											if(data1 == 1){
												rMsg('This transaction has been settled.','error');
											}else{
												bootbox.dialog({
												  message: baseUrl+'cashier/transfer_tables/'+id,
												  // title: 'Somthing',
												  className: 'manager-call-pop',
												  buttons: {
												    submit: {
												      label: "Transfer",
												      className: "btn  pop-manage pop-manage-green",
												      callback: function() {
												        var sales_id = id;
												        var to_table = $('#to-table').val();
												       	$.post(baseUrl+'cashier/go_transfer_table/'+sales_id+'/'+to_table+'/'+data2.manager_username,function(data){
												       		// alert(data);
												       		if(data == ""){
												       			location.reload();
												       		}
												       	});
												        // return true;
												      }
												    },
												    cancel: {
												      label: "CANCEL",
												      className: "btn pop-manage pop-manage-red",
												      callback: function() {
												        // Example.show("uh oh, look out!");
												      }
												    }
												  }
												});
											}
										});

									}
								});

							// }else{
							// 	$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
							// 		if(data1 == 1){
							// 			rMsg('This transaction has been settled.','error');
							// 		}else{
							// 			bootbox.dialog({
							// 			  message: baseUrl+'cashier/transfer_tables',
							// 			  // title: 'Somthing',
							// 			  className: 'manager-call-pop',
							// 			  buttons: {
							// 			    submit: {
							// 			      label: "Transfer",
							// 			      className: "btn  pop-manage pop-manage-green",
							// 			      callback: function() {
							// 			        var sales_id = id;
							// 			        var to_table = $('#to-table').val();
							// 			       	$.post(baseUrl+'cashier/go_transfer_table/'+sales_id+'/'+to_table,function(data){
							// 			       		if(data == ""){
							// 			       			location.reload();
							// 			       		}
							// 			       	});
							// 			        // return true;
							// 			      }
							// 			    },
							// 			    cancel: {
							// 			      label: "CANCEL",
							// 			      className: "btn pop-manage pop-manage-red",
							// 			      callback: function() {
							// 			        // Example.show("uh oh, look out!");
							// 			      }
							// 			    }
							// 			  }
							// 			});
							// 		}
							// 	});
							// }

							return false;
						});
						$('#print-btn-'+id).click(function(){
							$.post(baseUrl+'cashier/print_sales_receipt/'+id,'',function(data){
								rMsg(data.msg,'success');
							},'json');
							return false;
						});	
						$('#print-os-btn-'+id).click(function(){
							$.post(baseUrl+'cashier/print_os/'+id+'/0/1','',function(data){
								rMsg('Order Slip Reprinted.','success');
							});
							return false;
						});	
					});
				}
			},'json');
		}
		function checkOccupied(){
			// alert('here');
			$.post(baseUrl+'cashier/check_occupied_tables',function(data){
				console.log(data);
				var occ = data.occ;
				var ucc = data.ucc;
				$.each(occ,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-green')){
						// alert(tbl['id']);
						mark.removeClass('marker-green');
						mark.addClass('marker-red');
						mark.unbind('click');
						mark.click(function(e){
							$("#occ-num").text(tbl['name']);
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('occupied');
							get_table_orders(tbl['id']);
							return false;
						});
					}
				});
				$.each(ucc,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-red')){
						// alert(tbl['id']);
						mark.removeClass('marker-red');
						mark.addClass('marker-green');
						mark.unbind('click');
						mark.click(function(e){
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('no-guest');
							return false;
						});
					}
				});
			},'json');	
				// alert(data);
			// });
			setTimeout(function(){
		  		checkOccupied();
			}, 1000);	
		}
		function checkUnOccupied(){
		}
		function loadDivs(type){
			$('.loads-div').hide();
			$('.'+type+'-div').show();
		}
	<?php elseif($use_js == 'tableTransferJs'): ?>
		$('.reason-btns').click(function(){
			var reason = $(this).attr('ref');
			// $('.reason-btns').attr('style','background-color:#D12027 !important;');
			$('.reason-btns').each(function(){
				var stat = $(this).attr('stat');
				if(stat == 'from'){
					$(this).attr('style','background-color:#d45500 !important;');
				}else if(stat == 'occ'){
					$(this).attr('style','background-color:#d12027!important;');
				}else{
					$(this).attr('style','background-color:#70AD47!important;');
				}
			});


			$(this).attr('style','background-color:#20877f !important;');
			$('#to-table').val(reason);
			return false;
		});
	<?php elseif($use_js == 'tableCombineJs'): ?>
		$('.reason-btns').click(function(){
			var reason = $(this).attr('ref');
			// $('.reason-btns').attr('style','background-color:#D12027 !important;');
			$('.reason-btns').each(function(){
				var stat = $(this).attr('stat');
				if(stat == 'from'){
					$(this).attr('style','background-color:#d45500 !important;');
				}else if(stat == 'occ'){
					$(this).attr('style','background-color:#d12027!important;');
				}else{
					$(this).attr('style','background-color:#70AD47!important;');
				}
			});


			$(this).attr('style','background-color:#20877f !important;');
			$('#to-table').val(reason);
			return false;
		});	
	<?php elseif($use_js == 'deliveryJs'): ?>
		$('.listings').perfectScrollbar({suppressScrollX: true});
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});

		$('.key-ins').on('keydown', function(e) {
		    if (e.keyCode === 9) {
		        e.preventDefault();
		        // do work

	        	ref = $(this).attr('ref');
				to_focus = Number(ref) + Number(1);

				$('.'+to_focus).focus();
		    }
		});


		// $('#search-customer,.key-ins')
		// 	.keyboard({
		// 		alwaysOpen: true,
		// 		usePreview: false,
		// 		autoAccept : true
		// 	})
		// 	.addNavigation({
		// 		position   : [0,0],
		// 		toggleMode : false,
		// 		focusClass : 'hasFocus'
		// 	});
		$('#search-customer').on('blur',function(){
			var txt = $(this).val();
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
										$.post(baseUrl+'cashier/get_customers/'+cust_id,function(cust){
											$.each(cust,function(id,col){
												$.each(col, function(field,val) {
													$('#'+field).val(val);
												});
											});
										},'json');
									});
						$('<h4/>').html(val.name).appendTo(li);
						$('<h5/>').html(val.phone).appendTo(li);
						li.appendTo(ul);
					});
					$('.listings').perfectScrollbar('update');
				}
				ul.goLoad({load:false});
			},'json');
		});
		$('#continue-btn').click(function(){
			$('#customer-form').rOkay({
				btn_load 	: 	$('#continue-btn'),
				bnt_load_remove : 	false,
				asJson 		: 	true,
				onComplete 	: 	function(data){
					var id = data.id;
					var type = $('#trans_type').val();
					var formData = 'type='+type+'&customer_id='+id;
					$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){

						// window.location = baseUrl+'cashier/counter/'+type;
						formData = 'txt='+type;
		       			$.post(baseUrl+'cashier/encrypt_data',formData,function(data){
		       				// alert(data);
							// window.location = baseUrl+'cashier/counter/'+type;
							window.location = baseUrl+'cashier/counter/'+data;
		       			});

					},'json');
				}
			});
			return false;
		});
		$('#clear-btn').click(function(){
			$('.cust-form').find("input[type=text],input[type=hidden]").val("");
			return false;
		});
	//###############################################################################################
	<?php elseif($use_js == 'retrackJs'): ?>
		$('#search-btn').click(function(){
			var btn = $(this);
			btn.goLoad();
			$.rProgressBar();
			var otbody = $('#orders-tbl tbody');
			var stbody = $('#shifts-tbl tbody');
			otbody.html('');
			stbody.html('');
			var formData = $('#search-form').serialize();
			$.post(baseUrl+'cashier/retrack_load',formData,function(data){
				$.rProgressBarEnd({
					onComplete : function(){
						otbody.html(data.sales_rows);
						stbody.html(data.shift_rows);
						$('.add-shift-order').each(function(){
							$(this).click(function(){
								var shift_id = $(this).attr('ref');
								var user_id = $(this).attr('user');
								var type = $('#type-shift-'+shift_id).val();
								if(type == 'dine-in'){
									bootbox.dialog({
									  message: baseUrl+'cashier/transfer_tables',
									  className: 'manager-call-pop',
									  buttons: {
									    submit: {
									      label: "CONTINUE",
									      className: "btn  pop-manage pop-manage-green",
									      callback: function() {
									        var to_table = $('#to-table').val();
									      	var formData = 'type=dinein&table='+to_table+'&table_name='+to_table+'&guest=1&re_shift_id='+shift_id+'&re_user_id='+user_id;
								      		$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
								      			window.location = baseUrl+'cashier/counter_retrack/dinein';
								      		},'json');
									      }
									    },
									    cancel: {
									      label: "CANCEL",
									      className: "btn pop-manage pop-manage-red",
									      callback: function() {									        
									        btn.goLoad({load:false});
									      }
									    }
									  }
									});
								}
								else{
							      	var formData = 'type='+type+'&guest=1&re_shift_id='+shift_id+'&re_user_id='+user_id;
							      	// alert(formData);
						      		$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
						      			window.location = baseUrl+'cashier/counter_retrack/'+type;
						      			// console.log(data);
						      		},'json');
								}


								return false;
							});
						});
						btn.goLoad({load:false});
					 }
				});
			},'json').fail( function(xhr, textStatus, errorThrown) {
				btn.goLoad({load:false});
		        alert(xhr.responseText);
		    });
			return false;
		});
    <?php elseif($use_js == 'editDatetimeJs'): ?>		
    	 $('#datetime').datetimepicker();
    <?php elseif($use_js == 'counterRetrackJs'): ?>		
		$(document).scannerDetection({
			avgTimeByChar: 40,
			onComplete: function(barcode, qty){ 
				// console.log(barcode);
				// console.log(qty);
				var formData = 'barcode='+barcode;
				// alert(formData);
				scannedRetailItem(formData);
			},
			onError: function(string){}
		});
		$('#counter').disableSelection();
		var scrolled=0;
		var transScroll=0;
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		loadMenuCategories();
		loadTransCart();
		loadTransChargeCart();
		var hashTag = window.location.hash;
		if(hashTag == '#retail'){
			remeload('retail');
		}
		$('#edit-datetime').click(function(){
			bootbox.dialog({
			  message: baseUrl+'cashier/edit_datetime',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "USE",
			      className: "btn  pop-manage pop-manage-green",
			      callback: function() {
			        var formData = 'datetime='+$('#datetime').val();
			        $.post(baseUrl+'cashier/update_datetime_trans',formData,function(data){
			        	$('#trans-datetime-txt').text(data);
			        });
			        return true;
			      }
			    },
			    cancel: {
			      label: "CANCEL",
			      className: "btn pop-manage pop-manage-red",
			      callback: function() {									        
			        return true;
			      }
			    }
			  }
			});  
			return false;
		});
		$('#free-btn').click(function(){
			var id  = $('.selected').attr("ref");
			if (typeof id !== typeof undefined && id !== false) {
				$.callManager({
					success : function(manager){
						var man_user = manager.manager_username;
						var man_id = manager.manager_id;
						var free_reason = $('#pop-reason').val();
						

						$.callFreeReasons({
								submit : function(reason){
									
									var formData = 'free_user_id='+man_id+'&free_reason'+free_reason;

										$('body').goLoad2();
										$.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
											$('#trans-row-'+id+' .cost').text(0);
											var text = $('#trans-row-'+id).find('.name').text();
											// alert(text);
											$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text);
											transTotal();
											rMsg('Updated Menu as free','success');
											$('body').goLoad2({load:false});
										});
									
									// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
									// 	// alert(data);
									// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
									// 		sel.prev().addClass('selected');
									// 		sel.remove();
									// 		if(cart == 'trans_cart' && retail === false){
									// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
									// 				var cat_id = $(".category-btns:first").attr('ref');
									// 				var cat_name = $(".category-btns:first").text();
									// 				var val = {'name':cat_name};
									// 				loadsDiv('menus',cat_id,val,null);
									// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
									// 			});
									// 		}
									// 		$('.counter-center .body').perfectScrollbar('update');
									// 		transTotal();
									// 	},'json');
									// });

								}
							});
						// $('body').goLoad2();
						// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
						// 	$('#trans-row-'+id+' .cost').text(0);
						// 	var text = $('#trans-row-'+id).find('.name').text();
						// 	// alert(text);
						// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text);
						// 	transTotal();
						// 	rMsg('Updated Menu as free','success');
						// 	$('body').goLoad2({load:false});
						// });
					}
				});
			}
			return false;
		});
		$('#submit-btn').click(function(){
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = false;
			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-submit');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						if(data.act == 'add'){
							newTransaction(false,data.type);
							if(btn.attr('id') == 'submit-btn'){
								rMsg('Success! Transaction Submitted.','success');
							}
							else{
								rMsg('Transaction Hold.','warning');
							}
							btn.prop('disabled', false);
						}
						else{
							newTransaction(true,data.type);
						}
					}

					$("#zero-rated-btn").removeClass('counter-btn-green');
					$("#zero-rated-btn").removeClass('zero-rated-active');
					$("#zero-rated-btn").addClass('counter-btn-red');
					$('.center-div .foot .foot-det').css({'background-color':'#fff'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
					
					$('.counter-center .body').perfectScrollbar('update');
					$(".counter-center .body").scrollTop(0);
				},'json');
				// alert(data);
				// console.log(data);
				// });
			}
			return false;
		});
		$('#send-trans-btn').click(function(){
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = false;
			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}
			if($("#trans-server-txt").text() == ""){
				rMsg('Select Food Server','error');
			}
			else{
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						if(data.act == 'add'){
							newTransaction(false,data.type);
							if(btn.attr('id') == 'send-trans-btn'){
								rMsg('Success! Transaction Submitted.','success');
							}
							else{
								rMsg('Transaction Hold.','warning');
							}
							btn.prop('disabled', false);
						}
						else{
							newTransaction(true,data.type);
						}
					}

					$("#zero-rated-btn").removeClass('counter-btn-green');
					$("#zero-rated-btn").removeClass('zero-rated-active');
					$("#zero-rated-btn").addClass('counter-btn-red');
					$('.center-div .foot .foot-det').css({'background-color':'#fff'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
					
					$('.counter-center .body').perfectScrollbar('update');
					$(".counter-center .body").scrollTop(0);
				},'json');
				// alert(data);
				// });
			}		
			return false;	
		});
		$('#print-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i> <br>Billing');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i> <br>Billing');
			}
		});
		$('#print-os-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i><br>ORDER SLIP');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i><br>ORDER SLIP');
			}
		});
		$('#hold-all-btn').click(function(){
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans',function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					if(data.act == 'add'){
						newTransaction(false,data.type);
						if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
					}
					else{
						newTransaction(true,data.type);
					}
				}
			},'json');
			return false;
		});
		$('#settle-btn').click(function(){
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-settle');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
						if(data.error != null){
							rMsg(data.error,'error');
							btn.prop('disabled', false);
						}
						else{
							newTransaction(false);
							if(btn.attr('id') == 'settle-btn'){
								rMsg('Success! Transaction Submitted.','success');
							}
							else{
								rMsg('Transaction Hold.','warning');
							}
							btn.prop('disabled', false);
							window.location = baseUrl+'cashier/settle/'+data.id+'?trans_retake=1';
						}
					},'json');
				// alert(data);
				// });
			}
			return false;
		});
		$('#cash-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-cash');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						newTransaction(false);
						if(btn.attr('id') == 'cash-btn'){
							//rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
						window.location = baseUrl+'cashier/settle/'+data.id+'#cash?trans_retake=1';
					}
				},'json');
			}
			return false;
		});
		$('#credit-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			var go = true;
			if($('#buy2take1').exists()){
				if(!$('#counter').hasClass('on-promo-choose')){
					$('#counter').addClass('on-promo-choose');
					$('#counter').addClass('on-promo-credit');
					$.post(baseUrl+'cashier/buy2take1_qty',function(data){
						$('#counter').attr('promo-qty',data.qty);
						$('#promo-qty').text(data.qty);
						$('#promo-txt').show();
					},'json');
					go = false;
				}
				else{
					$('#counter').removeClass('on-promo-choose');
					go = true;
				}				
			}
			if(go){
				var btn = $(this);
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans/true/settle',function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						newTransaction(false);
						if(btn.attr('id') == 'credit-btn'){
							//rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
						window.location = baseUrl+'cashier/settle/'+data.id+'#credit?trans_retake=1';
					}
				},'json');
			}
			return false;
		});
		$('#waiter-btn').click(function(){
			loadsDiv('waiter',null,null,null);
			loadWaiters();
			return false;
		});
		$('#remove-waiter-btn').click(function(){
			$.post(baseUrl+'cashier/update_trans/waiter_id/null/true',function(data){
				$('#trans-server-txt').text('').hide();
				rMsg('Food Server Removed.','success');
			},'json');
			return false;
		});
		$('#loyalty-btn').click(function(){
			// var sel = $('.selected');
			// if(sel.exists()){
			// 	if(sel.hasClass('loaded')){
			// 		$.callManager({
			// 			success : function(){
			// 				loadsDiv('qty',null,null,null);
			// 			}	
			// 		});		
			// 	}
			// 	else	
			loadsDiv('loyalty',null,null,null);
			// }
			return false;
		});
		///FOR RETAIL
			$('#retail-btn').click(function(){
				if($(this).hasClass('counter-btn-red')){
					$(this).removeClass('counter-btn-red');
					$(this).addClass('counter-btn-green');
					loadItemCategories();
					loadsDiv('retail');
					$('#scan-code').focus();
					$('#go-scan-code').removeClass('counter-btn-orange');
					$('#go-scan-code').addClass('counter-btn-green');
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-red');
					loadMenuCategories();
					var cat_id = $(".category-btns:first").attr('ref');
					var cat_name = $(".category-btns:first").text();
					var val = {'name':cat_name};
					loadsDiv('menus',cat_id,val,null);
					$('#go-scan-code').removeClass('counter-btn-green');
					$('#go-scan-code').addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#go-scan-code').click(function(){
				if($(this).hasClass('counter-btn-orange')){
					$(this).removeClass('counter-btn-orange');
					$(this).addClass('counter-btn-green');
					$('#scan-code').focus();
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#scan-code').on('keyup',function(e){
				if(e.keyCode == 13){
					var code = $(this).val();
					if(code != ""){
						$.post(baseUrl+'cashier/scan_code/'+code,function(data){
							if(data.error == ""){
								var opt = data.item;
								addRetailTransCart(opt.item_id,opt);
								// $.beep();
							}
							else{
								rMsg(data.error,'error');
								// $.beep({'status':'error'});
								
							}
						},'json');
					} 
					else{
						rMsg('Item not found.','error');
						// $.beep({'status':'error'});
					    
					}
					$('#scan-code').val('');
				}
			});
			$('#go-search-item').click(function(){
				var btn = $(this);
				var search = $('#search-item').val();
				if(search != ""){
					var formData = 'search='+search;
					loadRetailItemList(formData,btn);
				}
				else{
					rMsg('Nothing to search.','error');
				}
				return false;
			});
			$('#customer-btn').click(function(){
				var btn = $(this);
				loadsDiv('customers',null,null,null);
				return false;
			});
			$('#remove-customer').click(function(){
				$.post(baseUrl+'cashier/update_trans_customer/',function(data){
					$('#trans-customer').text('').hide();
					rMsg('REMOVED Customer ID','success');
				});
			});
			$('#go-search-customer').click(function(){
				var btn = $(this);
				var search = $('#search-customer').val();
				if(search != ""){
					var formData = 'search='+search;
					loadRetailCustomerList(formData,btn);
				}
				else{
					rMsg('Nothing to search.','error');
				}
				return false;
			});
		$('#qty-btn').click(function(){
			var sel = $('.selected');
			if(sel.exists()){
				if(sel.hasClass('loaded')){
					$.callManager({
						success : function(){
							loadsDiv('qty',null,null,null);
						}	
					});		
				}
				else	
					loadsDiv('qty',null,null,null);
			}
			return false;
		});
		$('#qty-btn-cancel,#qty-btn-done').click(function(){
			if($('#retail-btn').hasClass('counter-btn-red')){
				$('.loads-div').hide();				
				$('.menus-div').show();
			}
			else{
				remeload('retail');
			}
			return false;
		});
		$(".edit-qty-btn").click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var formData = 'value='+btn.attr('value')+'&operator='+btn.attr('operator');
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
				var qty = data.qty;
				$('#trans-row-'+id+' .qty').text(qty);
				btn.prop('disabled', false);

				// var formData = 'disc_id='+opt.promo_id+'&disc_code='+opt.promo_code+'&rate='+opt.disc_rate+'&promo_qty='+opt.promo_qty+'&disc_qty='+opt.disc_qty;
						// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
						// 	transTotal();
						// });

				transTotal();
			},'json');
			return false;
		});
		$("#zero-rated-btn").click(function(){
			var btn = $(this);
			$.callManager({
				addData : 'ZeroRated',
				success : function(manager){
							var man_user = manager.manager_username;
							var man_id = manager.manager_id;
							if(!btn.hasClass('zero-rated-active')){
								$.post(baseUrl+'cashier/update_trans/zero_rated/1',function(data){
									btn.removeClass('counter-btn-red');
									btn.addClass('counter-btn-green');
									btn.addClass('zero-rated-active');
									$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
									$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});
									transTotal();
								});
							}
							else{
								$.post(baseUrl+'cashier/update_trans/zero_rated/0',function(data){
									btn.removeClass('counter-btn-green');
									btn.removeClass('zero-rated-active');
									btn.addClass('counter-btn-red');
									$('.center-div .foot .foot-det').css({'background-color':'#fff'});
									$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
									transTotal();
								});
							}
				}
			});		
			return false;
		});
		$('#add-discount-btn').click(function(){
			loadsDiv('sel-discount',null,null,null);
			loadDiscounts();
			return false;
		});
		$('#add-disc-person-btn').click(function(){
			$('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				var ref = $(this).attr('ref');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

				$.post(baseUrl+'cashier/add_person_disc',formData,function(data){
					$('#add-disc-person-btn').goLoad({load:false});
					if(data.error==""){
						$('.disc-persons-list-div').html(data.code);
						$.each(data.items,function(code,opt){
							$("#disc-person-rem-"+code).click(function(){
								var lin = $(this).parent().parent();
								$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
									lin.remove();
									rMsg('Person Removed.','success');
									transTotal();
								});
								return false;
							});
						});
						transTotal();
					}
					else{
						rMsg(data.error,'error');
					}
				},'json');
			}
			return false;
		})
		$('.disc-btn-row').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#edit-order-guest-no').click(function(){
			$.callEditGuests({
				success : function(guest){
					$('#ord-guest-no').text(guest);	
					$('#disc-guests').val(guest);	
					rMsg('Guest has been updated to'+guest,'success');
				}
			});
			return false;
		});
		$('#prcss-disc').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			console.log(ref);
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				$('#ord-guest-no').text(guests);
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#remove-disc-btn').click(function(){
			var disc_code = $('#disc-disc-code').val();
			$.post(baseUrl+'cashier/del_trans_disc/'+disc_code,function(data){
				rMsg('Discounts Removed','success');
				$('.disc-person').remove();
				$('#disc-form')[0].reset();
				$('#disc-guests').val('');
				transTotal();
			});
			return false;
		});
		$('#remove-btn').click(function(){ alert(1);
			var sel = $('.selected');
			if(sel.exists()){
				if(sel.hasClass('loaded')){
					$.callManager({
						success : function(manager){
							var man_user = manager.manager_username;
							var man_id = manager.manager_id;
							$.callReasons({
								submit : function(reason){
									var id = sel.attr('ref');
									var cart = 'trans_cart';
									var type = 'menu';
									
									if(sel.hasClass('trans-sub-row')){
										cart = 'trans_mod_cart';
										type = 'mod';
									}
									else if(sel.hasClass('trans-charge-row')){
										cart = 'trans_charge_cart';
										type = 'charge';
									}
									var retail = false;
									if(sel.hasClass('retail-item')){
										retail = true;
										type = 'retail';
									}
									
									$.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
										// alert(data);
										$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
											sel.prev().addClass('selected');
											sel.remove();
											if(cart == 'trans_cart' && retail === false){
												$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
													var cat_id = $(".category-btns:first").attr('ref');
													var cat_name = $(".category-btns:first").text();
													var val = {'name':cat_name};
													loadsDiv('menus',cat_id,val,null);
													$('.trans-sub-row[trans-id="'+id+'"]').remove();
												});
											}
											$('.counter-center .body').perfectScrollbar('update');
											transTotal();
										},'json');
									});

								}
							});
						}
					});
				}
				else{
					var id = sel.attr('ref');
					var cart = 'trans_cart';
					if(sel.hasClass('trans-sub-row'))
						cart = 'trans_mod_cart';
					else if(sel.hasClass('trans-charge-row'))
						cart = 'trans_charge_cart';
					var retail = false;
					if(sel.hasClass('retail-item'))
						retail = true;
					if(!sel.hasClass('trans-remarks-row')){
						$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
							sel.prev().addClass('selected');
							sel.remove();
							if(cart == 'trans_cart' && retail === false){
								$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
									var cat_id = $(".category-btns:first").attr('ref');
									var cat_name = $(".category-btns:first").text();
									var val = {'name':cat_name};
									loadsDiv('menus',cat_id,val,null);
									$('.trans-sub-row[trans-id="'+id+'"]').remove();
								});
							}
							$('.counter-center .body').perfectScrollbar('update');
							transTotal();
						},'json');
					}
					else{
						$.post(baseUrl+'cashier/remove_trans_remark/'+id,function(data){
							sel.prev().addClass('selected');
							$('#trans-remarks-row-'+id).remove();
							$('.counter-center .body').perfectScrollbar('update');
						// });
						},'json');
					}
				}
			}
			return false;
		});
		$('#charges-btn').click(function(){
			$('.charges-div .title').text('Select Charges');
			loadCharges();
			loadsDiv('charges',null,null,null);
			return false;
		});
		$('#remarks-btn').click(function(){
			var sel = $('.selected');
			$('#line-remarks').val('');
			if(sel.exists()){
				loadsDiv('remarks',null,null,null);
			}
			return false;
		});
		$('#add-remark-btn').click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");

			var noError = $('#remarks-form').rOkay({
				 				btn_load		: 	$(this),
				 				goSubmit		: 	false,
				 				bnt_load_remove	: 	true
							});
			if(noError){
				var formData = $('#remarks-form').serialize();		
				btn.goLoad();
				$.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
					makeRemarksItemRow(id,data.remarks);
					
					btn.goLoad({load:false});
				},'json');	
			}
			return false;
		});
		$('#tax-exempt-btn').click(function(){
			$.callManager({
				success : function(){
							$.post(baseUrl+'cashier/trans_exempt_to_tax',function(data){
								alert(data);
								transTotal();
								checkWagon('trans_cart');
							// },'json');	
							});	
						  }
			});						  
			return false;
		});
		$('#manager-btn').click(function(){
			window.location = baseUrl+'manager';
			return false;
		});
		$('#logout-btn').click(function(){
			window.location = baseUrl+'site/go_logout';
			return false;
		});
		$('#cancel-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$("#menu-cat-scroll-down").on("click" ,function(){
		    var inHeight = $(".menu-cat-container")[0].scrollHeight;
		    var divHeight = $(".menu-cat-container").height();
		    var trueHeight = inHeight - divHeight;
	        if((scrolled + 150) > trueHeight){
	        	scrolled = trueHeight;
	        }
	        else{
	    	    scrolled=scrolled+150;				    	
	        }
		    // scrolled=scrolled+100;
			$(".menu-cat-container").animate({
			        scrollTop:  scrolled
			},200);
		});
		$("#menu-cat-scroll-up").on("click" ,function(){
			if(scrolled > 0){
				scrolled=scrolled-150;
				$(".menu-cat-container").animate({
				        scrollTop:  scrolled
				},200);
			}
		});
		$(".menu-cat-container").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$(".items-lists").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$('#search-menu').on('keyup',function(){
			alert('aw');
			var search = $(this).val();
			if(search != ""){
				remeload('menu');
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.menus-div').show();
				$('.menus-div .title').text('Search: '+search);
				$('.menus-div .items-lists').html('');
				var formData = 'search='+search;
				$.post(baseUrl+'cashier/get_menus_search_sorted',formData,function(data){
					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		$.each(data,function(key,opt){
			 			
			 			var menu_id = opt.id;
			 			var sCol = $('<div class="col-md-3"></div>');
			 			$('<button/>')
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
			 			.text(opt.name)
			 			.appendTo(sCol)
			 			.click(function(){
			 				// for 0 price rquest
			 				// if(opt.cost  == 0){
			 				// 	$('.loads-div').hide();
			 				// 	$('#menu-id-hidden').val(menu_id);
			 				// 	$('#menu-cat-id-hidden').val(opt.category);
			 				// 	$('#menu-cat-name-hidden').val(opt.category_name);
								// $('.price-div').show();
								// selectModMenu();
			 				// }else{
				 				if(opt.free == 1){
					 				$.callManager({
					 					success : function(){
									 				addTransCart(menu_id,opt);
					 							  }	
					 				});
				 				}
				 				else{
				 					addTransCart(menu_id,opt);
				 				}
				 			// }
			 				return false;
			 			});
			 			sCol.appendTo(div);
			 		});
			 		$('.menus-div .items-lists').after('<div id="scrollers-menu"><div class="row"><div class="col-md-6 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
			 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });

	 				    var inHeight = $(".items-lists")[0].scrollHeight;
	 				    var divHeight = $(".items-lists").height();
	 				    var trueHeight = inHeight - divHeight;
	 			        if((scrolled + 150) > trueHeight){
	 			        	scrolled = trueHeight;
	 			        }
	 			        else{
	 			    	    scrolled=scrolled+150;				    	
	 			        }
	 				    // scrolled=scrolled+100;
	 					$(".items-lists").animate({
	 					        scrollTop:  scrolled
	 					},200);
			 		});
			 		$("#menu-item-scroll-up").on("click" ,function(){
			 			// scrolled=scrolled-100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			if(scrolled > 0){
			 				scrolled=scrolled-150;
			 				$(".items-lists").animate({
			 				        scrollTop:  scrolled
			 				},200);
			 			}
			 		});
			 	},'json');
			}
			return false;
		});
		function loadMenuCategories(){
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_menu_cats',function(data){
		 		showMenuCategories(data,1);
		 	},'json');
		}
		function showMenuCategories(data,ctr){
			$('.category-btns').remove();

			$.each(data,function(key,val){
				var cat_id = val['id'];
				if(ctr == 1){
					var hashTag = window.location.hash;
					// alert(hashTag);
					if(hashTag != '#retail'){
						loadsDiv('menus',cat_id,val,null);
					}
				}
	 			$('<button/>')
	 			.attr({'id':'menu-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
	 			.click(function(){
	 				$('#search-menu').val('');
	 				loadsDiv('menus',cat_id,val,null);
	 				return false;
	 			});
				ctr++;
			});
			if(ctr < 10){
				for (var i = 0; i <= (10-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
		}
		function loadItemCategories(){
		 	$.post(baseUrl+'cashier/get_item_categories',function(data){
		 		showItemCategories(data,1);
		 	},'json');
		}
		function showItemCategories(data,ctr){
			$('.category-btns').remove();
			$.each(data,function(cat_id,val){
				if(ctr == 1){
					loadsDiv('retail');
				}
	 			$('<button/>')
	 			.attr({'id':'item-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
	 			.click(function(){
	 				var formData = 'cat_id='+cat_id+'&cat_name='+val.name;
	 				loadsDiv('retail');
	 				loadRetailItemList(formData,$(this));
	 				return false;
	 			});
				ctr++;
			});
			// alert(ctr);
			if(ctr < 9){
				for (var i = 0; i <= (8-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
		}
		function scannedRetailItem(formData){
			// btn.goLoad();
			$.post(baseUrl+'cashier/get_item_scanned',formData,function(data){
				if(data.error  != ""){
					rMsg(data.error,'error');
				}
				else{
					addRetailTransCart(data.item_id,data.opt);
				}
				// $('.retail-title').text(data.title).show();
				// $('.retail-loads-div').html(data.code);
				// $.each(data.items,function(item_id,opt){
				// 	$('#retail-item-'+item_id).click(function(){
				// 		addRetailTransCart(item_id,opt);
				// 		return false;
				// 	});
				// });
				// $('#search-item').val('');
				// btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function loadRetailItemList(formData,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/get_item_lists',formData,function(data){
				$('.retail-title').text(data.title).show();
				$('.retail-loads-div').html(data.code);
				$.each(data.items,function(item_id,opt){
					$('#retail-item-'+item_id).click(function(){
						addRetailTransCart(item_id,opt);
						return false;
					});
				});
				$('#search-item').val('');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function loadRetailCustomerList(formData,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/get_customers_lists',formData,function(data){
				$('.customers-loads-div').html(data.code);
				$.each(data.items,function(customer_id,opt){
					$('#customer-item-'+customer_id).click(function(){
						$.post(baseUrl+'cashier/update_trans_customer/'+customer_id,function(data){
							$('#trans-customer').text('CUSTOMER ID: '+customer_id).show();
							rMsg('Added Customer ID','success');
						});
						return false;
					});
				});
				$('#search-item').val('');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function remeload(type_load){
			if(type_load == 'retail'){
				$('#retail-btn').removeClass('counter-btn-red');
				$('#retail-btn').addClass('counter-btn-green');
				loadsDiv('retail');
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				loadItemCategories();
				$('#scan-code').focus();
			}
			else{
				$('#retail-btn').removeClass('counter-btn-green');
				$('#retail-btn').addClass('counter-btn-red');
			}
		}
		function loadsDiv(type,id,opt,trans_id,other){
			if(type == 'menus'){
				remeload('menu');
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				$('.menus-div .title').text(opt.name);
				$('.menus-div .items-lists').html('');

				$.post(baseUrl+'cashier/get_menus_sorted/'+id,function(data){
					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		$.each(data,function(key,opt){
			 			
			 			var menu_id = opt.id;
			 			var sCol = $('<div class="col-md-3"></div>');
			 			$('<button/>')
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
			 			.text(opt.name)
			 			.appendTo(sCol)
			 			.click(function(){
			 				var btn = $(this);
			 				btn.goLoad();
			 				if(opt.free == 1){
				 				$.callManager({
				 					success : function(){
								 				addTransCart(menu_id,opt,btn);
				 							  },
				 					fail    : function(){
				 								btn.goLoad({load:false});
				 							  },
				 					cancel  : function(){
				 								btn.goLoad({load:false});
				 							  }		  	
				 				});
			 				}
			 				else{
			 					addTransCart(menu_id,opt,btn);
			 				}
			 				return false;
			 			});
			 			sCol.appendTo(div);
			 			
			 		});
			 		

			 		$('.menus-div .items-lists').after('<div class="scrollers-menu"><div class="row"><div class="col-md-6 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
			 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });

	 				    var inHeight = $(".items-lists")[0].scrollHeight;
	 				    var divHeight = $(".items-lists").height();
	 				    var trueHeight = inHeight - divHeight;
	 			        if((scrolled + 150) > trueHeight){
	 			        	scrolled = trueHeight;
	 			        }
	 			        else{
	 			    	    scrolled=scrolled+150;				    	
	 			        }
	 				    // scrolled=scrolled+100;
	 					$(".items-lists").animate({
	 					        scrollTop:  scrolled
	 					},200);
			 		});
			 		$("#menu-item-scroll-up").on("click" ,function(){
			 			// scrolled=scrolled-100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			if(scrolled > 0){
			 				scrolled=scrolled-150;
			 				$(".items-lists").animate({
			 				        scrollTop:  scrolled
			 				},200);
			 			}
			 		});
			 	},'json');
			}
			else if(type=='mods'){
				remeload('menu');
				$('.mods-div .title').text(opt.name+" Modifiers");
				$('.mods-div .mods-lists').html('');
				var trans_det = opt;

				var formData = 'menu_name='+trans_det.name;
				if(other == "addModDefault"){
					formData += '&add_defaults=1';
				}	
				$.post(baseUrl+'cashier/get_menu_modifiers_wth_dflt/'+id+'/'+trans_id,formData,function(data){
					var modGRP = data.group;
					var dfltGRP = data.dflts;
					if(!$.isEmptyObject(dfltGRP)){
						$.each(dfltGRP,function(trans_mod_id,mopt){
							makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,trans_det,"","default");
						});	
					}	
					if(!$.isEmptyObject(modGRP)){
						$('.loads-div').hide();
						$('.'+type+'-div').show();
						$.each(modGRP,function(mod_group_id,opt){
							var row = $('<div/>').attr({'class':'mod-group','id':'mod-group-'+mod_group_id}).appendTo('.mods-div .mods-lists');
							$('<h4/>').text(opt.name)
									  .addClass('text-center receipt media-mod-title')
									  .css({'margin-bottom':'5px'})
									  .appendTo('#mod-group-'+mod_group_id);
							var mandatory = opt.mandatory;
							var multiple = opt.multiple;

							var div = $('#mod-group-'+mod_group_id);
							var divRow = $('<div/>').attr({'class':'row'});
							// var div = $('#mod-group-'+mod_group_id).append('<div class="row"></div>');
							$.each(opt.details,function(mod_id,det){
								var sCol = $('<div class="col-md-4"></div>');
								$('<button/>')
								.attr({'id':'mod-'+mod_id,'ref':mod_id,'class':'counter-btn-silver btn btn-block btn-default'})
								// .css({'margin':'5px','width':'130px'})
								.text(det.name)
								.appendTo(sCol)
								.click(function(){
									addTransModCart(trans_id,mod_group_id,mod_id,det,id,$(this),trans_det,mandatory,multiple);
									return false;
								});
				 				sCol.appendTo(divRow);
				 			});
				 			div.append(divRow);
				 			$('<hr/>').appendTo('#mod-group-'+mod_group_id);
				 		});
						$('.mods-div .mods-lists').after('<div id="scrollers-mods"><div class="row"><div class="col-md-6 text-left"><button id="mods-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="mods-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
				 		$("#mods-item-scroll-down").on("click" ,function(){
				 		 //    scrolled=scrolled+100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });

		 				    var inHeight = $(".mods-lists")[0].scrollHeight;
		 				    var divHeight = $(".mods-lists").height();
		 				    var trueHeight = inHeight - divHeight;
		 			        if((scrolled + 150) > trueHeight){
		 			        	scrolled = trueHeight;
		 			        }
		 			        else{
		 			    	    scrolled=scrolled+150;				    	
		 			        }
		 				    // scrolled=scrolled+100;
		 					$(".mods-lists").animate({
		 					        scrollTop:  scrolled
		 					},200);
				 		});
				 		$("#mods-item-scroll-up").on("click" ,function(){
				 			// scrolled=scrolled-100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });
				 			if(scrolled > 0){
				 				scrolled=scrolled-150;
				 				$(".mods-lists").animate({
				 				        scrollTop:  scrolled
				 				},200);
				 			}
				 		});
					}
			 	},'json');
			}
			else if(type=='qty'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='remarks'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='charges'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
			else if(type=='sel-discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else{
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
		}
		var promo_ctr = 0;
		function addTransCart(menu_id,opt,btn){
			var cost = opt.cost;
			var goOn = false;
			if($('#buy2take1').exists()){				
				if($('#counter').hasClass('on-promo-choose')){
					var max_qty = parseFloat($('#counter').attr('promo-qty')); 
					if(promo_ctr < max_qty){
						cost = 0;
						promo_ctr++;
						if(promo_ctr == max_qty){
							goOn = true;
						}
					}
				}
			}			
			var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+cost+'&no_tax='+opt.no_tax+'&qty=1';
			var submit = $('#submit-btn');
			var settle = $('#settle-btn');
			var cash = $('#cash-btn');
			var credit = $('#credit-btn');
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,menu_id,data.items);
				loadsDiv('mods',menu_id,data.items,data.id,"addModDefault");
				transTotal();
				if(goOn){
					$('body').goLoad2({loadTxt:'Loading...'});
					if($('#counter').hasClass('on-promo-submit')){
						submit.trigger('click');
						$('body').goLoad2({load:false});
						$('#promo-txt').hide();
						$('#counter').removeClass('on-promo-choose');
						$('#counter').removeClass('on-promo-submit');
						$('#counter').removeAttr('promo-qty');
						promo_ctr = 0;
					}
					else if($('#counter').hasClass('on-promo-settle')){
						settle.trigger('click');
					}
					else if($('#counter').hasClass('on-promo-cash')){
						cash.trigger('click');
					}
					else if($('#counter').hasClass('on-promo-credit')){
						credit.trigger('click');
					}
				}
				btn.goLoad({load:false});
			},'json');
			
		}
		function addRetailTransCart(item_id,opt){
			var formData = 'menu_id='+item_id+'&name='+opt.name+'&cost='+opt.cost+'&no_tax=0&qty=1&retail=1';
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,item_id,data.items);
				// loadsDiv('mods',menu_id,data.items,data.id);
				transTotal();
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				$('#scan-code').focus();
			},'json');
		}
		function addTransModCart(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det,mandatory,multiple){
			var formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det.name
							+'&mandatory='+mandatory
							+'&multiple='+multiple
							+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			// console.log(formData);
			if(btn != null){
				btn.prop('disabled', true);				
			}
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det)
				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// });
		}
		function makeItemRow(id,menu_id,opt,loaded){
			$('.sel-row').removeClass('selected');
			var retail = "";
			if (opt.hasOwnProperty('retail')) {
				retail = 'retail-item';
			}

			$('<li/>').attr({'id':'trans-row-'+id,'trans-id':id,'ref':id,'class':'sel-row trans-row selected '+retail+' '+loaded})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					if (!opt.hasOwnProperty('retail')) {
						loadsDiv('mods',menu_id,opt,id);
					}
					else{
						remeload('retail');
					}
					return false;
				});
			$('<span/>').attr('class','qty').text(opt.qty).css('margin-left','10px').appendTo('#trans-row-'+id);
			var namer = opt.name;
			if (opt.hasOwnProperty('retail')) {
				namer = '<i class="fa fa-shopping-cart"></i> '+opt.name;
			}
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					namer = ' <i class="fa fa-print"></i> '+namer;
			}
			console.log(opt);
			if (opt.hasOwnProperty('free_user_id')) {	
				if(opt.free_user_id != "")
					namer = ' <i class="fa fa-asterisk"></i> '+namer;
			}
			$('<span/>').attr('class','name').html(namer).appendTo('#trans-row-'+id);
			$('<span/>').attr('class','cost').text(opt.cost).css('margin-right','10px').appendTo('#trans-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubRow(id,trans_id,mod_id,opt,trans_det,loaded,dflt){
			var subRow = $('<li/>').attr({'id':'trans-sub-row-'+id,'trans-id':trans_id,'trans-mod-id':id,'ref':id,'class':'trans-sub-row sel-row '+loaded})
								   .click(function(){
										selector($(this));
										loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});
			var mod_name = opt.name;					   
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					mod_name = ' <i class="fa fa-print"></i> '+mod_name;
			}

			$('<span/>').attr('class','name').css('margin-left','28px').html(mod_name).appendTo(subRow);
			if(parseFloat(opt.cost) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.cost).appendTo(subRow);

			if(dflt == "default"){
				$('#trans-row-'+trans_id).after(subRow);
			}
			else{
				$('.selected').after(subRow);
			}

			$('.sel-row').removeClass('selected');
			selector($('#trans-sub-row-'+id));
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function selector(li){
			$('.sel-row').removeClass('selected');
			li.addClass('selected');
		}
		function selectModMenu(){
			var sel = $('.selected');
			if(sel.hasClass('trans-sub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
		}
		function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;
				var local_tax = data.local_tax;
				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
				if($("#local-tax-txt").exists()){
					$("#local-tax-txt").number(local_tax,2);
				}
				
				if(data.zero_rated > 0){
					$("#zero-rated-btn").removeClass('counter-btn-red');
					$("#zero-rated-btn").addClass('counter-btn-green');
					$("#zero-rated-btn").addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
				}
			},'json');
			// 	alert(data);
			// });
		}
		function loadTransCart(){
			$.post(baseUrl+'cashier/get_trans_cart/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					console.log(data);
					$.each(data,function(trans_id,opt){
						makeItemRow(trans_id,opt.menu_id,opt,'loaded');
						if(opt.remarks != "" && opt.remarks != null){
							makeRemarksItemRow(trans_id,opt.remarks);
						}
						var modifiers = opt.modifiers;
						if(!$.isEmptyObject(modifiers)){
							$.each(modifiers,function(trans_mod_id,mopt){
								makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,mopt,'loaded');
							});
						}
						if(ctr == len)
							$('.selected').trigger('click');
						ctr++;
					});
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
		}
		function loadTransChargeCart(){
			$.post(baseUrl+'cashier/get_trans_charges/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					$.each(data,function(charge_id,opt){
						makeChargeItemRow(charge_id,opt);
					});
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
		}
		function newTransaction(redirect,type){
			$.post(baseUrl+'cashier/new_trans/true/'+type,function(data){
				if(!redirect){
					$('#trans-datetime').text(data.datetime);
					var tp = data.type;
					$('#trans-header').text(tp.toUpperCase());

					$('.trans-lists').find('li').remove();
					var cat_id = $(".category-btns:first").attr('ref');
					var cat_name = $(".category-btns:first").text();
					var val = {'name':cat_name};
					loadsDiv('menus',cat_id,val,null);
					transTotal();
					$('.addon-texts').text('').hide();
					if(type == 'retail')
						remeload('retail');
					if(type=='dinein')
						window.location = baseUrl+'cashier/tables';
					else if(type=='delivery')
						window.location = baseUrl+'cashier/delivery';
					else if(type=='pickup')
						window.location = baseUrl+'cashier/pickup';
				}
				else{
					if(type=='dinein')
						window.location = baseUrl+'cashier/tables';
					else if(type=='delivery')
						window.location = baseUrl+'cashier/delivery';
					else if(type=='pickup')
						window.location = baseUrl+'cashier/pickup';
					else{
						window.location = baseUrl+'cashier/counter/'+data.type;
					}
				}
			},'json');
		}
		function loadDefault(){
			var cat_id = $(".category-btns:first").attr('ref');
			var cat_name = $(".category-btns:first").text();
			var val = {'name':cat_name};
			loadsDiv('menus',cat_id,val,null);
		}
		function loadDiscounts(){
			$.post(baseUrl+'cashier/get_discounts',function(data){
				$('.select-discounts-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#item-disc-btn-'+id).click(function(){
						var idisc = $(this);
						if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$('#prcss-disc').attr('ref','equal');
						}
						else{
							$('#prcss-disc').attr('ref','all');
						}

						if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$.callManager({
								addData : 'Discount',
			 					success : function(){
									loadsDiv('discount',null,null,null);
									$('.discount-div .title').text(idisc.text());
									$('.discount-div #rate-txt').number(opt.disc_rate,2);
									$('#disc-disc-id').val(opt.disc_id);
									$('#disc-disc-rate').val(opt.disc_rate);
									$('#disc-disc-code').val(opt.disc_code);
									$('#disc-no-tax').val(opt.no_tax);
									$('#disc-fix').val(opt.fix);
									$('#disc-guests').val(opt.guest);
									$.post(baseUrl+'cashier/load_disc_persons/'+opt.disc_code,function(data){
										$('.disc-persons-list-div').html(data.code);
										$.each(data.items,function(code,opt){
											$("#disc-person-rem-"+code).click(function(){
												var lin = $(this).parent().parent();
												$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
													lin.remove();
													rMsg('Person Removed.','success');
													transTotal();
												});
												return false;
											});
										});
									},'json');
									// if (typeof opt.name != 'undefined') {
									// 	$('#disc-cust-name').val(opt.name);
									// 	$('#disc-cust-guest').val(opt.guest);
									// 	$('#disc-guests').val(opt.guest);
									// 	$('#disc-cust-code').val(opt.code);
									// 	$('#disc-cust-bday').val(opt.bday);
									// }
			 					}	
		 					});
						}else{
							loadsDiv('discount',null,null,null);
									$('.discount-div .title').text(idisc.text());
									$('.discount-div #rate-txt').number(opt.disc_rate,2);
									$('#disc-disc-id').val(opt.disc_id);
									$('#disc-disc-rate').val(opt.disc_rate);
									$('#disc-disc-code').val(opt.disc_code);
									$('#disc-no-tax').val(opt.no_tax);
									$('#disc-fix').val(opt.fix);
									$('#disc-guests').val(opt.guest);
									$.post(baseUrl+'cashier/load_disc_persons/'+opt.disc_code,function(data){
										$('.disc-persons-list-div').html(data.code);
										$.each(data.items,function(code,opt){
											$("#disc-person-rem-"+code).click(function(){
												var lin = $(this).parent().parent();
												$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
													lin.remove();
													rMsg('Person Removed.','success');
													transTotal();
												});
												return false;
											});
										});
									},'json');
						}

						// $.callManager({
		 			// 		success : function(){
						// 		loadsDiv('discount',null,null,null);
						// 		$('.discount-div .title').text(idisc.text());
						// 		$('.discount-div #rate-txt').number(opt.disc_rate,2);
						// 		$('#disc-disc-id').val(opt.disc_id);
						// 		$('#disc-disc-rate').val(opt.disc_rate);
						// 		$('#disc-disc-code').val(opt.disc_code);
						// 		$('#disc-no-tax').val(opt.no_tax);
						// 		$('#disc-fix').val(opt.fix);
						// 		$('#disc-guests').val(opt.guest);
						// 		$.post(baseUrl+'cashier/load_disc_persons/'+opt.disc_code,function(data){
						// 			$('.disc-persons-list-div').html(data.code);
						// 			$.each(data.items,function(code,opt){
						// 				$("#disc-person-rem-"+code).click(function(){
						// 					var lin = $(this).parent().parent();
						// 					$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
						// 						lin.remove();
						// 						rMsg('Person Removed.','success');
						// 						transTotal();
						// 					});
						// 					return false;
						// 				});
						// 			});
						// 		},'json');
						// 		// if (typeof opt.name != 'undefined') {
						// 		// 	$('#disc-cust-name').val(opt.name);
						// 		// 	$('#disc-cust-guest').val(opt.guest);
						// 		// 	$('#disc-guests').val(opt.guest);
						// 		// 	$('#disc-cust-code').val(opt.code);
						// 		// 	$('#disc-cust-bday').val(opt.bday);
						// 		// }
		 			// 		}	
		 			// 	});
						return false;
					});
				});
			},'json');
		}
		function loadCharges(){
			$.post(baseUrl+'cashier/get_charges',function(data){
				$('.charges-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#charges-btn-'+id).click(function(){
						addChargeCart(id,opt);
						return false;
					});
				});
			},'json');
		}
		function loadWaiters(){
			$.post(baseUrl+'cashier/get_waiters',function(data){
				$('.waiters-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#waiters-btn-'+id).click(function(){
						$.callFS({
							success : function(emp){
										if(id == emp['emp_id']){
											$.post(baseUrl+'cashier/update_trans/waiter_id/'+id,function(data){
												$('#trans-server-txt').text('FS: '+opt.uname).show();
												rMsg(opt.full_name+' added as Food Server','success');
											},'json');
										}
										else{
											rMsg('Wrong Pin.','error');
										}
									  }
						});
						return false;
					});
				});
			},'json');
		}
		function addChargeCart(id,row){
			var formData = 'name='+row.charge_name+'&code='+row.charge_name+'&amount='+row.charge_amount+'&absolute='+row.absolute;
			$.post(baseUrl+'wagon/add_to_wagon/trans_charge_cart/'+id,formData,function(data){
				if(data.error == null){
					makeChargeItemRow(data.id,data.items);
					// loadsDiv('mods',menu_id,data.items,data.id);
					transTotal();
				}
				else{
					rMsg(data.error,'error');
				}
			},'json');
			// });
		}
		function makeChargeItemRow(id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-charge-row-'+id,'charge-id':id,'ref':id,'class':'sel-row trans-charge-row selected'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					loadsDiv('charges');
					return false;
				});
			$('<span/>').attr('class','qty').html('<i class="fa fa-tag"></i>').css('margin-left','10px').appendTo('#trans-charge-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-charge-row-'+id);
			var tx = opt.amount;
			if(opt.absolute == 0){
				tx = opt.amount+'%';
			}
			$('<span/>').attr('class','cost').text(tx).css('margin-right','10px').appendTo('#trans-charge-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeRemarksItemRow(id,remarks){
			$('.sel-row').removeClass('selected');
			if($('#trans-remarks-row-'+id).exists()){
				$('#trans-remarks-row-'+id).remove();
			}
			$('<li/>').attr({'id':'trans-remarks-row-'+id,'ref':id,'class':'sel-row trans-remarks-row selected'})
				.insertAfter('.trans-lists li#trans-row-'+id)
				.click(function(){
					selector($(this));
					loadsDiv('remarks');
					$('#line-remarks').val(remarks);
					return false;
				});
			// $('<span/>').attr('class','qty').html('').css('margin-left','10px').appendTo('#trans-remarks-row-'+id);
			$('<span/>').attr('class','name').css('margin-left','26px').html('<i class="fa fa-text-width"></i> '+remarks).appendTo('#trans-remarks-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function checkWagon(name){
			$.post(baseUrl+'wagon/get_wagon/'+name,function(data){
				alert(data);
			});
		}
		$('#disc-cust-name,#disc-cust-code,#disc-cust-bday,#disc-cust-guest,#line-remarks,#search-item')
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
	//###############################################################################################
	//###############################################################################################
	<?php elseif($use_js == 'counterJs2'): ?>
		// $(':button').click(function(){
		// 	$.beep();
		// 	// alert('here');
		// });
		var scrolled=0;
		var transScroll=0;
		$('.counter-center .body').perfectScrollbar({suppressScrollX: true});
		loadMenuCategories();
		loadTransCart();
		loadTransChargeCart();
		var hashTag = window.location.hash;
		if(hashTag == '#retail'){
			remeload('retail');
		}
		$('#submit-btn').click(function(){
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = false;
			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}
			
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans2/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					if(data.act == 'add'){
						newTransaction(false,data.type);
						if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
					}
					else{
						newTransaction(true,data.type);
					}
				}

				$("#zero-rated-btn").removeClass('counter-btn-green');
				$("#zero-rated-btn").removeClass('zero-rated-active');
				$("#zero-rated-btn").addClass('counter-btn-red');
				$('.center-div .foot .foot-det').css({'background-color':'#fff'});
				$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
				
				$('.counter-center .body').perfectScrollbar('update');
				$(".counter-center .body").scrollTop(0);
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#send-trans-btn').click(function(){
			var btn = $(this);
			var print = $('#print-btn').attr('doprint');
			var printOS = $('#print-os-btn').attr('doprint');
			var doPrintOS = false;
			if (typeof printOS !== typeof undefined && printOS !== false) {
				doPrintOS = printOS;
			}
			if($("#trans-server-txt").text() == ""){
				rMsg('Select Food Server','error');
			}
			else{
				btn.prop('disabled', true);
				$.post(baseUrl+'cashier/submit_trans2/true/null/false/0/null/null/'+print+'/null/'+doPrintOS,function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						if(data.act == 'add'){
							newTransaction(false,data.type);
							if(btn.attr('id') == 'send-trans-btn'){
								rMsg('Success! Transaction Submitted.','success');
							}
							else{
								rMsg('Transaction Hold.','warning');
							}
							btn.prop('disabled', false);
						}
						else{
							newTransaction(true,data.type);
						}
					}

					$("#zero-rated-btn").removeClass('counter-btn-green');
					$("#zero-rated-btn").removeClass('zero-rated-active');
					$("#zero-rated-btn").addClass('counter-btn-red');
					$('.center-div .foot .foot-det').css({'background-color':'#fff'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
					
					$('.counter-center .body').perfectScrollbar('update');
					$(".counter-center .body").scrollTop(0);
				},'json');
				// alert(data);
				// });
			}		
			return false;	
		});
		$('#print-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i> <br>Billing');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i> <br>Billing');
			}
		});
		$('#print-os-btn').click(function(event){
			var current =  $(this).attr('doprint');
			if (current == 'true'){
				$(this).attr('doprint','false');
				$(this).html('<i class="fa fa-fw fa-ban fa-lg"></i><br>ORDER SLIP');
			} else {
				$(this).attr('doprint','true');
				$(this).html('<i class="fa fa-fw fa-print fa-lg"></i><br>ORDER SLIP');
			}
		});
		$('#hold-all-btn').click(function(){
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans2',function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					if(data.act == 'add'){
						newTransaction(false,data.type);
						if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
					}
					else{
						newTransaction(true,data.type);
					}
				}
			},'json');
			return false;
		});
		$('#settle-btn').click(function(){
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans2/true/settle',function(data){
					if(data.error != null){
						rMsg(data.error,'error');
						btn.prop('disabled', false);
					}
					else{
						newTransaction(false);
						if(btn.attr('id') == 'settle-btn'){
							rMsg('Success! Transaction Submitted.','success');
						}
						else{
							rMsg('Transaction Hold.','warning');
						}
						btn.prop('disabled', false);
						window.location = baseUrl+'cashier/settle/'+data.id;
					}
				},'json');
			// alert(data);
			// });
			return false;
		});
		$('#cash-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			btn.prop('disabled', true);
			var formData = 'must_ref='+$('#must_ref').val()+'&must_datetime='+$('#must_trans_date').val();
			$.post(baseUrl+'cashier/submit_trans2/true/settle',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					window.location.reload();
					// newTransaction(false);
					// if(btn.attr('id') == 'cash-btn'){
					// 	//rMsg('Success! Transaction Submitted.','success');
					// }
					// else{
					// 	rMsg('Transaction Hold.','warning');
					// }
					// btn.prop('disabled', false);
					// window.location = baseUrl+'cashier/settle/'+data.id+'#cash';
				}
			},'json');
			return false;
		});
		$('#credit-btn').click(function(){
			//alert('aw');
			var btn = $(this);
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/submit_trans2/true/settle',function(data){
				if(data.error != null){
					rMsg(data.error,'error');
					btn.prop('disabled', false);
				}
				else{
					newTransaction(false);
					if(btn.attr('id') == 'credit-btn'){
						//rMsg('Success! Transaction Submitted.','success');
					}
					else{
						rMsg('Transaction Hold.','warning');
					}
					btn.prop('disabled', false);
					window.location = baseUrl+'cashier/settle/'+data.id+'#credit';
				}
			},'json');
			return false;
		});
		$('#waiter-btn').click(function(){
			loadsDiv('waiter',null,null,null);
			loadWaiters();
			return false;
		});
		$('#remove-waiter-btn').click(function(){
			$.post(baseUrl+'cashier/update_trans/waiter_id/null/true',function(data){
				$('#trans-server-txt').text('').hide();
				rMsg('Food Server Removed.','success');
			},'json');
			return false;
		});
		///FOR RETAIL
			$('#retail-btn').click(function(){
				if($(this).hasClass('counter-btn-red')){
					$(this).removeClass('counter-btn-red');
					$(this).addClass('counter-btn-green');
					loadItemCategories();
					loadsDiv('retail');
					$('#scan-code').focus();
					$('#go-scan-code').removeClass('counter-btn-orange');
					$('#go-scan-code').addClass('counter-btn-green');
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-red');
					loadMenuCategories();
					var cat_id = $(".category-btns:first").attr('ref');
					var cat_name = $(".category-btns:first").text();
					var val = {'name':cat_name};
					loadsDiv('menus',cat_id,val,null);
					$('#go-scan-code').removeClass('counter-btn-green');
					$('#go-scan-code').addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#go-scan-code').click(function(){
				if($(this).hasClass('counter-btn-orange')){
					$(this).removeClass('counter-btn-orange');
					$(this).addClass('counter-btn-green');
					$('#scan-code').focus();
				}
				else{
					$(this).removeClass('counter-btn-green');
					$(this).addClass('counter-btn-orange');
					$('#scan-code').blur();
				}
				return false;
			});
			$('#scan-code').on('keyup',function(e){
				if(e.keyCode == 13){
					var code = $(this).val();
					if(code != ""){
						$.post(baseUrl+'cashier/scan_code/'+code,function(data){
							if(data.error == ""){
								var opt = data.item;
								addRetailTransCart(opt.item_id,opt);
								// $.beep();
							}
							else{
								rMsg(data.error,'error');
								// $.beep({'status':'error'});
								
							}
						},'json');
					} 
					else{
						rMsg('Item not found.','error');
						// $.beep({'status':'error'});
					    
					}
					$('#scan-code').val('');
				}
			});
			$('#go-search-item').click(function(){
				var btn = $(this);
				var search = $('#search-item').val();
				if(search != ""){
					var formData = 'search='+search;
					loadRetailItemList(formData,btn);
				}
				else{
					rMsg('Nothing to search.','error');
				}
				return false;
			});
		$('#qty-btn').click(function(){
			var sel = $('.selected');
			if(sel.exists()){
				if(sel.hasClass('loaded')){
					$.callManager({
						success : function(){
							loadsDiv('qty',null,null,null);
						}	
					});		
				}
				else	
					loadsDiv('qty',null,null,null);
			}
			return false;
		});
		$('#qty-btn-cancel,#qty-btn-done').click(function(){
			if($('#retail-btn').hasClass('counter-btn-red')){
				$('.loads-div').hide();				
				$('.menus-div').show();
			}
			else{
				remeload('retail');
			}
			return false;
		});
		$(".edit-qty-btn").click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");
			var formData = 'value='+btn.attr('value')+'&operator='+btn.attr('operator');
			btn.prop('disabled', true);
			$.post(baseUrl+'cashier/update_trans_qty/'+id,formData,function(data){
				var qty = data.qty;
				$('#trans-row-'+id+' .qty').text(qty);
				btn.prop('disabled', false);

				// $.post(baseUrl+'cashier/discount_item_qty/'+id,function(data){
				// 								transTotal();
				// 							});

				transTotal();
			},'json');
			return false;
		});
		$("#zero-rated-btn").click(function(){
			var btn = $(this);
			if(!btn.hasClass('zero-rated-active')){
				$.post(baseUrl+'cashier/update_trans/zero_rated/1',function(data){
					btn.removeClass('counter-btn-red');
					btn.addClass('counter-btn-green');
					btn.addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});
					transTotal();
				});
			}
			else{
				$.post(baseUrl+'cashier/update_trans/zero_rated/0',function(data){
					btn.removeClass('counter-btn-green');
					btn.removeClass('zero-rated-active');
					btn.addClass('counter-btn-red');
					$('.center-div .foot .foot-det').css({'background-color':'#fff'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#000'});
					transTotal();
				});
			}
			return false;
		});
		$('#add-discount-btn').click(function(){
			loadsDiv('sel-discount',null,null,null);
			loadDiscounts();
			return false;
		});
		$('#add-disc-person-btn').click(function(){
			$('#add-disc-person-btn').goLoad();
			var noError = $('#disc-form').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var guests = $('#disc-guests').val();
				var ref = $(this).attr('ref');
				var formData = $('#disc-form').serialize();
				formData = formData+'&type='+ref+'&guests='+guests;

				$.post(baseUrl+'cashier/add_person_disc',formData,function(data){
					$('#add-disc-person-btn').goLoad({load:false});
					if(data.error==""){
						$('.disc-persons-list-div').html(data.code);
						$.each(data.items,function(code,opt){
							$("#disc-person-"+code).click(function(){
								var lin = $(this);
								$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
									lin.remove();
									rMsg('Person Removed.','success');
									transTotal();
								});
								return false;
							});
						});
						transTotal();
					}
					else{
						rMsg(data.error,'error');
					}
				},'json');
			}
			return false;
		})
		$('.disc-btn-row').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#edit-order-guest-no').click(function(){
			$.callEditGuests({
				success : function(guest){
					$('#ord-guest-no').text(guest);	
					$('#disc-guests').val(guest);	
					rMsg('Guest has been updated to'+guest,'success');
				}
			});
			return false;
		});
		$('#prcss-disc').click(function(){
			var guests = $('#disc-guests').val();
			var ref = $(this).attr('ref');
			console.log(ref);
			var formData = $('#disc-form').serialize();
			formData = formData+'&type='+ref+'&guests='+guests;
			$('.disc-btn-row').goLoad2();
			$.post(baseUrl+'cashier/add_trans_disc',formData,function(data){
				$('.disc-btn-row').goLoad2({load:false});
				$('#ord-guest-no').text(guests);
				if(data.error != ""){
					rMsg(data.error,'error');
				}
				else{
					rMsg('Added Discount.','success');
					transTotal();
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$('#remove-disc-btn').click(function(){
			var disc_code = $('#disc-disc-code').val();
			$.post(baseUrl+'cashier/del_trans_disc/'+disc_code,function(data){
				rMsg('Discounts Removed','success');
				$('.disc-person').remove();
				$('#disc-form')[0].reset();
				$('#disc-guests').val('');
				transTotal();
			});
			return false;
		});
		$('#remove-btn').click(function(){ alert(2);
			var sel = $('.selected');
			if(sel.exists()){
				if(sel.hasClass('loaded')){
					$.callManager({
						success : function(manager){
							var man_user = manager.manager_username;
							var man_id = manager.manager_id;
							$.callReasons({
								submit : function(reason){
									var id = sel.attr('ref');
									var cart = 'trans_cart';
									var type = 'menu';
									
									if(sel.hasClass('trans-sub-row')){
										cart = 'trans_mod_cart';
										type = 'mod';
									}
									else if(sel.hasClass('trans-charge-row')){
										cart = 'trans_charge_cart';
										type = 'charge';
									}
									var retail = false;
									if(sel.hasClass('retail-item')){
										retail = true;
										type = 'retail';
									}
									
									$.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
										// alert(data);
										$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
											sel.prev().addClass('selected');
											sel.remove();
											if(cart == 'trans_cart' && retail === false){
												$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
													var cat_id = $(".category-btns:first").attr('ref');
													var cat_name = $(".category-btns:first").text();
													var val = {'name':cat_name};
													loadsDiv('menus',cat_id,val,null);
													$('.trans-sub-row[trans-id="'+id+'"]').remove();
												});
											}
											$('.counter-center .body').perfectScrollbar('update');
											transTotal();
										},'json');
									});

								}
							});
						}
					});
				}
				else{
					var id = sel.attr('ref');
					var cart = 'trans_cart';
					if(sel.hasClass('trans-sub-row'))
						cart = 'trans_mod_cart';
					else if(sel.hasClass('trans-charge-row'))
						cart = 'trans_charge_cart';
					var retail = false;
					if(sel.hasClass('retail-item'))
						retail = true;
					if(!sel.hasClass('trans-remarks-row')){
						$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
							sel.prev().addClass('selected');
							sel.remove();
							if(cart == 'trans_cart' && retail === false){
								$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
									var cat_id = $(".category-btns:first").attr('ref');
									var cat_name = $(".category-btns:first").text();
									var val = {'name':cat_name};
									loadsDiv('menus',cat_id,val,null);
									$('.trans-sub-row[trans-id="'+id+'"]').remove();
								});
							}
							$('.counter-center .body').perfectScrollbar('update');
							transTotal();
						},'json');
					}
					else{
						$.post(baseUrl+'cashier/remove_trans_remark/'+id,function(data){
							sel.prev().addClass('selected');
							$('#trans-remarks-row-'+id).remove();
							$('.counter-center .body').perfectScrollbar('update');
						// });
						},'json');
					}
				}
			}
			return false;
		});
		$('#charges-btn').click(function(){
			$('.charges-div .title').text('Select Charges');
			loadCharges();
			loadsDiv('charges',null,null,null);
			return false;
		});
		$('#remarks-btn').click(function(){
			var sel = $('.selected');
			$('#line-remarks').val('');
			if(sel.exists()){
				loadsDiv('remarks',null,null,null);
			}
			return false;
		});
		$('#add-remark-btn').click(function(){
			var sel = $('.selected');
			var btn = $(this);
			var id = sel.attr("ref");

			var noError = $('#remarks-form').rOkay({
				 				btn_load		: 	$(this),
				 				goSubmit		: 	false,
				 				bnt_load_remove	: 	true
							});
			if(noError){
				var formData = $('#remarks-form').serialize();		
				btn.goLoad();
				$.post(baseUrl+'cashier/add_trans_remark/'+id,formData,function(data){
					makeRemarksItemRow(id,data.remarks);
					
					btn.goLoad({load:false});
				},'json');	
			}
			return false;
		});
		$('#tax-exempt-btn').click(function(){
			$.callManager({
				success : function(){
							$.post(baseUrl+'cashier/trans_exempt_to_tax',function(data){
								alert(data);
								transTotal();
								checkWagon('trans_cart');
							// },'json');	
							});	
						  }
			});						  
			return false;
		});
		$('#manager-btn').click(function(){
			window.location = baseUrl+'manager';
			return false;
		});
		$('#logout-btn').click(function(){
			window.location = baseUrl+'site/go_logout';
			return false;
		});
		$('#cancel-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$("#menu-cat-scroll-down").on("click" ,function(){
		    var inHeight = $(".menu-cat-container")[0].scrollHeight;
		    var divHeight = $(".menu-cat-container").height();
		    var trueHeight = inHeight - divHeight;
	        if((scrolled + 150) > trueHeight){
	        	scrolled = trueHeight;
	        }
	        else{
	    	    scrolled=scrolled+150;				    	
	        }
		    // scrolled=scrolled+100;
			$(".menu-cat-container").animate({
			        scrollTop:  scrolled
			},200);
		});
		$("#menu-cat-scroll-up").on("click" ,function(){
			if(scrolled > 0){
				scrolled=scrolled-150;
				$(".menu-cat-container").animate({
				        scrollTop:  scrolled
				},200);
			}
		});
		$(".menu-cat-container").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$(".items-lists").bind("mousewheel",function(ev, delta) {
		    var scrollTop = $(this).scrollTop();
		    $(this).scrollTop(scrollTop-Math.round(delta));
		});
		$('#search-menu').on('keyup',function(){
			var search = $(this).val();
			if(search != ""){
				remeload('menu');
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.menus-div').show();
				$('.menus-div .title').text('Search: '+search);
				$('.menus-div .items-lists').html('');
				var formData = 'search='+search;
				$.post(baseUrl+'cashier/get_menus_search_sorted',formData,function(data){
					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		$.each(data,function(key,opt){
			 			
			 			var menu_id = opt.id;
			 			var sCol = $('<div class="col-md-3"></div>');
			 			$('<button/>')
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
			 			.text(opt.name)
			 			.appendTo(sCol)
			 			.click(function(){
			 				if(opt.free == 1){
				 				$.callManager({
				 					success : function(){
								 				addTransCart(menu_id,opt);
				 							  }	
				 				});
			 				}
			 				else{
			 					addTransCart(menu_id,opt);
			 				}
			 				return false;
			 			});
			 			sCol.appendTo(div);
			 		});
			 		$('.menus-div .items-lists').after('<div id="scrollers-menu"><div class="row"><div class="col-md-6 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
			 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });

	 				    var inHeight = $(".items-lists")[0].scrollHeight;
	 				    var divHeight = $(".items-lists").height();
	 				    var trueHeight = inHeight - divHeight;
	 			        if((scrolled + 150) > trueHeight){
	 			        	scrolled = trueHeight;
	 			        }
	 			        else{
	 			    	    scrolled=scrolled+150;				    	
	 			        }
	 				    // scrolled=scrolled+100;
	 					$(".items-lists").animate({
	 					        scrollTop:  scrolled
	 					},200);
			 		});
			 		$("#menu-item-scroll-up").on("click" ,function(){
			 			// scrolled=scrolled-100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			if(scrolled > 0){
			 				scrolled=scrolled-150;
			 				$(".items-lists").animate({
			 				        scrollTop:  scrolled
			 				},200);
			 			}
			 		});
			 	},'json');
			}
			return false;
		});
		function loadMenuCategories(){
		 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
		 	$.post(baseUrl+'cashier/get_menu_cats',function(data){
		 		showMenuCategories(data,1);
		 	},'json');
		}
		function showMenuCategories(data,ctr){
			$('.category-btns').remove();

			$.each(data,function(key,val){
				var cat_id = val['id'];
				if(ctr == 1){
					var hashTag = window.location.hash;
					// alert(hashTag);
					if(hashTag != '#retail'){
						loadsDiv('menus',cat_id,val,null);
					}
				}
	 			$('<button/>')
	 			.attr({'id':'menu-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
	 			.click(function(){
	 				$('#search-menu').val('');
	 				loadsDiv('menus',cat_id,val,null);
	 				return false;
	 			});
				ctr++;
			});
			if(ctr < 9){
				for (var i = 0; i <= (8-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
		}
		function loadItemCategories(){
		 	$.post(baseUrl+'cashier/get_item_categories',function(data){
		 		showItemCategories(data,1);
		 	},'json');
		}
		function showItemCategories(data,ctr){
			$('.category-btns').remove();
			$.each(data,function(cat_id,val){
				if(ctr == 1){
					loadsDiv('retail');
				}
	 			$('<button/>')
	 			.attr({'id':'item-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
	 			.click(function(){
	 				var formData = 'cat_id='+cat_id+'&cat_name='+val.name;
	 				loadsDiv('retail');
	 				loadRetailItemList(formData,$(this));
	 				return false;
	 			});
				ctr++;
			});
			// alert(ctr);
			if(ctr < 9){
				for (var i = 0; i <= (8-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
		}
		function loadRetailItemList(formData,btn){
			btn.goLoad();
			$.post(baseUrl+'cashier/get_item_lists',formData,function(data){
				$('.retail-title').text(data.title).show();
				$('.retail-loads-div').html(data.code);
				$.each(data.items,function(item_id,opt){
					$('#retail-item-'+item_id).click(function(){
						addRetailTransCart(item_id,opt);
						return false;
					});
				});
				$('#search-item').val('');
				btn.goLoad({load:false});
			},'json');
			// alert(data);
			// });
		}
		function remeload(type_load){
			if(type_load == 'retail'){
				$('#retail-btn').removeClass('counter-btn-red');
				$('#retail-btn').addClass('counter-btn-green');
				loadsDiv('retail');
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				loadItemCategories();
				$('#scan-code').focus();
			}
			else{
				$('#retail-btn').removeClass('counter-btn-green');
				$('#retail-btn').addClass('counter-btn-red');
			}
		}
		function loadsDiv(type,id,opt,trans_id,other){
			if(type == 'menus'){
				remeload('menu');
				$('.scrollers-menu').remove();
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				$('.menus-div .title').text(opt.name);
				$('.menus-div .items-lists').html('');
				$.post(baseUrl+'cashier/get_menus_sorted/'+id,function(data){
					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
			 		$.each(data,function(key,opt){
			 			
			 			var menu_id = opt.id;
			 			var sCol = $('<div class="col-md-3"></div>');
			 			$('<button/>')
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default'})
			 			.text(opt.name)
			 			.appendTo(sCol)
			 			.click(function(){
			 				if(opt.free == 1){
				 				$.callManager({
				 					success : function(){
								 				addTransCart(menu_id,opt);
				 							  }	
				 				});
			 				}
			 				else{
			 					addTransCart(menu_id,opt);
			 				}
			 				return false;
			 			});
			 			sCol.appendTo(div);
			 			
			 		});

			 		$('.menus-div .items-lists').after('<div id="scrollers-menu"><div class="row"><div class="col-md-6 text-left"><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
			 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });

	 				    var inHeight = $(".items-lists")[0].scrollHeight;
	 				    var divHeight = $(".items-lists").height();
	 				    var trueHeight = inHeight - divHeight;
	 			        if((scrolled + 150) > trueHeight){
	 			        	scrolled = trueHeight;
	 			        }
	 			        else{
	 			    	    scrolled=scrolled+150;				    	
	 			        }
	 				    // scrolled=scrolled+100;
	 					$(".items-lists").animate({
	 					        scrollTop:  scrolled
	 					},200);
			 		});
			 		$("#menu-item-scroll-up").on("click" ,function(){
			 			// scrolled=scrolled-100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			if(scrolled > 0){
			 				scrolled=scrolled-150;
			 				$(".items-lists").animate({
			 				        scrollTop:  scrolled
			 				},200);
			 			}
			 		});
			 	},'json');
			}
			else if(type=='mods'){
				remeload('menu');
				$('.mods-div .title').text(opt.name+" Modifiers");
				$('.mods-div .mods-lists').html('');
				var trans_det = opt;

				var formData = 'menu_name='+trans_det.name;
				if(other == "addModDefault"){
					formData += '&add_defaults=1';
				}	
				$.post(baseUrl+'cashier/get_menu_modifiers_wth_dflt/'+id+'/'+trans_id,formData,function(data){
					var modGRP = data.group;
					var dfltGRP = data.dflts;
					if(!$.isEmptyObject(dfltGRP)){
						$.each(dfltGRP,function(trans_mod_id,mopt){
							makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,trans_det,"","default");
						});	
					}	
					if(!$.isEmptyObject(modGRP)){
						$('.loads-div').hide();
						$('.'+type+'-div').show();
						$.each(modGRP,function(mod_group_id,opt){
							var row = $('<div/>').attr({'class':'mod-group','id':'mod-group-'+mod_group_id}).appendTo('.mods-div .mods-lists');
							$('<h4/>').text(opt.name)
									  .addClass('text-center receipt media-mod-title')
									  .css({'margin-bottom':'5px'})
									  .appendTo('#mod-group-'+mod_group_id);
							var mandatory = opt.mandatory;
							var multiple = opt.multiple;

							var div = $('#mod-group-'+mod_group_id);
							var divRow = $('<div/>').attr({'class':'row'});
							// var div = $('#mod-group-'+mod_group_id).append('<div class="row"></div>');
							$.each(opt.details,function(mod_id,det){
								var sCol = $('<div class="col-md-4"></div>');
								$('<button/>')
								.attr({'id':'mod-'+mod_id,'ref':mod_id,'class':'counter-btn-silver btn btn-block btn-default'})
								// .css({'margin':'5px','width':'130px'})
								.text(det.name)
								.appendTo(sCol)
								.click(function(){
									addTransModCart(trans_id,mod_group_id,mod_id,det,id,$(this),trans_det,mandatory,multiple);
									return false;
								});
				 				sCol.appendTo(divRow);
				 			});
				 			div.append(divRow);
				 			$('<hr/>').appendTo('#mod-group-'+mod_group_id);
				 		});
						$('.mods-div .mods-lists').after('<div id="scrollers-mods"><div class="row"><div class="col-md-6 text-left"><button id="mods-item-scroll-up" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="mods-item-scroll-down" class="btn-block counter-btn double btn btn-default "><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
				 		$("#mods-item-scroll-down").on("click" ,function(){
				 		 //    scrolled=scrolled+100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });

		 				    var inHeight = $(".mods-lists")[0].scrollHeight;
		 				    var divHeight = $(".mods-lists").height();
		 				    var trueHeight = inHeight - divHeight;
		 			        if((scrolled + 150) > trueHeight){
		 			        	scrolled = trueHeight;
		 			        }
		 			        else{
		 			    	    scrolled=scrolled+150;				    	
		 			        }
		 				    // scrolled=scrolled+100;
		 					$(".mods-lists").animate({
		 					        scrollTop:  scrolled
		 					},200);
				 		});
				 		$("#mods-item-scroll-up").on("click" ,function(){
				 			// scrolled=scrolled-100;
				 			// $(".items-lists").animate({
				 			//         scrollTop:  scrolled
				 			// });
				 			if(scrolled > 0){
				 				scrolled=scrolled-150;
				 				$(".mods-lists").animate({
				 				        scrollTop:  scrolled
				 				},200);
				 			}
				 		});
					}
			 	},'json');
			}
			else if(type=='qty'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='remarks'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else if(type=='charges'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
			else if(type=='sel-discount'){
				$('.loads-div').hide();
				$('.'+type+'-div').show();
				selectModMenu();
			}
			else{
				$('.loads-div').hide();
				$('.'+type+'-div').show();
			}
		}
		function addTransCart(menu_id,opt){
			var formData = 'menu_id='+menu_id+'&name='+opt.name+'&cost='+opt.cost+'&no_tax='+opt.no_tax+'&qty=1';
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,menu_id,data.items);
				loadsDiv('mods',menu_id,data.items,data.id,"addModDefault");
				transTotal();
			},'json');
		}
		function addRetailTransCart(item_id,opt){
			var formData = 'menu_id='+item_id+'&name='+opt.name+'&cost='+opt.cost+'&no_tax=0&qty=1&retail=1';
			$.post(baseUrl+'wagon/add_to_wagon/trans_cart',formData,function(data){
				makeItemRow(data.id,item_id,data.items);
				// loadsDiv('mods',menu_id,data.items,data.id);
				transTotal();
				$('#go-scan-code').removeClass('counter-btn-orange');
				$('#go-scan-code').addClass('counter-btn-green');
				$('#scan-code').focus();
			},'json');
		}
		function addTransModCart(trans_id,mod_group_id,mod_id,opt,menu_id,btn,trans_det,mandatory,multiple){
			var formData = 'trans_id='+trans_id+'&mod_group_id='+mod_group_id+'&mod_id='+mod_id+'&menu_id='+menu_id+'&menu_name='+trans_det.name
							+'&mandatory='+mandatory
							+'&multiple='+multiple
							+'&name='+opt.name+'&cost='+opt.cost+'&qty=1';
			// console.log(formData);
			if(btn != null){
				btn.prop('disabled', true);				
			}
			$.post(baseUrl+'cashier/add_trans_modifier',formData,function(data){
				if(data.error != null){
					rMsg(data.error,'error');
				}
				else{
					// console.log(data.id);
					// console.log(trans_id);
					// console.log(mod_id);
					// console.log(opt);
					makeItemSubRow(data.id,trans_id,mod_id,opt,trans_det)
				}
				if(btn != null){
					btn.prop('disabled', false);
				}
				transTotal();
			},'json');
			// alert(data);
			// });
		}
		function makeItemRow(id,menu_id,opt,loaded){
			$('.sel-row').removeClass('selected');
			var retail = "";
			if (opt.hasOwnProperty('retail')) {
				retail = 'retail-item';
			}

			$('<li/>').attr({'id':'trans-row-'+id,'trans-id':id,'ref':id,'class':'sel-row trans-row selected '+retail+' '+loaded})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					if (!opt.hasOwnProperty('retail')) {
						loadsDiv('mods',menu_id,opt,id);
					}
					else{
						remeload('retail');
					}
					return false;
				});
			$('<span/>').attr('class','qty').text(opt.qty).css('margin-left','10px').appendTo('#trans-row-'+id);
			var namer = opt.name;
			if (opt.hasOwnProperty('retail')) {
				namer = '<i class="fa fa-shopping-cart"></i> '+opt.name;
			}
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					namer = ' <i class="fa fa-print"></i> '+namer;
			}
			$('<span/>').attr('class','name').html(namer).appendTo('#trans-row-'+id);
			$('<span/>').attr('class','cost').text(opt.cost).css('margin-right','10px').appendTo('#trans-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeItemSubRow(id,trans_id,mod_id,opt,trans_det,loaded,dflt){
			var subRow = $('<li/>').attr({'id':'trans-sub-row-'+id,'trans-id':trans_id,'trans-mod-id':id,'ref':id,'class':'trans-sub-row sel-row '+loaded})
								   .click(function(){
										selector($(this));
										loadsDiv('mods',trans_det.menu_id,trans_det,trans_id);
										return false;
									});
			var mod_name = opt.name;					   
			if (opt.hasOwnProperty('kitchen_slip_printed')) {
				if(opt.kitchen_slip_printed == 1)
					mod_name = ' <i class="fa fa-print"></i> '+mod_name;
			}

			$('<span/>').attr('class','name').css('margin-left','28px').html(mod_name).appendTo(subRow);
			if(parseFloat(opt.cost) > 0)
				$('<span/>').attr('class','cost').css('margin-right','10px').html(opt.cost).appendTo(subRow);

			if(dflt == "default"){
				$('#trans-row-'+trans_id).after(subRow);
			}
			else{
				$('.selected').after(subRow);
			}

			$('.sel-row').removeClass('selected');
			selector($('#trans-sub-row-'+id));
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function selector(li){
			$('.sel-row').removeClass('selected');
			li.addClass('selected');
		}
		function selectModMenu(){
			var sel = $('.selected');
			if(sel.hasClass('trans-sub-row')){
				var trans_id = sel.attr("trans-id");
				selector($('#trans-row-'+trans_id));
			}
		}
		function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;
				var local_tax = data.local_tax;
				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
				if($("#local-tax-txt").exists()){
					$("#local-tax-txt").number(local_tax,2);
				}
				
				if(data.zero_rated > 0){
					$("#zero-rated-btn").removeClass('counter-btn-red');
					$("#zero-rated-btn").addClass('counter-btn-green');
					$("#zero-rated-btn").addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
				}
			},'json');
			// 	alert(data);
			// });
		}
		function loadTransCart(){
			$.post(baseUrl+'cashier/get_trans_cart/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					console.log(data);
					$.each(data,function(trans_id,opt){
						makeItemRow(trans_id,opt.menu_id,opt,'loaded');
						if(opt.remarks != "" && opt.remarks != null){
							makeRemarksItemRow(trans_id,opt.remarks);
						}
						var modifiers = opt.modifiers;
						if(!$.isEmptyObject(modifiers)){
							$.each(modifiers,function(trans_mod_id,mopt){
								makeItemSubRow(trans_mod_id,mopt.trans_id,mopt.mod_id,mopt,mopt,'loaded');
							});
						}
						if(ctr == len)
							$('.selected').trigger('click');
						ctr++;
					});
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
		}
		function loadTransChargeCart(){
			$.post(baseUrl+'cashier/get_trans_charges/',function(data){
				if(!$.isEmptyObject(data)){
					var len = data.length;
					var ctr = 1;
					$.each(data,function(charge_id,opt){
						makeChargeItemRow(charge_id,opt);
					});
				}
				transTotal();
				// checkWagon('trans_cart');
			},'json');
		}
		function newTransaction(redirect,type){
			$.post(baseUrl+'cashier/new_trans/true/'+type,function(data){
				if(!redirect){
					$('#trans-datetime').text(data.datetime);
					var tp = data.type;
					$('#trans-header').text(tp.toUpperCase());

					$('.trans-lists').find('li').remove();
					var cat_id = $(".category-btns:first").attr('ref');
					var cat_name = $(".category-btns:first").text();
					var val = {'name':cat_name};
					loadsDiv('menus',cat_id,val,null);
					transTotal();
					$('.addon-texts').text('').hide();
					if(type == 'retail')
						remeload('retail');
					if(type=='dinein')
						window.location = baseUrl+'cashier/tables';
					else if(type=='delivery')
						window.location = baseUrl+'cashier/delivery';
					else if(type=='pickup')
						window.location = baseUrl+'cashier/pickup';
				}
				else{
					if(type=='dinein')
						window.location = baseUrl+'cashier/tables';
					else if(type=='delivery')
						window.location = baseUrl+'cashier/delivery';
					else if(type=='pickup')
						window.location = baseUrl+'cashier/pickup';
					else{
						window.location = baseUrl+'cashier/counter/'+data.type;
					}
				}
			},'json');
		}
		function loadDefault(){
			var cat_id = $(".category-btns:first").attr('ref');
			var cat_name = $(".category-btns:first").text();
			var val = {'name':cat_name};
			loadsDiv('menus',cat_id,val,null);
		}
		function loadDiscounts(){
			$.post(baseUrl+'cashier/get_discounts',function(data){
				$('.select-discounts-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#item-disc-btn-'+id).click(function(){
						var idisc = $(this);
						if(opt.disc_code == 'SNDISC' || opt.disc_code == 'PWDISC'){
							$('#prcss-disc').attr('ref','equal');
						}
						else{
							$('#prcss-disc').attr('ref','all');
						}
						$.callManager({
		 					success : function(){
								loadsDiv('discount',null,null,null);
								$('.discount-div .title').text(idisc.text());
								$('.discount-div #rate-txt').number(opt.disc_rate,2);
								$('#disc-disc-id').val(opt.disc_id);
								$('#disc-disc-rate').val(opt.disc_rate);
								$('#disc-disc-code').val(opt.disc_code);
								$('#disc-no-tax').val(opt.no_tax);
								$('#disc-fix').val(opt.fix);
								$('#disc-guests').val(opt.guest);
								$.post(baseUrl+'cashier/load_disc_persons/'+opt.disc_code,function(data){
									$('.disc-persons-list-div').html(data.code);
									$.each(data.items,function(code,opt){
										$("#disc-person-"+code).click(function(){
											var lin = $(this);
											$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
												lin.remove();
												rMsg('Person Removed.','success');
												transTotal();
											});
											return false;
										});
									});
								},'json');
								// if (typeof opt.name != 'undefined') {
								// 	$('#disc-cust-name').val(opt.name);
								// 	$('#disc-cust-guest').val(opt.guest);
								// 	$('#disc-guests').val(opt.guest);
								// 	$('#disc-cust-code').val(opt.code);
								// 	$('#disc-cust-bday').val(opt.bday);
								// }
		 					}	
		 				});
						return false;
					});
				});
			},'json');
		}
		function loadCharges(){
			$.post(baseUrl+'cashier/get_charges',function(data){
				$('.charges-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#charges-btn-'+id).click(function(){
						addChargeCart(id,opt);
						return false;
					});
				});
			},'json');
		}
		function loadWaiters(){
			$.post(baseUrl+'cashier/get_waiters',function(data){
				$('.waiters-lists').html(data.code);
				$.each(data.ids,function(id,opt){
					$('#waiters-btn-'+id).click(function(){
						$.callFS({
							success : function(emp){
										if(id == emp['emp_id']){
											$.post(baseUrl+'cashier/update_trans/waiter_id/'+id,function(data){
												$('#trans-server-txt').text('FS: '+opt.uname).show();
												rMsg(opt.full_name+' added as Food Server','success');
											},'json');
										}
										else{
											rMsg('Wrong Pin.','error');
										}
									  }
						});
						return false;
					});
				});
			},'json');
		}
		function addChargeCart(id,row){
			var formData = 'name='+row.charge_name+'&code='+row.charge_name+'&amount='+row.charge_amount+'&absolute='+row.absolute;
			$.post(baseUrl+'wagon/add_to_wagon/trans_charge_cart/'+id,formData,function(data){
				if(data.error == null){
					makeChargeItemRow(data.id,data.items);
					// loadsDiv('mods',menu_id,data.items,data.id);
					transTotal();
				}
				else{
					rMsg(data.error,'error');
				}
			},'json');
			// });
		}
		function makeChargeItemRow(id,opt){
			$('.sel-row').removeClass('selected');
			$('<li/>').attr({'id':'trans-charge-row-'+id,'charge-id':id,'ref':id,'class':'sel-row trans-charge-row selected'})
				.appendTo('.trans-lists')
				.click(function(){
					selector($(this));
					loadsDiv('charges');
					return false;
				});
			$('<span/>').attr('class','qty').html('<i class="fa fa-tag"></i>').css('margin-left','10px').appendTo('#trans-charge-row-'+id);
			$('<span/>').attr('class','name').text(opt.name).appendTo('#trans-charge-row-'+id);
			var tx = opt.amount;
			if(opt.absolute == 0){
				tx = opt.amount+'%';
			}
			$('<span/>').attr('class','cost').text(tx).css('margin-right','10px').appendTo('#trans-charge-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function makeRemarksItemRow(id,remarks){
			$('.sel-row').removeClass('selected');
			if($('#trans-remarks-row-'+id).exists()){
				$('#trans-remarks-row-'+id).remove();
			}
			$('<li/>').attr({'id':'trans-remarks-row-'+id,'ref':id,'class':'sel-row trans-remarks-row selected'})
				.insertAfter('.trans-lists li#trans-row-'+id)
				.click(function(){
					selector($(this));
					loadsDiv('remarks');
					$('#line-remarks').val(remarks);
					return false;
				});
			// $('<span/>').attr('class','qty').html('').css('margin-left','10px').appendTo('#trans-remarks-row-'+id);
			$('<span/>').attr('class','name').css('margin-left','26px').html('<i class="fa fa-text-width"></i> '+remarks).appendTo('#trans-remarks-row-'+id);
			$('.counter-center .body').perfectScrollbar('update');
			$(".counter-center .body").scrollTop($(".counter-center .body")[0].scrollHeight);
		}
		function checkWagon(name){
			$.post(baseUrl+'wagon/get_wagon/'+name,function(data){
				alert(data);
			});
		}
		$('#disc-cust-name,#disc-cust-code,#disc-cust-bday,#disc-cust-guest,#line-remarks,#search-item')
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


     //##############FOR DISCOUNT######################
    <?php elseif($use_js == 'discountJS'): ?>

    	$(document).off('click','.show_form').on('click','.show_form',function(){
    		disc_id = $(this).attr('ref');
    		discode = $(this).attr('disc_code');
    		// alert(discode);
    		fix = $(this).attr('fix');
    		rrate = $(this).attr('rate');
    		name = $('#ndisc-cust-name').val("");
    		disc_code = $('#ndisc-cust-code').val("");
    		bday = $('#ndisc-cust-bday').val("");
    		$('#ndiscount-id').val(disc_id);
    		$('#cust-disc-code').val(discode);
    		// if(disc_id == 1){
    		// 	$("#div_form_disc_senior").show();
    		// 	$("#div_form_disc").hide();
    		// }else{
    		// 	$("#div_form_disc").show();
    		// 	$("#div_form_disc_senior").hide();
    		// }

    		if($('#guest_count').val() > 1){
    			// alert($('#guest_count').val());   

    	// 		swal({
					//   title: "Quantity",
					//   text: "Input quantity:",
					//   type: "input",
					//   showCancelButton: true,
					//   closeOnConfirm: false,
					//   animation: "slide-from-top",
					//   inputPlaceholder: "Input quantity",
					//   // inputClass: "swal-input"
					// },
					// function(inputValue){
					//   if (inputValue === null) return false;
					  
					//   if (inputValue === "" || !$.isNumeric(inputValue)) {
					//     swal.showInputError("Please input quantity");
					//     return false
					//   }
					  
					// });

					disc_qty = prompt("Please enter quantity", "1");
					running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) : 0;

					if(disc_qty == null){
						return false;
					}

					if (!$.isNumeric(disc_qty)) {
						alert('Invalid quantity.');
					}else if(parseInt(disc_qty) + running_count > $('#guest_count').val()){
						alert('Quantity should not more than guest count.');
					}else{
						$("#div_form_disc").show();
						$("#disc_count").val(disc_qty);
						$('#disc_ctr').val(0);
						$('#process').prop('disabled',true);
						$('#submit-discount-btn').prop('disabled',false);
					}
    		}else{
    			running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) : 0;
    			if(running_count == $('#guest_count').val()){
    				alert('Discount quantity should not more than guest count.');
    			}else{
    				$("#disc_count").val(1);
	    			$('#disc_ctr').val(0);
	    			$("#div_form_disc").show();
	    			$('#submit-discount-btn').prop('disabled',false);
    			}
    			
    		}

    		if(fix == 1 && rrate == 0){
    			$("#opendiv").show();
    		}else{
    			$("#opendiv").hide();
    		}

    		$('.show_form').attr('class','btn-primary btn-block show_form btn');
    		$(this).attr('class','btn-success btn-block show_form btn');



    		///auto fill docus
			$('#ndisc-cust-name').keydown(function(){
				// ref = $(this).attr('refs');
					// dcode = $('#cust-disc-code').val();
					// alert(dcode);
					// alert(dcode);
					if(discode == 'SNDISC'){

				   		var opts = $('#seniors').find('option');
				   		var arr = [];
						opts.each(function(){
							// var val = $(this).attr('value');
							var val = "";
							var txt = $(this).text();
							arr.push({"code":val,"items":txt});
						});	
						

						$('#ndisc-cust-name').typeaheadmap({ "source":arr, 
						    "key": "items", 
						    "value": "code", 
						    "listener": function(k, v) {
						    				formData = 'val='+k+'&type=name';
						    				$.post(baseUrl+'cashier/get_customer_dets',formData,function(data){
									       		// alert(data);
									       		// console.log(data);
									       		$('#ndisc-cust-code').val(data);

									       	});
						    				// alert(v);
											// $('#cust-id').val(v);
											// $('#cust-item-drop-set').trigger("change");	
											//$('#'+ref).select();
										}		
						});


					}

			});

			$('#ndisc-cust-code').keydown(function(){
				// ref = $(this).attr('refs');
					// dcode = $('#cust-disc-code').val();
					// alert(dcode);
					// alert(dcode);
					// alert('aaaaa');
					if(discode == 'SNDISC'){
						// alert('aw');

				   		var opts = $('#ids_number').find('option');
				   		var arr = [];
						opts.each(function(){
							// var val = $(this).attr('value');
							var val = "";
							var txt = $(this).text();
							arr.push({"code":val,"items":txt});
						});	
						

						$('#ndisc-cust-code').typeaheadmap({ "source":arr, 
						    "key": "items", 
						    "value": "code", 
						    "listener": function(k, v) {
						    				formData = 'val='+k+'&type=id';
						    				$.post(baseUrl+'cashier/get_customer_dets',formData,function(data){
									       		// alert(data);
									       		// console.log(data);
									       		$('#ndisc-cust-name').val(data);

									       	});
						    				// alert(v);
											// $('#cust-id').val(v);
											// $('#cust-item-drop-set').trigger("change");	
											//$('#'+ref).select();
										}		
						});


					}

			});



    	});


  // 		/////auto fill docus
		// $('#ndisc-cust-name').keydown(function(){
		// 	// ref = $(this).attr('refs');
		// 		dcode = $('#cust-disc-code').val();
		// 		// alert(dcode);
		// 		// alert(dcode);

		// 		if(dcode == 'SNDISC'){

		// 	   		var opts = $('#seniors').find('option');
		// 	   		var arr = [];
		// 			opts.each(function(){
		// 				// var val = $(this).attr('value');
		// 				var val = "";
		// 				var txt = $(this).text();
		// 				arr.push({"code":val,"items":txt});
		// 			});	
					

		// 			$('#ndisc-cust-name').typeaheadmap({ "source":arr, 
		// 			    "key": "items", 
		// 			    "value": "code", 
		// 			    "listener": function(k, v) {
		// 			    				// alert(v);
		// 								// $('#cust-id').val(v);
		// 								// $('#cust-item-drop-set').trigger("change");	
		// 								//$('#'+ref).select();
		// 							}		
		// 			});


		// 		}

		// });

		// $("#ndisc-cust-name").typeaheadmap({ 
		// 	"key": "key", 
		//     "value": "value",
		// 	"source" : function(q, process) { 
		// 		ref = 1;
		// 		var formData = "key="+q+"&ref="+ref;
		// 	    $.post(baseUrl+'deposit/codes_json',formData,function(data){
		// 	    	alert(data);
		// 	    	// $('#comp-header').append(data);
		// 	    	// process(data);
		// 	    // },'json');
		// 	    });
		// 	},
		// 	"displayer" : function(that, item, highlighted) {
		//     	alert(highlighted);
		//     	// return highlighted  
		// 	} 
		// });


    	$('#cancel-disc-person-btn').click(function(){
    		$("#div_form_disc").hide();
    		$("#div_form_disc_senior").hide();
    		name = $('#ndisc-cust-name').val("");
    		disc_code = $('#ndisc-cust-code').val("");
    		bday = $('#ndisc-cust-bday').val("");
    		$('#ndiscount-id').val(null);
    	});
    	$('#cancel-sdisc-person-btn').click(function(){
    		$("#div_form_disc").hide();
    		$("#div_form_disc_senior").hide();
    		sname = $('#sndisc-cust-name').val("");
    		sdisc_code = $('#sndisc-cust-code').val("");
    		sbday = $('#sndisc-cust-bday').val("");
    		$('#ndiscount-id').val(null);
    	});

    	$('#submit-discount-btn').click(function(){
    		disc_id = $('#ndiscount-id').val();
    		name = $('#ndisc-cust-name').val();
    		disc_code = $('#ndisc-cust-code').val();
    		bday = $('#ndisc-cust-bday').val();
    		remarks = $('#ndisc-remark').val();
    		guest = $('#nguest').val();
    		total = $('#ntotal').val();
    		openamt = $('#ndisc-openamt').val();
    		// alert(name);
    		if(name){
    			// alert('sss');
    			formData = 'disc_id='+disc_id+'&name='+name+'&disc_code='+disc_code+'&bday='+bday+'&guest='+guest+'&total='+total+'&remark='+remarks+'&openamt='+openamt;
		       	$.post(baseUrl+'cashier/add_discount_session',formData,function(data){
		       		// alert(data);
		       		// console.log(data);
		       		// $('#added-disc-div').html(data.code);
		       		// $('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
		       		// $('#disc-total-span').html("<font size='4'>wala pa</font>");
		       		// alert(data);
		       		if(data == 'bigger'){
		       			// rMsg('Given discount amount is bigger that total payable. Please add more items','error');
		       			var conf = confirm('Given discount amount is bigger than total payable. Please add more items.');
					   	if(conf){
					      $('.bootbox-close-button').click();
					   	}
		       		}else if(data == 'addopenamt'){
		       			rMsg('Please input an Amount.','error');
		       		}else if(data == 'guestlimit'){
		       			rMsg('Person is in limit with the no of guest.','error');
		       		}
		       		else if(data == 'exist'){
		       			rMsg('Person already added.','error');
		       		}
		       		else{
			       		// $("#div_form_disc").hide();
			       		// $("#div_form_disc_senior").hide();
			    		name = $('#ndisc-cust-name').val("");
			    		disc_code = $('#ndisc-cust-code').val("");
			    		bday = $('#ndisc-cust-bday').val("");
			    		remarks = $('#ndisc-remark').val("");
			    		// $('#ndiscount-id').val(null);

			    		running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) + 1 : 1;
			       		$('#running_count').val(running_count);

			       		disc_ctr = $('#disc_ctr').val() != '' ? parseInt($('#disc_ctr').val()) + 1 : 1;
			       		$('#disc_ctr').val(disc_ctr);

			       		disc_count = parseInt($('#disc_count').val());

			       		if(disc_count == disc_ctr){
			       			$('#submit-discount-btn').prop('disabled',true);
			       			$('#process').removeAttr('disabled');
			       			$("#div_form_disc").hide();
			       		}

			       		transTotal();
			       		computeDiscount();


		       		}
		       	// },'json');	
		       	});	
    		}else{
    			rMsg('Please input a Customer Name.','error');
    		}
    	});
    	$('#submit-sdiscount-btn').click(function(){
    		disc_id = $('#ndiscount-id').val();
    		name = $('#sndisc-cust-name').val();
    		disc_code = $('#sndisc-cust-code').val();
    		bday = $('#sndisc-cust-bday').val();
    		remarks = $('#sndisc-remark').val();
    		guest = $('#nguest').val();
    		total = $('#ntotal').val();
    		// alert(disc_code);
    		if(name){
    			formData = 'disc_id='+disc_id+'&name='+name+'&disc_code='+disc_code+'&bday='+bday+'&guest='+guest+'&total='+total+'&remark='+remarks;
		       	$.post(baseUrl+'cashier/add_discount_session',formData,function(data){
		       		// alert(data);
		       		// console.log(data);
		       		// $('#added-disc-div').html(data.code);
		       		// $('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
		       		// $('#disc-total-span').html("<font size='4'>wala pa</font>");
		       		if(data == 'bigger'){
		       			// rMsg('Given discount amount is bigger that total payable. Please add more items','error');
		       			var conf = confirm('Given discount amount is bigger than total payable. Please add more items.');
					   	if(conf){
					      $('.bootbox-close-button').click();
					   	}
		       		}else{
			       		$("#div_form_disc").hide();
			       		$("#div_form_disc_senior").hide();
			    		name = $('#sndisc-cust-name').val("");
			    		disc_code = $('#sndisc-cust-code').val("");
			    		bday = $('#sndisc-cust-bday').val("");
			    		remarks = $('#sndisc-remark').val("");
			    		$('#ndiscount-id').val(null);


			       		transTotal();
			       		computeDiscount();
		       		}
		       	// },'json');	
		       	});	
    		}else{
    			rMsg('Please input a Customer Name.','error');
    		}
    	});

    	function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;
				var local_tax = data.local_tax;
				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
				if($("#local-tax-txt").exists()){
					$("#local-tax-txt").number(local_tax,2);
				}
				
				if(data.zero_rated > 0){
					$("#zero-rated-btn").removeClass('counter-btn-red');
					$("#zero-rated-btn").addClass('counter-btn-green');
					$("#zero-rated-btn").addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
				}

				// $("#amount-due-txt").number(total,2);
				$("#vat-txt").number(data.vat_txt,2);
				$("#vat-sales-txt").number(data.vat_sales_txt,2);
				$("#subtotal-txt").number(data.gross,2);
				$("#amount-due-txt").number(total,2);
				$("#discount-amount-txt").number(data.discount,2);
				$("#charge-amount-txt").number(data.charge,2);
			// 	alert(data);
			// 	console.log(data);
			// });
			},'json');

			// $.post(baseUrl+'cashier/counter_trans_details',function(data){
			// 	$("#subtotal-txt").number(data.total_gross,2);
			// 	$("#vat-txt").number(data.tax < 0 ? data.tax*-1:data.tax,2);

			// 	if(data.tax < 0){
			// 		$("#vat-txt").text('('+$("#vat-txt").text()+')');
			// 	}

			// 	$("#vat-sales-txt").number(data.tax > 0 ? data.total_gross-data.tax : 0,2);
			// 	$("#discount-amount-txt").number(data.total_discount,2);
			// 	$("#charge-amount-txt").number(data.total_charges,2);

			// 	$("#discount-amount-txt").text('('+$("#discount-amount-txt").text()+')');
			// },'json');
			// 	alert(data);
			// 	console.log(data);
			// });
		}

		function computeDiscount(){
			formData = 'total='+total+'&guest='+guest;
			$.post(baseUrl+'cashier/compute_discount_right',formData,function(data){
				// alert(data.code);
				// console.log(data);
	       		$('#added-disc-div').html(data.code);
	       		$('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
	       		$('#disc-total-span').html("<font size='4'>"+data.total_discount.toFixed(2)+"</font>");
	       		$('#disc-lv-span').html("<font size='4'>"+data.total_lv.toFixed(2)+"</font>");

			},'json');
			// 	alert(data);
			// 	console.log(data);
			// });
		}

		$('#reset').click(function(){
			guest = $('#nguest').val();
    		total = $('#ntotal').val();
			$.post(baseUrl+'cashier/reset_discount',formData,function(data){
				// console.log(data);
	       		// $('#added-disc-div').html("");
	       		// $('#disc-count-span').html("<font size='4'>0</font>");
	       		// $('#disc-total-span').html("<font size='4'>0.00</font>");
	       		// $('#disc-lv-span').html("<font size='4'>0.00</font>");
	       		$('#process').removeAttr('disabled');
	       		transTotal();
	       		computeDiscount();
			// },'json');
			// 	alert(data);
			// 	console.log(data);
			});
		});

		// $('#ndisc-remark').click(function(){

		// 	$.callDiscReasons({
		// 		submit : function(reason){
		// 			// console.log(reason);
		// 			var free_reason = reason;
		// 			// if(free_reason == ""){
		// 			// 	rMsg('Please select/input a reason','error');
		// 			// 	return false;
		// 			// }else{
		// 				var formData = 'free_reason='+free_reason;

		// 				// $('body').goLoad2();
		// 				$('#ndisc-remark').val(free_reason);
		// 				// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
		// 				// 	// console.log(data);
		// 				// 	$('#trans-row-'+id+' .cost').text(0);
		// 				// 	var text = $('#trans-row-'+id).find('.name').text();
		// 				// 	// alert(text);
		// 				// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text + " <br>&nbsp;&nbsp;&nbsp;<span class='label label-sm label-warning'>"+free_reason+"</span>");
		// 				// 	transTotal();
		// 				// 	rMsg('Updated Menu as free','success');
		// 				// 	$('body').goLoad2({load:false});
		// 				// });
		// 			// }
					
		// 			// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
		// 			// 	// alert(data);
		// 			// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
		// 			// 		sel.prev().addClass('selected');
		// 			// 		sel.remove();
		// 			// 		if(cart == 'trans_cart' && retail === false){
		// 			// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
		// 			// 				var cat_id = $(".category-btns:first").attr('ref');
		// 			// 				var cat_name = $(".category-btns:first").text();
		// 			// 				var val = {'name':cat_name};
		// 			// 				loadsDiv('menus',cat_id,val,null);
		// 			// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
		// 			// 			});
		// 			// 		}
		// 			// 		$('.counter-center .body').perfectScrollbar('update');
		// 			// 		transTotal();
		// 			// 	},'json');
		// 			// });

		// 		}
		// 	});

		// });

		$('#search-disc').on('keyup',function(){
    		
			val = $(this).val();

			$('#search-disc-div').html('');

			if(val == ""){
				$('.disc-buttons').show();
			}else{
				$('.disc-buttons').hide();

				var div = $('#search-disc-div');

				formData = "val="+val;

				$.post(baseUrl+'cashier/search_discs',formData,function(data){
	 				
	 				// alert(data);
	 				$.each(data.found,function(key,opt){

		 				// 	addClss = "";
		 				// 	// alert(opt.is_new);
		 				// 	if(opt.is_new == 'new'){
		 				// 		addClss = ' payment-btns';
		 				// 	}
		 				// 	// "style"=>'margin-bottom:10px;'
	 					var sCol = $('<div class="col-md-6" style="margin-bottom:10px;"></div>');
			 			$('<button/>')
			 			.attr({'id':'disc-'+key,'class':'btn-block show_form btn-primary btn','ref':key,'fix':opt.fix,'rate':opt.rate,'disc_code':opt.disc_code})
			 			.text(opt.text)
			 			.appendTo(sCol);

			 			sCol.appendTo(div);


			 		});

		 		// });
		 		},'json');



			}


    	});

   //  	$('#search-disc')
   //      .keyboard({
   //          alwaysOpen: false,
   //          usePreview: false,
			// autoAccept : true,
			// change   : function(e, keyboard, el) { 
			// 	val = $('#search-disc').val();

			// 	$('#search-disc-div').html('');

			// 	if(val == ""){
			// 		$('.disc-buttons').show();
			// 	}else{
			// 		$('.disc-buttons').hide();

			// 		var div = $('#search-disc-div');

			// 		formData = "val="+val;

			// 		$.post(baseUrl+'cashier/search_discs',formData,function(data){
		 				
		 // 				// alert(data);
		 // 				$.each(data.found,function(key,opt){

			//  				// 	addClss = "";
			//  				// 	// alert(opt.is_new);
			//  				// 	if(opt.is_new == 'new'){
			//  				// 		addClss = ' payment-btns';
			//  				// 	}
			//  				// 	// "style"=>'margin-bottom:10px;'
		 // 					var sCol = $('<div class="col-md-6" style="margin-bottom:10px;"></div>');
			// 	 			$('<button/>')
			// 	 			.attr({'id':'disc-'+key,'class':'btn-block show_form btn-primary btn','ref':key,'fix':opt.fix,'rate':opt.rate,'disc_code':opt.disc_code})
			// 	 			.text(opt.text)
			// 	 			.appendTo(sCol);

			// 	 			sCol.appendTo(div);


			// 	 		});

			//  		// });
			//  		},'json');



			// 	}
				
			// 	return false;
			// },

   //      })
   //      .addNavigation({
   //          position   : [0,0],
   //          toggleMode : false,
   //          focusClass : 'hasFocus'
   //      });

   //      $('#ndisc-cust-bday,#ndisc-remark')
   //      .keyboard({
   //          alwaysOpen: false,
   //          usePreview: true,
			// autoAccept : true
   //      });

		// $('#process').click(function(){
		// 	transTotal();
  //      		computeDiscount();
		// });

	<?php elseif($use_js == 'invCheckJS'): ?>

		loadInventory();

		function loadInventory(search=''){
			formData = 'search='+search;
	       	$.post(baseUrl+'cashier/get_menus_check_inv',formData,function(data){
	       		// alert(data);
	       		// console.log(data);
	       		$('#menu-div').html(data.code);

	       	},'json');	
		}

		$('#search-inv').on('keyup',function(){
			var search = $(this).val();
			$('#menu-div').html('');
			loadInventory(search);
		});
	 <?php elseif($use_js == 'setCalendarJs'): ?>
	 	$(document).on("click","#advance_order_btns",function(){
	 		var tsales_id = $("#tsales_id").val();
	 		if(tsales_id){
	 			window.location = baseUrl+'cashier/counter/reservation/'+tsales_id+'#reservation';
	 			return false;
	 		}else{
	 			window.location = baseUrl+'cashier/tables_reservation/reservation';

	 		}
          // alert(tsales_id); return false;

        });
        $("#void-btn").click(function(){
			var id = $("input[type=hidden]#tsales_id").val();
			var type = $("input[type=hidden]#ttype").val();
			var status = $("input[type=hidden]#status").val();
			var tbl_id =  $("#tbl_id").val();
			// alert(tbl_id+' ... '+id+' ... '+type+' ... '+status);
			// return false;

			//check if may activity yun table
			$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				if(data.error == ""){
					$.callManager({
						success : function(manager){
							var type = $('#reasons_div').attr('approver',manager.manager_id);
							// loadDivs('reasons');
							$("#reasons_div").modal('show');
						}
					});			
				}else{
					rMsg(data.error,'error');
				}	
			},'json');
				
			return false;
		});
		$("#rcancel-btn").click(function(){
			// var id = $("input[type=hidden]#tsales_id").val();
			// var type = $("input[type=hidden]#ttype").val();
			// var status = $("input[type=hidden]#status").val();
			// var tbl_id =  $("#tbl_id").val();
			// alert(tbl_id+' ... '+id+' ... '+type+' ... '+status);
			// return false;
			 var ref_no = $("input[type=hidden]#ref_no").val();
			 // alert(ref);return false;

			//check if may activity yun table
			// $.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
				// if(data.error == ""){
					$.callManager({
						success : function(manager){
							var type = $('#reasons_div').attr('approver',manager.manager_id);
							// loadDivs('reasons');
							// $("#reasons_div").modal('show');
							var box = bootbox.dialog({
									  message: baseUrl+'cashier/other_reason_pop',
									  title: 'Cancel Reservation',
									  className: 'manager-call-pop',
									  buttons: {
									    submit: {
									      label: "Submit",
									      className: "btn btn-guest-submit pop-manage pop-manage-green",
									      callback: function() {
									      	var formData = 'reason='+$('#other-reason-txt').val()+'&ref_no='+ref_no;
									      	// formData += '&reason='+$('#other-reason-txt').val();
									      	$.post(baseUrl+'cashier/rvoid_order_cancel',formData,function(data){
									      		// alert(data);
									      		// return false;
									      		if(data.error == ""){
									      			// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
									      			// });	
									      			$("#reasons_div").modal('hide');
													$("#pickTimeModal2").modal('hide');
									      			// $("#refresh-btn").trigger('click');
									      			rMsg('Success!  Voided ','success');
									      			btn.goLoad({load:false});
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
									      	$('.manager-call-pop'). modal('hide');
									        // Example.show("uh oh, look out!");
									        // btn.goLoad({load:false});
									        // return true;
									      }
									    }
									  }
									});
									return false;
						}
					});			
			// 	}else{
			// 		rMsg(data.error,'error');
			// 	}	
			// },'json');
				
			return false;
		});
		$(".reason-btns").click(function(){
			// var id = $('.order-view-list').attr('ref');
			var id = $("input[type=hidden]#tsales_id").val();
			var type = $("input[type=hidden]#ttype").val();
			// var type = $('.order-view-list').attr('type');
			var approver = $('#reasons_div').attr('approver');

			var prev = $('#now-btn').attr('type');
			var old = 0;
			if(prev == 'all_trans'){
				old = 1;
			}
			var reason = $(this).text();
			// alert(reason);
			// return false;
			var btn = $(this);
			btn.goLoad();
			formData = 'reason='+reason+'&approver='+approver;
			$.post(baseUrl+'cashier/void_order/'+id+'/'+old,formData,function(data){
				if(data.error == ""){
					// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
					// });	

					if(data.js_rcps){
						html_print(data.js_rcps);
					}

					$("#reasons_div").modal('hide');
					$("#pickTimeModal2").modal('hide');
					$("#refresh-btn").trigger('click');
					rMsg('Success!  Voided '+type+' #'+id,'success');
					btn.goLoad({load:false});
				}
				else{
					rMsg(data.error,'error');
					btn.goLoad({load:false});
				}
			},'json');
			// alert(data);
			// });
			return false;
		});
		$("#cancel-other-reason-btn").click(function(){
			// var id = $('.order-view-list').attr('ref');
			// var type = $('.order-view-list').attr('type');
			var id = $("input[type=hidden]#tsales_id").val();
			var type = $("input[type=hidden]#ttype").val();
			var approver = $('#reasons_div').attr('approver');
			var prev = $('#now-btn').attr('type');
			var old = 0;
			if(prev == 'all_trans'){
				old = 1;
			}
			var reason = $(this).text();
			var btn = $(this);
			btn.goLoad();
			formData = 'approver='+approver;
			var box = bootbox.dialog({
			  message: baseUrl+'cashier/other_reason_pop',
			  title: 'Other Reason',
			  className: 'manager-call-pop',
			  buttons: {
			    submit: {
			      label: "Submit",
			      className: "btn btn-guest-submit pop-manage pop-manage-green",
			      callback: function() {
			      	formData += '&reason='+$('#other-reason-txt').val();
			      	$.post(baseUrl+'cashier/void_order/'+id+'/'+old,formData,function(data){
			      		if(data.error == ""){
			      			// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
			      			// });	
			      			$("#reasons_div").modal('hide');
							$("#pickTimeModal2").modal('hide');
			      			// $("#refresh-btn").trigger('click');
			      			rMsg('Success!  Voided '+type+' #'+id,'success');
			      			btn.goLoad({load:false});
			      		}
			      		else{
			      			rMsg(data.error,'error');
			      			btn.goLoad({load:false});
			      		}
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
		$("#cancel-reserve-reason-btn").click(function(){
			// var id = $('.order-view-list').attr('ref');
			// var type = $('.order-view-list').attr('type');
			var id = $("input[type=hidden]#tsales_id").val();
			var type = $("input[type=hidden]#ttype").val();
			var ref_no = $("input[type=hidden]#ref_no").val();
			// var approver = $('.order-view-list').attr('approver');
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
			$.callManager({
				success : function(manager){
					var type = $('#reasons_div').attr('approver',manager.manager_id);
					// loadDivs('reasons');
					$("#reasons_div").modal('show');
				}
			});
			event.preventDefault();
   			$('#cancel-reserve-reason-btn').prop("disabled", false);
   			$('button#cancel-reserve-reason-btn').find('i.fa-spinner').remove();
		});
		$('#credit_amount')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
			autoAccept : true
        });
		$("#save-reserve-reason-btn").click(function(){
			// $('#search-btn').click(function(){
			var id = $("input[type=hidden]#tsales_id").val();
			var type = $("input[type=hidden]#ttype").val();
			var ref_no = $("input[type=hidden]#ref_no").val();
    		var noError = $('#reasons_div').rOkay({
		     				btn_load		: 	$(this),
		     				goSubmit		: 	false,
		     				bnt_load_remove	: 	true
						  });
			if(noError){
				var approver = $('#reasons_div').attr('approver');
					formData = 'approver='+approver;
					// formData += '&add_amount='+$('#add_amount').val()+'&ref_no='+$('#ref_no').val();
			      	formData += '&reason='+$('#rother-reason-txt').val()+'&credit_amount='+$('#credit_amount').val()+'&credit_note_no='+$('#credit_note_no').val()+'&ref_no='+ref_no;
				// alert(formData);
				// return false;
				$.post(baseUrl+'cashier/rvoid_order/'+id,formData,function(data){
					      		// console.log(data);
					      		// return false;
					      		if(data.error == ""){
					      			// $.post(baseUrl+'cashier/print_sales_receipt/'+id,function(data){
					      			// });
					      			
					      			if(data.js_rcps){
					      				html_print(data.js_rcps);
					      			}	
					      			$("#reasons_div").modal('hide');
									$("#pickTimeModal2").modal('hide');
					      			// $("#refresh-btn").trigger('click');
					      			rMsg('Success!  Voided '+type+' #'+id,'success');
					      			// btn.goLoad({load:true});
					      			location.reload();
					      			return false;


					      		}
					      		else{
					      			rMsg(data.error,'error');
					      			btn.goLoad({load:false});
					      		}
					      	// });
					      	},'json');
				// var guests = $('#disc-guests').val();
				// // var ref = $(this).attr('ref');
				// var ref = $('#prcss-disc').attr('disc-code');
				// var formData = $('#disc-form').serialize();
				// formData = formData+'&type='+ref+'&guests='+guests;

				// 	var disc_code = $('#disc-disc-code').val();
				// 	if(disc_code == "SNDISC" || disc_code == "PWDISC"){
				// 		var disc_cust_code = $('#disc-cust-code').val();
				// 		console.log(disc_cust_code.length);
				// 		if(disc_cust_code == ""){
				// 			rMsg('Card Number cannot be empty','error');
				// 			return false;
				// 		}
				// 	}
				// $.post(baseUrl+'cashier/add_person_disc_line',formData,function(data){

				// 		$('#add-disc-person-btn').goLoad({load:false});
				// 		if(data.error==""){
				// 			$('.disc-persons-list-div').html(data.code);
				// 			$.each(data.items,function(code,opt){
				// 				$("#disc-person-rem-"+code).click(function(){
				// 					var lin = $(this).parent().parent();
				// 					$.post(baseUrl+'cashier/remove_person_disc/'+opt.disc+'/'+code,function(data){
				// 						lin.remove();
				// 						rMsg('Person Removed.','success');
				// 						transTotal();
				// 					});
				// 					return false;
				// 				});
				// 			});
				// 			transTotal();
				// 		}
				// 		else{
				// 			rMsg(data.error,'error');
				// 		}
				// },'json');
			}
    		return false;
    	// });	
			// alert('haha');
			// return false;
		});
	 	$(document).ready(function() {
		 	document.addEventListener('DOMContentLoaded', function() {
		    var calendarEl = document.getElementById('calendar');

		    // var calendar = new FullCalendar.Calendar(calendarEl, {
		    $('#calendar').fullCalendar({
		      headerToolbar: {
		        left: 'prevYear,prev,next,nextYear today',
		        center: 'title',
		        right: 'dayGridMonth,dayGridWeek,dayGridDay'
		      },
		      initialDate: '2020-09-12',
		      navLinks: true, // can click day/week names to navigate views
		      editable: true,
		      dayMaxEvents: true, // allow "more" link when too many events
		      events: [
		        {
		          title: 'All Day Event',
		          start: '2020-09-01'
		        },
		        {
		          title: 'Long Event',
		          start: '2020-09-07',
		          end: '2020-09-10'
		        },
		        {
		          groupId: 999,
		          title: 'Repeating Event',
		          start: '2020-09-09T16:00:00'
		        },
		        {
		          groupId: 999,
		          title: 'Repeating Event',
		          start: '2020-09-16T16:00:00'
		        },
		        {
		          title: 'Conference',
		          start: '2020-09-11',
		          end: '2020-09-13'
		        },
		        {
		          title: 'Meeting',
		          start: '2020-09-12T10:30:00',
		          end: '2020-09-12T12:30:00'
		        },
		        {
		          title: 'Lunch',
		          start: '2020-09-12T12:00:00'
		        },
		        {
		          title: 'Meeting',
		          start: '2020-09-12T14:30:00'
		        },
		        {
		          title: 'Happy Hour',
		          start: '2020-09-12T17:30:00'
		        },
		        {
		          title: 'Dinner',
		          start: '2020-09-12T20:00:00'
		        },
		        {
		          title: 'Birthday Party',
		          start: '2020-09-13T07:00:00'
		        },
		        {
		          title: 'Click for Google',
		          url: 'http://google.com/',
		          start: '2020-09-28'
		        }
		      ]
		    });

		    calendar.render();
		  });
	 // // alert('haha');	
	 // // return false;	
  //   	 // $('#datetime').datetimepicker();
		//  	$('#calendar-select').fullCalendar({});
     	});

     	function html_print(js_rcps){ 
			$.each(js_rcps, function(index, v) {
			   $.ajaxSetup({async: false});
			   $.post(baseUrl+'cashier/set_default_printer/'+v.printer,function(data){	
			   		// alert(JSON.stringify(value.printer));		   
					$('#print-rcp').html(v.value);
				});
			});
        	
        }

     <?php elseif($use_js == 'reserveTablesJs'): ?>
	    $('#guest-input').keypress(function(event){
          if(event.keyCode == 13){
           $('#guest-enter-btn').trigger('click');
          }
        });
		$('#exit-btn').click(function(){
			window.location = baseUrl+'cashier';
			return false;
		});
		$('#back-btn,#back-occ-btn').click(function(){
			loadDivs('select-table');
			return false;
		});
		$('#guest-enter-btn').click(function(){
			var type = $('#dine_type').val();
			var tbl = $('#select-table').attr('ref');
			var tbl_name = $('#select-table').attr('ref_name');
			// alert(tbl+'---'+tbl_name);
			// return false;
			var guest = $('#guest-input').val();
			var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
			if($.isNumeric(guest)){
				// $.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
					// window.location = baseUrl+'cashier/counter/'+type;
				// },'json');
				swal({
				  // title: 'Please confirm that the guest count is '+ guest +'.',
				  title: 'Do you want to take an order now?',
				  // text: "You won't be able to revert this!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes , I confirm!'
				},
				function(isConfirm){
					if(isConfirm){
						var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
						$.post(baseUrl+'wagon/add_to_wagon/trans_type_cart',formData,function(data){
							window.location = baseUrl+'cashier/rcounter/'+type;
						},'json');
					}else{
						var btn = $(this);
							btn.prop('disabled', true);
							var formData = 'type='+type+'&table='+tbl+'&table_name='+tbl_name+'&guest='+guest;
							$.post(baseUrl+'cashier/rsubmit_table_trans',formData,function(data){
								console.log(data);
								return false;
								if(data.error != null){
									rMsg(data.error,'error');
									btn.prop('disabled', false);
								}
								else{
									if(data.act == 'add'){
										newTransaction(false,data.type);
										if(btn.attr('id') == 'submit-btn'){
											rMsg('Success! Transaction Submitted.','success');
										}
										else{
											rMsg('Transaction Hold.','warning');
										}
										btn.prop('disabled', false);
									}
									else{
										newTransaction(true,data.type);
									}
								}
							// });
							},'json');
							return false;
						// return false;
					}
				});
			}
			else{
				rMsg('Invalid guest number.','error');
			}
			return false;
		});
		$('#start-new-btn').click(function(){
			loadDivs('no-guest');
			return false;
		});
		$.post(baseUrl+'cashier/get_branch_details',function(data){
			var img = data.layout;
			if(img != "" ){
				var img_real_width=0,
				    img_real_height=0;
				$("<img/>")
				.attr("src", img)
			    .attr("id", "image-layout")
			    .load(function(){
		           img_real_width = this.width;
		           img_real_height = this.height;
		           $(this).appendTo('#image-con');
		           $("<div/>")
				    .attr("class", "rtag")
				    .attr("id", "rtag-div")
				    .css("height", img_real_height)
				    .css("width", img_real_width)
				    .appendTo('#image-con');
					loadMarks();

				});
			}
		},'json');

		function newTransaction(redirect,type){
			$.post(baseUrl+'cashier/new_trans/true/'+type,function(data){
				// if(!redirect){
				// 	$('#trans-datetime').text(data.datetime);
				// 	var tp = data.type;
				// 	$('#trans-header').text(tp.toUpperCase());

				// 	$('.trans-lists').find('li').remove();
				// 	var cat_id = $(".category-btns:first").attr('ref');
				// 	var cat_name = $(".category-btns:first").text();
				// 	var val = {'name':cat_name};
				// 	loadsDiv('menus',cat_id,val,null);
				// 	transTotal();
				// 	$('.addon-texts').text('').hide();
				// 	if(type == 'retail')
				// 		remeload('retail');
				// 	if(type=='dinein')
				// 		window.location = baseUrl+'cashier/tables';
				// 	else if(type=='delivery')
				// 		window.location = baseUrl+'cashier/delivery';
				// 	else if(type=='pickup')
				// 		window.location = baseUrl+'cashier/pickup';
				// }
				// else{



					
					if(type=='dinein')
						window.location = baseUrl+'cashier/tables';
					else if(type=='delivery')
						window.location = baseUrl+'cashier/delivery';
					else if(type=='pickup')
						window.location = baseUrl+'cashier/pickup';
					else if(type=='eatigo')
						window.location = baseUrl+'cashier/tables/eatigo';
					else if(type=='bigdish')
						window.location = baseUrl+'cashier/tables/bigdish';
					else if(type=='zomato')
						window.location = baseUrl+'cashier/tables/zomato';
					else if(type=='reservation')
						window.location = baseUrl+'cashier';
					else{
						formData = 'type='+type;
				       	$.post(baseUrl+'cashier/check_tables',formData,function(data){
				       		// alert(data);
				       		// console.log(data);
				       		if(data.status){
								window.location = baseUrl+'cashier/tables_other/'+type;
				       		}
				       		else{
				       			// rMsg(data.error,'error');
								window.location = baseUrl+'cashier/counter/'+type;
				       		}
				       	},'json');	
				       	//old
						// window.location = baseUrl+'cashier/counter/'+data.type;
					}
				// }
			},'json');
		}
		function updateTblStatus(){
			$.post(baseUrl+'cashier/get_rtbl_status',{type:'reservation'},function(data){
				$.each(data,function(tbl_id,val){
					var mark = $('#mark-'+tbl_id);
					mark.removeClass('marker-green');
					mark.removeClass('marker-orange');
					mark.removeClass('marker-red');
					mark.addClass('marker-'+val.stat);
					mark.unbind('click');
					if(val.stat == 'green'){
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('no-guest');
			    					$('#guest-input').focus();
								}	
							},'json');	
						});
					}
					else{
						mark.click(function(){
							$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
								if(data.error == ""){
									$("#occ-num").text(val.name);
									$('#select-table').attr('ref',tbl_id);
									$('#select-table').attr('ref_name',val.name);
									loadDivs('occupied');
									get_table_orders(tbl_id);
								}else{
									rMsg(data.error,'error');
								}	
							},'json');	
						});
					}
				});
			},'json');	
			setTimeout(function(){
		  		updateTblStatus();
			}, 3000);	
		}	
		// checkOccupied();
		function loadMarks(){
			var type = $('#dine_type').val();
			// alert(type);
			$.post(baseUrl+'cashier/get_tables_other2/true/'+null+'/'+type,function(data){
				// alert(data);
				$.each(data,function(tbl_id,val){
					$('<a/>')
	    			.attr('href','#')
	    			// .attr('class','marker-red')
	    			.attr('class','markers marker-'+val.stat)
	    			.attr('id','mark-'+tbl_id)
	    			.attr('ref',tbl_id)
	    			.css('top',val.top+'px')
	    			.css('left',val.left+'px')
	    			.html('<h5 style="text-align:center;padding-top:7px;color:#fff">'+val.name+'</h5>')
	    			.appendTo('#rtag-div')
	    			.click(function(e){
	    				$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
		    				if(data.error == ""){
			    				if(val.stat == 'red'){
			    					$("#occ-num").text(val.name);
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('occupied');
			    					get_table_orders(tbl_id);

			    				}
			    				else{
			    					$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					loadDivs('no-guest');
			    					$('#guest-input').focus();
			    				}
		    				}
		    				else{
		    					rMsg(data.error,'error');
		    				}
	    				},'json');
	    				return false;
    				});
				});
				// checkOccupied();
				updateTblStatus();
			},'json');
			// });
		}
		function get_table_orders(tbl_id){
			$('.occ-orders-div').html('<br><center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
			$.post(baseUrl+'cashier/get_table_orders/true/'+tbl_id,function(data){
				$('.occ-orders-div').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						$('#order-btn-'+id).click(function(){

							//check if settled na
							$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
								if(data1 == 1){
									rMsg('This transaction has been settled.','error');
								}else{
									$.post(baseUrl+'cashier/check_tbl_activity/'+tbl_id,function(data){
										if(data.error == ""){
											window.location = baseUrl+'cashier/counter/'+val.type+'/'+id;
										}else{
											rMsg(data.error,'error');
										}	
									},'json');
								}
							});
							return false;
						});
						$("#transfer-btn-"+id).click(function(){

								$.callManager({
									success : function(data2){
										// alert('aw');
										$.post(baseUrl+'cashier/check_trans_settled/'+id,function(data1){
											// alert(id);
											if(data1 == 1){
												rMsg('This transaction has been settled.','error');
											}else{
												bootbox.dialog({
												  message: baseUrl+'cashier/transfer_tables/'+id,
												  // title: 'Somthing',
												  className: 'manager-call-pop',
												  buttons: {
												    submit: {
												      label: "Transfer",
												      className: "btn  pop-manage pop-manage-green",
												      callback: function() {
												        var sales_id = id;
												        var to_table = $('#to-table').val();
												       	$.post(baseUrl+'cashier/go_transfer_table/'+sales_id+'/'+to_table+'/'+data2.manager_username,function(data){
												       		// alert(data);
												       		if(data == ""){
												       			location.reload();
												       		}
												       	});
												        // return true;
												      }
												    },
												    cancel: {
												      label: "CANCEL",
												      className: "btn pop-manage pop-manage-red",
												      callback: function() {
												        // Example.show("uh oh, look out!");
												      }
												    }
												  }
												});
											}
										});

									}
								});

							return false;
						});
						$('#print-btn-'+id).click(function(){
							$.post(baseUrl+'cashier/print_sales_receipt/'+id,'',function(data){
								rMsg(data.msg,'success');
							},'json');
							return false;
						});	
						$('#print-os-btn-'+id).click(function(){
							$.post(baseUrl+'cashier/print_os/'+id+'/0/1','',function(data){
								rMsg('Order Slip Reprinted.','success');
							});
							return false;
						});	
					});
				}
			},'json');
		}
		function checkOccupied(){
			// alert('here');
			$.post(baseUrl+'cashier/check_occupied_tables',function(data){
				console.log(data);
				var occ = data.occ;
				var ucc = data.ucc;
				$.each(occ,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-green')){
						// alert(tbl['id']);
						mark.removeClass('marker-green');
						mark.addClass('marker-red');
						mark.unbind('click');
						mark.click(function(e){
							$("#occ-num").text(tbl['name']);
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('occupied');
							get_table_orders(tbl['id']);
							return false;
						});
					}
				});
				$.each(ucc,function(key,tbl){
					var mark = $('#mark-'+tbl['id']);
					if(mark.hasClass('marker-red')){
						// alert(tbl['id']);
						mark.removeClass('marker-red');
						mark.addClass('marker-green');
						mark.unbind('click');
						mark.click(function(e){
							$('#select-table').attr('ref',tbl['id']);
							$('#select-table').attr('ref_name',tbl['name']);
							loadDivs('no-guest');
							return false;
						});
					}
				});
			},'json');	
				// alert(data);
			// });
			setTimeout(function(){
		  		checkOccupied();
			}, 1000);	
		}
		function checkUnOccupied(){
		}
		function loadDivs(type){
			$('.loads-div').hide();
			$('.'+type+'-div').show();
		}
	<?php elseif($use_js == 'printerSetupJS'): ?>
    	$('#print-submit-btn').click(function(){
    		// alert('haha');return false;
            // alert(info.sales_id);
            // return false;
          var kitchen_printer = $("#kitchen_printer").val();
          var beverage_printer = $("#beverage_printer").val();
          var extra_printer = $("#extra_printer").val();
          $.post(baseUrl + "cashier/print_setup_edit_db",{'kitchen_printer':kitchen_printer ,'beverage_printer':beverage_printer,'extra_printer':extra_printer},function(data){
            // alert(data);
            // return false;
                rMsg('Printer setup change successfully!','success');
            // },'json');
          });
        });

    <?php elseif($use_js == 'printerSetupJS2'): ?>
    	$('#print-submit-btn').click(function(){
    		// alert('haha');return false;
            // alert(info.sales_id);
            // return false;
            // alert('aw');

          	// formData = '';
            // $('.printers').each(function()){
            // 	ref = $(this).attr('ref');
            // 	val = $(this).val();
            // 	formData = formData+ref+"="+val+"&";
            // }

            $('.printerss').each(function(){
            	ref = $(this).attr('ref');
            	val = $(this).val();
            	formData = "ref="+ref+"&val="+val;
            	// alert(formData);
          		$.post(baseUrl + "cashier/print_setup_update_db",formData,function(data){
          			// alert(data);
          			// $('#reprint-os-btn').click();
          		});
            })

            // alert(formData);
            // alert(data);
            // return false;
                // rMsg('Printer setup change successfully!','success');
            // },'json');
          
        });

        // $('#search-disc').

    <?php elseif($use_js == 'selBrandJs'): ?>
    	//for brand selection
        // $('.brand-btns').click(function(){
        // 	$('.brand-btns').attr('class','brand-btns btn-block cpanel-btn-orange btn btn-success');
        // 	brand = $(this).attr('brand');

        // 	// alert(brand);
        // 	$('#hid-brand').val(brand);

        // 	$(this).attr('class','brand-btns btn-block cpanel-btn-orange btn btn-danger');
        	
        // });

    <?php elseif($use_js == 'discountSettleJS'): ?>
    	$(document).on('click','.show_form',function(){
    		disc_id = $(this).attr('ref');
    		discode = $(this).attr('disc_code');
    		// alert(discode);
    		fix = $(this).attr('fix');
    		rrate = $(this).attr('rate');
    		name = $('#ndisc-cust-name').val("");
    		disc_code = $('#ndisc-cust-code').val("");
    		bday = $('#ndisc-cust-bday').val("");
    		$('#ndiscount-id').val(disc_id);
    		$('#cust-disc-code').val(discode);
    		// if(disc_id == 1){
    		// 	$("#div_form_disc_senior").show();
    		// 	$("#div_form_disc").hide();
    		// }else{
    		// 	$("#div_form_disc").show();
    		// 	$("#div_form_disc_senior").hide();
    		// }

    		if($('#guest_count').val() > 1){
    			// alert($('#guest_count').val());   

    	// 		swal({
					//   title: "Quantity",
					//   text: "Input quantity:",
					//   type: "input",
					//   showCancelButton: true,
					//   closeOnConfirm: false,
					//   animation: "slide-from-top",
					//   inputPlaceholder: "Input quantity",
					//   // inputClass: "swal-input"
					// },
					// function(inputValue){
					//   if (inputValue === null) return false;
					  
					//   if (inputValue === "" || !$.isNumeric(inputValue)) {
					//     swal.showInputError("Please input quantity");
					//     return false
					//   }
					  
					// });

					disc_qty = prompt("Please enter quantity", "1");
					running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) : 0;

					if(disc_qty == null){
						return false;
					}

					if (!$.isNumeric(disc_qty)) {
						alert('Invalid quantity.');
					}else if(parseInt(disc_qty) + running_count > $('#guest_count').val()){
						alert('Quantity should not more than guest count.');
					}else{
						$("#div_form_disc").show();
						$("#disc_count").val(disc_qty);
						$('#disc_ctr').val(0);
						$('#process').prop('disabled',true);
						$('#submit-discount-btn').prop('disabled',false);
					}
    		}else{
    			running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) : 0;
    			if(running_count == $('#guest_count').val()){
    				alert('Discount quantity should not more than guest count.');
    			}else{
    				$("#disc_count").val(1);
	    			$('#disc_ctr').val(0);
	    			$("#div_form_disc").show();
	    			$('#submit-discount-btn').prop('disabled',false);
    			}
    		}

    		if(fix == 1 && rrate == 0){
    			$("#opendiv").show();
    		}else{
    			$("#opendiv").hide();
    		}

    		$('.show_form').attr('class','btn-primary btn-block show_form btn');
    		$(this).attr('class','btn-success btn-block show_form btn');



    		///auto fill docus
			$('#ndisc-cust-name').keydown(function(){
				// ref = $(this).attr('refs');
					// dcode = $('#cust-disc-code').val();
					// alert(dcode);
					// alert(dcode);
					if(discode == 'SNDISC'){

				   		var opts = $('#seniors').find('option');
				   		var arr = [];
						opts.each(function(){
							// var val = $(this).attr('value');
							var val = "";
							var txt = $(this).text();
							arr.push({"code":val,"items":txt});
						});	
						

						$('#ndisc-cust-name').typeaheadmap({ "source":arr, 
						    "key": "items", 
						    "value": "code", 
						    "listener": function(k, v) {
						    				formData = 'val='+k+'&type=name';
						    				$.post(baseUrl+'cashier/get_customer_dets',formData,function(data){
									       		// alert(data);
									       		// console.log(data);
									       		$('#ndisc-cust-code').val(data);

									       	});
						    				// alert(v);
											// $('#cust-id').val(v);
											// $('#cust-item-drop-set').trigger("change");	
											//$('#'+ref).select();
										}		
						});


					}

			});

			$('#ndisc-cust-code').keydown(function(){
				// ref = $(this).attr('refs');
					// dcode = $('#cust-disc-code').val();
					// alert(dcode);
					// alert(dcode);
					// alert('aaaaa');
					if(discode == 'SNDISC'){
						// alert('aw');

				   		var opts = $('#ids_number').find('option');
				   		var arr = [];
						opts.each(function(){
							// var val = $(this).attr('value');
							var val = "";
							var txt = $(this).text();
							arr.push({"code":val,"items":txt});
						});	
						

						$('#ndisc-cust-code').typeaheadmap({ "source":arr, 
						    "key": "items", 
						    "value": "code", 
						    "listener": function(k, v) {
						    				formData = 'val='+k+'&type=id';
						    				$.post(baseUrl+'cashier/get_customer_dets',formData,function(data){
									       		// alert(data);
									       		// console.log(data);
									       		$('#ndisc-cust-name').val(data);

									       	});
						    				// alert(v);
											// $('#cust-id').val(v);
											// $('#cust-item-drop-set').trigger("change");	
											//$('#'+ref).select();
										}		
						});


					}

			});



    	});


  // 		/////auto fill docus
		// $('#ndisc-cust-name').keydown(function(){
		// 	// ref = $(this).attr('refs');
		// 		dcode = $('#cust-disc-code').val();
		// 		// alert(dcode);
		// 		// alert(dcode);

		// 		if(dcode == 'SNDISC'){

		// 	   		var opts = $('#seniors').find('option');
		// 	   		var arr = [];
		// 			opts.each(function(){
		// 				// var val = $(this).attr('value');
		// 				var val = "";
		// 				var txt = $(this).text();
		// 				arr.push({"code":val,"items":txt});
		// 			});	
					

		// 			$('#ndisc-cust-name').typeaheadmap({ "source":arr, 
		// 			    "key": "items", 
		// 			    "value": "code", 
		// 			    "listener": function(k, v) {
		// 			    				// alert(v);
		// 								// $('#cust-id').val(v);
		// 								// $('#cust-item-drop-set').trigger("change");	
		// 								//$('#'+ref).select();
		// 							}		
		// 			});


		// 		}

		// });

		// $("#ndisc-cust-name").typeaheadmap({ 
		// 	"key": "key", 
		//     "value": "value",
		// 	"source" : function(q, process) { 
		// 		ref = 1;
		// 		var formData = "key="+q+"&ref="+ref;
		// 	    $.post(baseUrl+'deposit/codes_json',formData,function(data){
		// 	    	alert(data);
		// 	    	// $('#comp-header').append(data);
		// 	    	// process(data);
		// 	    // },'json');
		// 	    });
		// 	},
		// 	"displayer" : function(that, item, highlighted) {
		//     	alert(highlighted);
		//     	// return highlighted  
		// 	} 
		// });


    	$('#cancel-disc-person-btn').click(function(){
    		$("#div_form_disc").hide();
    		$("#div_form_disc_senior").hide();
    		name = $('#ndisc-cust-name').val("");
    		disc_code = $('#ndisc-cust-code').val("");
    		bday = $('#ndisc-cust-bday').val("");
    		$('#ndiscount-id').val(null);
    	});
    	$('#cancel-sdisc-person-btn').click(function(){
    		$("#div_form_disc").hide();
    		$("#div_form_disc_senior").hide();
    		sname = $('#sndisc-cust-name').val("");
    		sdisc_code = $('#sndisc-cust-code').val("");
    		sbday = $('#sndisc-cust-bday').val("");
    		$('#ndiscount-id').val(null);
    	});

    	$('#submit-discount-btn').click(function(){
    		disc_id = $('#ndiscount-id').val();
    		name = $('#ndisc-cust-name').val();
    		disc_code = $('#ndisc-cust-code').val();
    		bday = $('#ndisc-cust-bday').val();
    		remarks = $('#ndisc-remark').val();
    		guest = $('#nguest').val();
    		total = $('#ntotal').val();
    		openamt = $('#ndisc-openamt').val();
    		// alert(name);
    		if(name){
    			// alert('sss');
    			formData = 'disc_id='+disc_id+'&name='+name+'&disc_code='+disc_code+'&bday='+bday+'&guest='+guest+'&total='+total+'&remark='+remarks+'&openamt='+openamt;
		       	$.post(baseUrl+'cashier/add_discount_session',formData,function(data){
		       		// alert(data);
		       		// console.log(data);
		       		// $('#added-disc-div').html(data.code);
		       		// $('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
		       		// $('#disc-total-span').html("<font size='4'>wala pa</font>");
		       		// alert(data);
		       		if(data == 'bigger'){
		       			// rMsg('Given discount amount is bigger that total payable. Please add more items','error');
		       			var conf = confirm('Given discount amount is bigger than total payable. Please add more items.');
					   	if(conf){
					      $('.bootbox-close-button').click();
					   	}
		       		}else if(data == 'addopenamt'){
		       			rMsg('Please input an Amount.','error');
		       		}else if(data == 'guestlimit'){
		       			rMsg('Person is in limit with the no of guest.','error');
		       		}else if(data == 'exist'){
		       			rMsg('Person already added.','error');
		       		}else{
			       		// $("#div_form_disc").hide();
			       		// $("#div_form_disc_senior").hide();
			    		name = $('#ndisc-cust-name').val("");
			    		disc_code = $('#ndisc-cust-code').val("");
			    		bday = $('#ndisc-cust-bday').val("");
			    		remarks = $('#ndisc-remark').val("");
			    		// $('#ndiscount-id').val(null);

			    		running_count = $('#running_count').val() != '' ? parseInt($('#running_count').val()) + 1 : 1;
			       		$('#running_count').val(running_count);

			       		disc_ctr = $('#disc_ctr').val() != '' ? parseInt($('#disc_ctr').val()) + 1 : 1;
			       		$('#disc_ctr').val(disc_ctr);

			       		disc_count = parseInt($('#disc_count').val());

			       		if(disc_count == disc_ctr){
			       			$('#submit-discount-btn').prop('disabled',true);
			       			$('#process').removeAttr('disabled');
			       			$("#div_form_disc").hide();
			       		}

			       		transTotal();
			       		computeDiscount();


		       		}
		       	// },'json');	
		       	});	
    		}else{
    			rMsg('Please input a Customer Name.','error');
    		}
    	});
    	$('#submit-sdiscount-btn').click(function(){
    		disc_id = $('#ndiscount-id').val();
    		name = $('#sndisc-cust-name').val();
    		disc_code = $('#sndisc-cust-code').val();
    		bday = $('#sndisc-cust-bday').val();
    		remarks = $('#sndisc-remark').val();
    		guest = $('#nguest').val();
    		total = $('#ntotal').val();
    		// alert(disc_code);
    		if(name){
    			formData = 'disc_id='+disc_id+'&name='+name+'&disc_code='+disc_code+'&bday='+bday+'&guest='+guest+'&total='+total+'&remark='+remarks;
		       	$.post(baseUrl+'cashier/add_discount_session',formData,function(data){
		       		// alert(data);
		       		// console.log(data);
		       		// $('#added-disc-div').html(data.code);
		       		// $('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
		       		// $('#disc-total-span').html("<font size='4'>wala pa</font>");
		       		if(data == 'bigger'){
		       			// rMsg('Given discount amount is bigger that total payable. Please add more items','error');
		       			var conf = confirm('Given discount amount is bigger than total payable. Please add more items.');
					   	if(conf){
					      $('.bootbox-close-button').click();
					   	}
		       		}else{
			       		$("#div_form_disc").hide();
			       		$("#div_form_disc_senior").hide();
			    		name = $('#sndisc-cust-name').val("");
			    		disc_code = $('#sndisc-cust-code').val("");
			    		bday = $('#sndisc-cust-bday').val("");
			    		remarks = $('#sndisc-remark').val("");
			    		$('#ndiscount-id').val(null);


			       		transTotal();
			       		computeDiscount();
		       		}
		       	// },'json');	
		       	});	
    		}else{
    			rMsg('Please input a Customer Name.','error');
    		}
    	});

    	function transTotal(){
			$.post(baseUrl+'cashier/total_trans',function(data){
				var total = data.total;
				var discount = data.discount;
				var local_tax = data.local_tax;
				$("#total-txt").number(total,2);
				$("#discount-txt").number(discount,2);
				if($("#local-tax-txt").exists()){
					$("#local-tax-txt").number(local_tax,2);
				}
				
				if(data.zero_rated > 0){
					$("#zero-rated-btn").removeClass('counter-btn-red');
					$("#zero-rated-btn").addClass('counter-btn-green');
					$("#zero-rated-btn").addClass('zero-rated-active');
					$('.center-div .foot .foot-det').css({'background-color':'#FDD017'});
					$('.center-div .foot .foot-det .receipt').css({'color':'#fff'});		
				}

			},'json');
			// 	alert(data);
			// 	console.log(data);
			// });
		}

		function computeDiscount(){
			formData = 'total='+total+'&guest='+guest;
			$.post(baseUrl+'cashier/compute_discount_right',formData,function(data){
				// alert(data.code);
				// console.log(data);
	       		$('#added-disc-div').html(data.code);
	       		$('#disc-count-span').html("<font size='4'>"+data.count_disc+"</font>");
	       		$('#disc-total-span').html("<font size='4'>"+data.total_discount.toFixed(2)+"</font>");
	       		$('#disc-lv-span').html("<font size='4'>"+data.total_lv.toFixed(2)+"</font>");

			},'json');
			// 	alert(data);
			// 	console.log(data);
			// });
		}

		$('#reset').click(function(){
			guest = $('#nguest').val();
    		total = $('#ntotal').val();
			$.post(baseUrl+'cashier/reset_discount_settle',formData,function(data){
				// console.log(data);
	       		// $('#added-disc-div').html("");
	       		// $('#disc-count-span').html("<font size='4'>0</font>");
	       		// $('#disc-total-span').html("<font size='4'>0.00</font>");
	       		// $('#disc-lv-span').html("<font size='4'>0.00</font>");
	       		transTotal();
	       		computeDiscount();
	       		$('#process').removeAttr('disabled');
			// },'json');
			// 	alert(data);
			// 	console.log(data);
			});
		});

		$('#ndisc-remark').click(function(){

			$.callDiscReasons({
				submit : function(reason){
					// console.log(reason);
					var free_reason = reason;
					// if(free_reason == ""){
					// 	rMsg('Please select/input a reason','error');
					// 	return false;
					// }else{
						var formData = 'free_reason='+free_reason;

						// $('body').goLoad2();
						$('#ndisc-remark').val(free_reason);
						// $.post(baseUrl+'cashier/update_free_menu/'+id,formData,function(data){
						// 	// console.log(data);
						// 	$('#trans-row-'+id+' .cost').text(0);
						// 	var text = $('#trans-row-'+id).find('.name').text();
						// 	// alert(text);
						// 	$('#trans-row-'+id+' .name').html('<i class="fa fa-asterisk"></i> '+text + " <br>&nbsp;&nbsp;&nbsp;<span class='label label-sm label-warning'>"+free_reason+"</span>");
						// 	transTotal();
						// 	rMsg('Updated Menu as free','success');
						// 	$('body').goLoad2({load:false});
						// });
					// }
					
					// $.post(baseUrl+'cashier/record_delete_line/'+cart+'/'+id+'/'+type+'/'+reason+'/'+man_id+'/'+man_user,function(data){
					// 	// alert(data);
					// 	$.post(baseUrl+'wagon/delete_to_wagon/'+cart+'/'+id,function(data){
					// 		sel.prev().addClass('selected');
					// 		sel.remove();
					// 		if(cart == 'trans_cart' && retail === false){
					// 			$.post(baseUrl+'cashier/delete_trans_menu_modifier/'+id,function(data){
					// 				var cat_id = $(".category-btns:first").attr('ref');
					// 				var cat_name = $(".category-btns:first").text();
					// 				var val = {'name':cat_name};
					// 				loadsDiv('menus',cat_id,val,null);
					// 				$('.trans-sub-row[trans-id="'+id+'"]').remove();
					// 			});
					// 		}
					// 		$('.counter-center .body').perfectScrollbar('update');
					// 		transTotal();
					// 	},'json');
					// });

				}
			});

		});

		$('#search-disc').on('keyup',function(){
    		
			val = $(this).val();

			$('#search-disc-div').html('');

			if(val == ""){
				$('.disc-buttons').show();
			}else{
				$('.disc-buttons').hide();

				var div = $('#search-disc-div');

				formData = "val="+val;

				$.post(baseUrl+'cashier/search_discs',formData,function(data){
	 				
	 				// alert(data);
	 				$.each(data.found,function(key,opt){

		 				// 	addClss = "";
		 				// 	// alert(opt.is_new);
		 				// 	if(opt.is_new == 'new'){
		 				// 		addClss = ' payment-btns';
		 				// 	}
		 				// 	// "style"=>'margin-bottom:10px;'
	 					var sCol = $('<div class="col-md-6" style="margin-bottom:10px;"></div>');
			 			$('<button/>')
			 			.attr({'id':'disc-'+key,'class':'btn-block show_form btn-primary btn','ref':key,'fix':opt.fix,'rate':opt.rate,'disc_code':opt.disc_code})
			 			.text(opt.text)
			 			.appendTo(sCol);

			 			sCol.appendTo(div);			 			


			 		});

		 		// });
		 		},'json');



			}


    	});

   //  	$('#search-disc')
   //      .keyboard({
   //          alwaysOpen: false,
   //          usePreview: false,
			// autoAccept : true,
			// change   : function(e, keyboard, el) { 
			// 	val = $('#search-disc').val();

			// 	$('#search-disc-div').html('');

			// 	if(val == ""){
			// 		$('.disc-buttons').show();
			// 	}else{
			// 		$('.disc-buttons').hide();

			// 		var div = $('#search-disc-div');

			// 		formData = "val="+val;

			// 		$.post(baseUrl+'cashier/search_discs',formData,function(data){
		 				
		 // 				// alert(data);
		 // 				$.each(data.found,function(key,opt){

			//  				// 	addClss = "";
			//  				// 	// alert(opt.is_new);
			//  				// 	if(opt.is_new == 'new'){
			//  				// 		addClss = ' payment-btns';
			//  				// 	}
			//  				// 	// "style"=>'margin-bottom:10px;'
		 // 					var sCol = $('<div class="col-md-6" style="margin-bottom:10px;"></div>');
			// 	 			$('<button/>')
			// 	 			.attr({'id':'disc-'+key,'class':'btn-block show_form btn-primary btn','ref':key,'fix':opt.fix,'rate':opt.rate,'disc_code':opt.disc_code})
			// 	 			.text(opt.text)
			// 	 			.appendTo(sCol);

			// 	 			sCol.appendTo(div);


			// 	 		});

			//  		// });
			//  		},'json');



			// 	}
				
			// 	return false;
			// },

   //      })
   //      .addNavigation({
   //          position   : [0,0],
   //          toggleMode : false,
   //          focusClass : 'hasFocus'
   //      });

   //      $('#ndisc-cust-code,#ndisc-cust-name,#ndisc-cust-bday,#ndisc-remark')
   //      .keyboard({
   //          alwaysOpen: false,
   //          usePreview: true,
			// autoAccept : true
   //      });

		$('#process').click(function(){
			$.post(baseUrl+'cashier/submit_trans',function(data){
												
				if(data.error != null){
					rMsg(data.error,'error');
					// btn.prop('disabled', false);
				}
				else{
					if(data.act == 'add'){
						// newTransaction(false,data.type);
						// if(btn.attr('id') == 'submit-btn'){
							rMsg('Success! Transaction Submitted.','success');
						// }
						// else{
						// 	rMsg('Transaction Hold.','warning');
						// }
						// btn.prop('disabled', false);
					}
					else{
						// newTransaction(true,data.type);
					}
					location.reload();
				}

				// transTotal();
   				
   				
			},'json').fail( function(xhr, textStatus, errorThrown) {
					   alert(xhr.responseText);
					});	
		});

	
	<?php endif; ?>

	setInterval(function(){
	<?php if(PRODUCT_KEY){ ?>
		check_pr_key();
	<?php } ?>		
	}, 3000);

	function check_pr_key(){
			$.post(baseUrl+'api/check_key',function(data){
				if(data == 1 || data == 2){
					window.location.href = baseUrl+"site/login";
				}
			});
		}
});
</script>