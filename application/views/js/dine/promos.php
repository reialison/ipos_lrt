<script>
$(document).ready(function(){
	<?php if($use_js == 'promoFreeListJs'): ?>
		var tbl_ref = $('table#main-tbl').attr('ref');
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#main-tbl').rTable({
			loadFrom	: 	 'promo/get_promo_free',
			noEdit		: 	 true,
			noAdd		:    tref,
			add			: 	 function(){
								goTo('promo/free_menu_form');
							 }				 				 	
		});
	<?php elseif($use_js == 'promoFreeFormJs'): ?>
		if($('#pf_id').val() != ""){
			initLinks();
		}
		$('#back-btn').click(function(){
			goTo('promo/free_menu');
			return false;
		});
		$('#save-btn').click(function(){
			var noError = $('#main-form').rOkay({
    			asJson			: 	false,
				btn_load		: 	null,
				goSubmit		: 	false,
				bnt_load_remove	: 	true
    		});
    		if(noError){
    			var count = $('.fmns').length;
    			if(count > 0){
    				var formData = $('#main-form').serialize();
    				$('body').goLoad2();
    				$.post(baseUrl+'promo/free_menu_db',formData,function(data){
    					goTo('promo/free_menu');
    					$('body').goLoad2({load:false});
    				});
    			}
    			else{
    				if($('.promo-option:checked').val()!=3){
    					rMsg('You need to add a free menu.','error');
    				}else{
    					var formData = $('#main-form').serialize();
    					$.post(baseUrl+'promo/free_menu_db',formData,function(data){
	    					goTo('promo/free_menu');
	    					$('body').goLoad2({load:false});
	    				});
    				}
    				
    			}
    		}
			return false;
		});
		$('#add-must-menu').click(function(){
			addMustMenu();
			return false;
		});
		$('#add-free-menu').click(function(){
			addFreeMenu();
			return false;
		});
		function addFreeMenu(){
			var mid = $("#free-menu").val();
			var divRow = $('#free-menus-div');
			if(mid != null){
				if($('#fmns-'+mid).exists()){
					rMsg('Menu is already Added.','error');
				}
				else{
					$.post(baseUrl+'menu/get_menus/'+mid,function(data){
						$.each(data.rows,function(key,td){
							var col = $('<div/>').attr({'class':'col-md-3 text-left fmns','id':'fmns-'+mid});
								var img = '<img src="'+baseUrl+'img/noimage.png" style="height:100%;width:100%">';
								if(td.hasOwnProperty('image')){
									var img = '<img src="'+baseUrl+td['image']+'" style="height:100%;width:100%">';
								}
								col.html('<div class="info-box">'+
										     '<span class="info-box-icon" style="line-height:0px">'+
										     	img+
										     '</span>'+
										     '<div class="info-box-content">'+
											     '<h5>'+td['title']+'</h5>'+
											     '<h5 style="font-size:12px;">'+td['subtitle']+'</h5>'+
											     '<div>'+
											     '<input type="hidden" value="'+mid+'" name="free_menus[]">'+
											     'Qty:<input type="text" name="free_qty[]" class="form-control input-smv free_qty" style="height:22px;padding:0px;padding-left:5px;padding-right:5px;text-align:right" value="1">'+

											     'Price:<input type="text" name="promo_amount[]" class="form-control input-sm promo_amount" style="height:22px;padding:0px;padding-left:5px;padding-right:5px;text-align:right" value="0">'+
											     '</div>'+
										     '</div>'+
									     '</div>');
								var del = $('<a href="#" class="btn btn-sm btn-danger btn-block" style="height:22px;padding:0px;margin-top:5px;"><i class="fa fa-times"></i> Remove</a>');
								del.click(function(){
									$('#fmns-'+mid).remove();
									return false;
								});
								col.find('.info-box-content').append(del);
							col.appendTo(divRow);
							$("#free-menu").val('').selectpicker('refresh');
							$('#add-free-menu').hide();
						});
					},'json').fail( function(xhr, textStatus, errorThrown) {
					   alert(xhr.responseText);
					});	
				}
			}
		}	
		function addMustMenu(){
			var mid = $("#must-menu").val();
			var ul = $('#must-menu-list');
			var id = parseFloat(ul.find('li').length)+1;
			if(mid != null){
				if($('#must-menu-'+mid).exists()){
					rMsg('Menu is already Added.','error');
				}
				else{
					var mname = $('#must-menu').find("option:selected").text();  
					var li = $('<li id="must-menu-'+mid+'"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">'+mname+'</span></li>');
					var qty = $('<div class="form-group"><input type="text" value="1" name="must_qty[]" class="  form-control numbers-only " style="text-align:right"></div>');
					var hidden = $('<input type="hidden" value="'+mid+'" name="must_menus[]">');
					var remove = $('<a href="#" class="del"><i class="fa fa-lg fa-times"></i></a>');
					remove.click(function(){
						$('#must-menu-'+mid).remove();
						return false;
					});
					li.append(hidden);					
					li.append(remove);
					li.append(qty);
					ul.append(li);
					$("#must-menu").val('').selectpicker('refresh');

					// $('#add-must-menu').hide();
				}	
			}
		}
		function initLinks(){
			$('.del').each(function(){
				$(this).click(function(){
					var id = $(this).attr('ref');
					$('#must-menu-'+id).remove();
					$('#add-must-menu').show();

					return false;
				})
			});
			$('.fdel').each(function(){
				$(this).click(function(){
					var id = $(this).attr('ref');
					$('#fmns-'+id).remove();

					$('#add-free-menu').show();

					return false;
				});
			});
		}

		$('.promo-option').click(function(){
			if($(this).val() == 1){
				$('.free_qty').val(1).prop('readonly','readonly');
				$('.option1').show();
				$('.option2').hide();
				$('.option3').hide();


				$('#menu-promo-div').show();
				$('#coupon-promo-div').hide();
			}else if($(this).val() == 2){
				$('#amount').val(0);
				$('#menu_category_id').val('').change();
				$('.free_qty').removeAttr('readonly');
				$('.option1').hide();
				$('.option2').show();
				$('.option3').hide();

				$('#menu-promo-div').show();
				$('#coupon-promo-div').hide();
			}else if($(this).val() == 3){
				$('#menu-promo-div').hide();
				$('#coupon-promo-div').show();

				$('.option1').hide();
				$('.option2').hide();
				$('.option3').show();
			}
		});

		$(document).on('blur','.promo_amount',function(){
			if($(this).val() > 0){
				$('.free_qty').val(1).prop('readonly','readonly');
			}else{
				$('.free_qty').removeAttr('readonly');
			}
		});
	<?php elseif($use_js == 'promosJs'): ?>
		var tbl_ref = $('table#promos-tbl').attr('ref');
	// alert('haha');return false;
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#promos-tbl').rTable({
			loadFrom	: 	 'settings/get_promos',
			noEdit		: 	 true,
			noAdd		: 	 tref,
			add			: 	 function(){goTo('settings/promo_details_load')},
			edit		: 	 function(id){goTo('settings/promo_details_load/'+id);},
			noBtn1 		:   true,
			btn1Txt		: 	"<i class='fa fa-upload '></i> Upload",				 				 	
			btn1 		: 	function(data){
								bootbox.dialog({
								  message: baseUrl+'user/upload_excel_form',
								  title: "Select Excel Menu File",
								  buttons: {
								    submit: {
								      label: "<i class='fa fa-check'></i> Upload",
								      className: "btn-success rFormSubmitBtn",
								      callback: function() {
								      		var noError = $('#upload-form').rOkay({
								      			goSubmit 	: 	false
								      		});
								      		if(noError){
								      			$.loadPage();
								      			$('#upload-form').submit();
								      		}
								      		return false;
								      }
								    },
								    close:{
								    	label: "Close",
								    	className: "btn-default",
								    	callback: function() {
								    			return true;
								    	}
								    }
								  }
								});
							},
			noBtn2 		:   true,
			btn2Txt		: 	"<i class='fa fa-download '></i> Download",				 				 	
			btn2 		: 	function(data){
								window.location.href = baseUrl + "setup/download_printer";
							},
			afterLoad 	    : 	 function(){
								var table = $('#promos-tbl');
						        var oTable = table.dataTable({

						            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
						            "language": {
						                "aria": {
						                    "sortAscending": ": activate to sort column ascending",
						                    "sortDescending": ": activate to sort column descending"
						                },
						                "emptyTable": "No data available in table",
						                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
						                "infoEmpty": "No entries found",
						                "infoFiltered": "(filtered1 from _MAX_ total entries)",
						                "lengthMenu": "_MENU_ entries",
						                "search": "Search:",
						                "zeroRecords": "No matching records found"
						            },

						            // Or you can use remote translation file
						            //"language": {
						            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
						            //},


						            buttons: [
						                { extend: 'print', className: 'btn dark btn-outline'},
						                { extend: 'copy', className: 'btn red btn-outline' },
						                { extend: 'pdf', className: 'btn green btn-outline' },
						                { extend: 'excel', className: 'btn yellow btn-outline ' },
						                { extend: 'csv', className: 'btn purple btn-outline ' },
						                { extend: 'colvis', className: 'btn dark btn-outline', text: 'Columns'}
						            ],

						            // setup responsive extension: http://datatables.net/extensions/responsive/
						            responsive: true,

						            //"ordering": false, disable column ordering 
						            //"paging": false, disable pagination

						            "order": [
						                [0, 'asc']
						            ],
						            
						            "lengthMenu": [
						                [5, 10, 15, 20, -1],
						                [5, 10, 15, 20, "All"] // change per page values here
						            ],
						            // set the initial value
						            "pageLength": 10,

						            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

						            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
						            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
						            // So when dropdowns used the scrollable div should be removed. 
						            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
						        });
								// $('#users-tbl').dataTable();
				 }						 	
		});
		loader('#details_link');

		$(".timepicker").timepicker({
		    showInputs: false
		});
		$('#add-new-promo').click(function(){
			// var vald = $('#promo_id').val();

			// if(vald == '')	$('#save-btn').trigger('click');
			$('#promo-drop option:first-child').attr("selected", "selected");
			loader('#details_link');
		});

		$('#promo-drop').change(function(){
			var sel = $(this).val();
			if(sel == ''){
				$('#add-new-promo').trigger('click');
				$('#add-new-promo').removeClass('disabled');
				$('#add-schedule').addClass('disabled');
				$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			}else{
				$('#add-new-promo').addClass('disabled');
				$('#add-schedule').addClass('disabled');
				$('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			}

		});

		$('.tab_link').click(function(){
				var id = $(this).attr('id');
				loader('#'+id);
			});

		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var sel = $('#promo-drop').val();

			if(sel == ""){
				sel = 'add';
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
				// $('#assign').removeClass('disabled');
				// $('#add-new-promo').removeClass('disabled');
				// alert('zxc');
			}
			else{
				// $('#assign').addClass('disabled');
				// $('#add-new-promo').addClass('disabled');
				disEnbleTabs('.load-tab',true);
			}
			var res_id = $('#res_id').val();
			$(tabPane).rLoad({url:baseUrl+loadUrl+'/'+sel});
		}
		function disEnbleTabs(id,enable){
			if(enable){
				$(id).parent().removeClass('disabled');
				$(id).removeAttr('disabled','disabled');
				$(id).attr('data-toggle','tab');
			}
			else{
				$(id).parent().addClass('disabled');
				$(id).attr('disabled','disabled');
				$(id).removeAttr('data-toggle','tab');
			}
		}
	<?php elseif($use_js == 'promoDetailsJs'): ?>
		var promo = $('#promo-drop').val();
		if(promo == '')
			$('#add-schedule').addClass('disabled');

		$('.del-sched').each(function(){
			var id = $(this).attr('ref');
			deleteSched(id);
		});
		function deleteSched(id){
			$('#del-sched-'+id).click(function(){
				// alert(id);
				var formData = 'pr_sched_id='+id;
				var li = $(this).parent().parent();
				// alert(baseUrl+'settings/remove_promo_details');
				$.post(baseUrl+'settings/remove_promo_details',formData,function(data){
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#staff-list li').length;
					if(noLi == 0){
						$('ul#staff-list').append('<li class="no-staff"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Staffs found.</span></li>');
					}
					// });
				},'json');
				return false;
			});
		}

		$(".timepicker").timepicker({
                    showInputs: false,
                    minuteStep: 1
                });
		$('#promo_code').keyup(function() {
			var validation_promo = $('#promo_code').val();
            		if (this.value.match(/[^a-zA-Z0-9]/g)) {
               			$('#save-btn').attr('disabled','disabled');
                		rMsg('Please no special characters in the text field','warning');
				    }else{
				    	$('#save-btn').removeAttr('disabled');
				    }
		   		});
		$('#save-btn').click(function(){
			$("#promo-form").rOkay({
				btn_load		: 	$('#save-btn'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(typeof data.msg != 'undefined' ){
											// var sel = $('#promo-drop').val();
											// $('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
											// rMsg(data.msg,'success');
											$('#save-btn').attr('disabled','disabled');
											rMsg(data.msg,'success');
											setTimeout(function() {
											      // Do something after 2 seconds
												window.location = baseUrl+'settings/promos';
											}, 1000);
										}
									}
			});
			return false;
		});
		$('#add-schedule').click(function(){
			$("#promo-details-form").rOkay({
				btn_load		: 	$('#add-schedule'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										// alert(data.msg);
										if(data.msg == 'error'){
											msg = 'Day already duplicated in this promo.';
											rMsg(msg,'error');
										}
										else if(typeof data.msg != 'undefined' ){
											var sel = $('#promo-drop').val();
											// $('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
											location.reload();
											msg = 'Schedule has been added.';
											rMsg(msg,'success');

										}
									}
			});
			return false;
		});

		$('.dels').click(function(){
			ref = $(this).attr('ref');

			formData = 'ref='+ref;
			$.post(baseUrl+'settings/remove_schedule',formData,function(data){
				
				rMsg('Schedule has been deleted.','success');
				var sel = $('#promo-drop').val();
				location.reload();
				// $('#details').rLoad({url:baseUrl+'settings/promo_details_load/'+sel});
			});
		});
	<?php elseif($use_js == 'assignedItemPromoJs'): ?>
		$('.del-item').each(function(){
			var id = $(this).attr('ref');
			deleteSched(id);
		});

		function deleteSched(id){
			$('#del-item-'+id).click(function(){
				//alert(id);
				var formData = 'pr_item_id='+id;
				var li = $(this).parent();
				// alert(baseUrl+'settings/remove_promo_details');
				$.post(baseUrl+'settings/remove_promo_items',formData,function(data){
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#item-list li').length;
					if(noLi == 0){
						$('ul#item-list').append('<li class="no-item"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Item found.</span></li>');
					}
					// });
				},'json');
				return false;
			});
		}
		$('#add-item').click(function(){
			$("#assignedItem_form").rOkay({
				btn_load		: 	$('#add-item'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										//alert(data);
										if(data.msg == 'error'){
											msg = 'Item already duplicated in this promo.';
											rMsg(msg,'error');
										}
										else if(typeof data.msg != 'undefined' ){
											var sel = $('#promo-drop').val();
											$('#assign').rLoad({url:baseUrl+'settings/assign_load/'+sel});
											msg = 'Item successfully added.';
											rMsg(msg,'success');
										}
									}
			});
			return false;
		});
		$('.del-staff').click(function(){
			ref = $(this).attr('ref');
			formData = 'ref='+ref;
			$.post(baseUrl+'settings/remove_item_assign',formData,function(data){
				
				rMsg('Item has been deleted.','success');
				var sel = $('#promo-drop').val();
				$('#assign').rLoad({url:baseUrl+'settings/assign_load/'+sel});
			});
			// },'json');

		});

		$('.edit-staff').click(function(){
			$(this).parents('li').find('input[type="text"]').removeAttr('readonly');
			$(this).parents('li').find('.update-staff').removeClass('hidden');
			$(this).addClass('hidden');	

			return false;		
		});

		$('.update-staff').click(function(){
			ref = $(this).attr('ref');
			promo_qty = $(this).parents('li').find('#promo_qty').val();
			disc_qty = $(this).parents('li').find('#disc_qty').val();
			formData = 'ref='+ref+'&promo_qty='+promo_qty+'&disc_qty='+disc_qty;
			$.post(baseUrl+'settings/update_item_assign',formData,function(data){
				
				rMsg('Item has been updated.','success');
				var sel = $('#promo-drop').val();
				$('#assign').rLoad({url:baseUrl+'settings/assign_load/'+sel});
			});
			// },'json');

		});

	<?php endif; ?>
});
</script>