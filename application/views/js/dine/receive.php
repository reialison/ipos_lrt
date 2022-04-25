<script>
$(document).ready(function(){        
	<?php if($use_js == 'receiveListJs'): ?>
		$(document).on('click','#receiving_template',function(e){
				e.preventDefault();
				// console.log('testx');
				window.location = baseUrl+'receiving/download_template';
				// $.get(baseUrl+'gift_cards/download_template',function(){

				// });
			});

		$('#main-tbl').rTable({
			loadFrom	: 	 'receiving/get_receiving',
			noEdit		: 	 true,
			add			: 	 function(){
								goTo('receiving/form');
							 },
			noBtn1 		:   false,
			btn1Txt		: 	"<i class='fa fa-upload '></i> Upload",				 				 	
			btn1 		: 	function(data){
								bootbox.dialog({
								  message: baseUrl+'receiving/upload_excel_form',
								  title: "Select Excel Receiving File",
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
			afterLoad 	: 	 function(){
								$('.void').each(function(){
									$(this).click(function(){
										var id = $(this).attr('ref');
										var title = $(this).attr('title');
										$.rPopForm({
											loadUrl			:	"trans/void_form/"+id,
											passTo			:	"receiving/void/"+id,
											title			:	title,
											rform			:	"main-form",
											onComplete		:	function(response){
																	window.location.reload();
																},
										});
										return false;
									});
								});

								$('.view').rPopView({
									wide : true,
									asJson : true,
									onComplete: function(data)
									{
									//$('[data-bb-handler=cancel]').click();
									}
								});

								var table = $('#main-tbl');
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
								// $('#main-tbl').dataTable();
							 }
		});
	<?php elseif($use_js == 'receiveJs'): ?>

		if(<?=DR_LOOKUP?>){
			console.log('asd');
			$('#reference_icon').on('click',function(e){
				var ref = $('input#reference').val();

				// console.log(ref);
				swal({
				  title: "",
				  text: "Do you want to check available transaction with this reference number?",
				  type: "warning",
				  showCancelButton: true,
		
				  cancelButtonText: 'No',
				  confirmButtonClass: "btn-danger",
				  confirmButtonText: "YES, PROCEED",
				  closeOnConfirm: false,
				  showLoaderOnConfirm: true
				},
				function(){
					$.post(baseUrl+'store_order/get_dr_details',{'ajax':true,'ref':ref},function(e){
						console.log(e);
						var resp = JSON.parse(e);
						if(resp.item_ctr > 0){	
							 set_item_cart_from_trans(resp.item_details);
							 $('form#add_item_form').find('input, select, button').each(function(i,e){
							 	$(e).attr('disabled','disabled');
							 });
							 swal("Transaction found!",'Items were loaded..','success');

						}else{

						 		 swal("", "No transaction associated with the reference number", "success");
						}

					});
					// swal('',)

				});
			});
		}
		$(".timepicker").timepicker({
		    showInputs: false
		});

		$('#save-btn').click(function(){
			$("#receive_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(data.error != ""){
											rMsg(data.msg,'error');
										}else{
											window.location.reload();
										}
									}
			});
			return false;
		});
		$('#back-btn').click(function(){
			goTo('receiving');
			return false;
		});	
		// $('#item-search').typeaheadmap({
		// 	"source": function(search, process) {
		// 		var url = $('#item-search').attr('search-url');
		// 		var formData = 'search='+search;
		// 		$.post(baseUrl+url,formData,function(data){
		// 			process(data);
		// 		},'json');
		// 	},
		//     "key": "key",
		//     "value": "value",
		//     "listener": function(k, v) {
		// 		set_item_details(v);
		// 	}
		// });
		$('#item-search').change(function(){
			var item = $(this).val();
			set_item_details(item);
		});
		function set_item_details(id){
			$.post(baseUrl+'receiving/get_item_details/'+id,function(data){
				$('#item-id').val(data.item_id);
				$('#item-uom').val(data.uom);
				$('#select-uom').find('option').remove();
				$.each(data.opts,function(key,val){
					$('#select-uom').append($("<option/>", {
				        value: val,
				        text: key
				    }));
				});
				$('#item-ppack').val(data.ppack);
				$('#item-ppack-uom').val(data.ppack_uom);
				$('#item-pcase').val(data.pcase);
			},'json');
		}
		$('#add-item-btn').click(function(){
			var noError = $('#add_item_form').rOkay({
				btn_load	: 	$('#add-item-btn'),
				goSubmit	: 	false,
				bnt_load_remove	: 	true
			});
			if(noError){
				var formData = $("#add_item_form").serialize();
				console.log(formData);
				$.post(baseUrl+'wagon/add_to_wagon/rec_cart',formData,function(data){
					console.log(data);
					var row = data.items;
					var id = data.id;
					var tr = $("<tr/>").attr('id','row-'+id);
					$.each(row,function(key,val){
						if(key == 'item-search' || key == 'qty' || key == 'select-uom' || key == 'cost'){
							if(key == 'item-search'){
								var selectedText = $('#item-search').find("option:selected").text();
							    tr.append($("<td/>", {text: selectedText}));
							}	
							else if(key == 'qty' || key == 'cost'){
							    tr.append($("<td/>", {text: $.number(val,2)}));
							}	
							else if(key == 'select-uom'){
								var txt = splitUom(val);
								tr.append($("<td/>", {text: txt}));
							}
							else
								tr.append($("<td/>", {text: val}));
						}
					});
					var link = $('<a/>')
						.attr('id','del-'+id)
						.attr('class','del')
						.attr('href','#')
						.html('<i class="fa fa-trash-o fa-lg fa-fw"></i>');
					tr.append($("<td/>",{html:link}));
				    $('#details-tbl').append(tr);
				    $('#add_item_form').find("input[type=text], textarea").val("");
				    $("#item-search").val('').selectpicker('refresh');
				    $('#select-uom').find('option').remove();
				    deleteRow(id);
				    total();
				},'json');
			}
			return false;
		});
		function deleteRow(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'wagon/delete_to_wagon/rec_cart/'+id,function(data){
					$('#row-'+id).remove();
					total();
				},'json');
				return false;
			});
		}
		function total(){
			$.post(baseUrl+'wagon/get_wagon/rec_cart/',function(data){
				var items = data.items;
				var count = items.length;
				if(count > 0){
					$('.no-items-row').hide();
					var total = 0;
					$.each(items,function(line_id,row){
						total += parseFloat(row.qty) * parseFloat(row.cost);
					});
					$('#grand-total').number(total,2);
				}
				else{
					$('.no-items-row').show();
					$('#grand-total').number(0,2);
				}				
			},'json').fail( function(xhr, textStatus, errorThrown) {
			   alert(xhr.responseText);
			});	
		}
		function splitUom(txt){
			var txt = txt.split('-');
			var line = "";
			if(1 in txt){
				if(txt[1] == 'pack'){					
					line = $("#item-ppack-uom").val()+'(@'+txt[2]+' '+txt[0]+')';
				}
				else{
					line = 'Case(@'+txt[2]+' Packs)';
				}
			}
			else
				line = txt[0];
			return line;
		}

		function set_item_cart_from_trans(item_details){
			// item-search=3&item-id=3&item-uom=pc&item-ppack=0&item-ppack-uom=&item-pcase=0&qty=1&select-uom=pc&loc_id=1-warehouse
			var ctr = item_details.length;

			console.log(ctr);
			// var i;
			// for(i=0; i<ctr; i++){

			// }
			$.each(item_details,function(i,e){
				// console.log(e);
				var ctr = i+1;
				var formData = "item-search="+e.item_code+"&item-id="+e.item_code+"&item-uom="+e.uom+"&item-ppack=0&item-ppack-uom=&item-pcase=0&qty="+e.qty_sent+"&select-uom="+e.uom+"&loc_id=1-warehouse&item_name="+e.item_name;
				// console.log(formData);
				// setTimeout(function(){

					$.post(baseUrl+'wagon/add_to_wagon/rec_cart/'+ctr,formData,function(data){
						console.log('pre: ');
						console.log(data);
							var row = data.items;
							var id = data.id;
							var tr = $("<tr/>").attr('id','row-'+id);
							$.each(row,function(key,val){
								console.log();
								if(key == 'item-search' || key == 'qty' || key == 'select-uom' || key == 'cost'){
									if(key == 'item-search'){
										var selectedText = data.items.item_name;//$('#item-search').find("option:selected").text();
									    tr.append($("<td/>", {text: selectedText}));
									}	
									else if(key == 'qty' || key == 'cost'){
									    tr.append($("<td/>", {text: $.number(val,2)}));
									}	
									else if(key == 'select-uom'){
										var txt = splitUom(val);
										tr.append($("<td/>", {text: txt}));
									}
									else
										tr.append($("<td/>", {text: val}));
								}
							});
							var link = $('<a/>')
								.attr('id','del-'+id)
								.attr('class','del')
								.attr('href','#')
								.html('<i class="fa fa-trash-o fa-lg fa-fw"></i>');
							tr.append($("<td/>",{html:link}));
						    $('#details-tbl').append(tr);
						    $('#add_item_form').find("input[type=text], textarea").val("");
						    $("#item-search").val('').selectpicker('refresh');
						    $('#select-uom').find('option').remove();
						    deleteRow(id);
						    total();
					},'json');
				// },1000);
			})

			// return true;
		}

	<?php elseif($use_js == 'receiveMenuListJs'): ?>
		$(document).on('click','#receiving_template',function(e){
			e.preventDefault();
			window.location = baseUrl+'receiving/download_menu_template';		
		});

		$('#main-tbl').rTable({
			loadFrom	: 	 'receiving_menu/get_receiving_menu',
			noEdit		: 	 true,
			add			: 	 function(){
								goTo('receiving_menu/form_menu');
							 },
			noBtn1 		:   false,
			btn1Txt		: 	"<i class='fa fa-upload '></i> Upload",				 				 	
			btn1 		: 	function(data){
								bootbox.dialog({
								  message: baseUrl+'receiving_menu/upload_menu_form',
								  title: "Select Excel Receiving File",
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
			afterLoad 	: 	 function(){
								$('.void').each(function(){
									$(this).click(function(){
										var id = $(this).attr('ref');
										var title = $(this).attr('title');
										$.rPopForm({
											loadUrl			:	"trans/void_form/"+id,
											passTo			:	"receiving_menu/void_menu/"+id,
											title			:	title,
											rform			:	"main-form",
											onComplete		:	function(response){
																	window.location.reload();
																},
										});
										return false;
									});
								});

								$('.view').rPopView({
									wide : true,
									asJson : true,
									onComplete: function(data)
									{
									//$('[data-bb-handler=cancel]').click();
									}
								});

								var table = $('#main-tbl');
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
								// $('#main-tbl').dataTable();
							 }			 				 	
		});

	<?php elseif($use_js == 'receiveMenuJs'): ?>
		// $('.datepicker').datepicker({format:'yyyy-mm-dd h:mm A'});
		$(".timepicker").timepicker({
		    showInputs: false
		});

		$('#save-btn').click(function(){
			$("#receive_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(data.error != ""){
											rMsg(data.msg,'error');
										}else{
											window.location.reload();
										}
									}
			});
			return false;
		});
		$('#back-btn').click(function(){
			goTo('receiving_menu');
			return false;
		});	
		// $('#item-search').typeaheadmap({
		// 	"source": function(search, process) {
		// 		var url = $('#item-search').attr('search-url');
		// 		var formData = 'search='+search;
		// 		$.post(baseUrl+url,formData,function(data){
		// 			process(data);
		// 		},'json');
		// 	},
		//     "key": "key",
		//     "value": "value",
		//     "listener": function(k, v) {
		// 		set_item_details(v);
		// 	}
		// });
		$('#menu-search').change(function(){
			var item = $(this).val();
			// set_item_details(item);
		});
		function set_item_details(id){
			$.post(baseUrl+'receiving/get_item_details/'+id,function(data){
				$('#item-id').val(data.item_id);
				$('#item-uom').val(data.uom);
				$('#select-uom').find('option').remove();
				$.each(data.opts,function(key,val){
					$('#select-uom').append($("<option/>", {
				        value: val,
				        text: key
				    }));
				});
				$('#item-ppack').val(data.ppack);
				$('#item-ppack-uom').val(data.ppack_uom);
				$('#item-pcase').val(data.pcase);
			},'json');
		}
		$('#add-item-btn').click(function(){
			// alert('aw');
			// if()
			var st = $('#menu-search').find("option:selected").text();
			if(st == ""){
				rMsg('Please select an item.','error');
			}else{

				var noError = $('#add_menu_form').rOkay({
					btn_load	: 	$('#add-item-btn'),
					goSubmit	: 	false,
					bnt_load_remove	: 	true
				});
				if(noError){
					var formData = $("#add_menu_form").serialize();
					$.post(baseUrl+'wagon/add_to_wagon/rec_cart',formData,function(data){
						var row = data.items;
						var id = data.id;
						var tr = $("<tr/>").attr('id','row-'+id);
						$.each(row,function(key,val){
							if(key == 'menu-search' || key == 'qty' || key == 'select-uom' || key == 'cost'){
								if(key == 'menu-search'){
									var selectedText = $('#menu-search').find("option:selected").text();
								    tr.append($("<td/>", {text: selectedText}));
								}	
								else if(key == 'qty' || key == 'cost'){
								    tr.append($("<td/>", {text: $.number(val,2)}));
								}	
								// else if(key == 'select-uom'){
								// 	var txt = splitUom(val);
								// 	tr.append($("<td/>", {text: txt}));
								// }
								else
									tr.append($("<td/>", {text: val}));
							}
						});
						var link = $('<a/>')
							.attr('id','del-'+id)
							.attr('class','del')
							.attr('href','#')
							.html('<i class="fa fa-trash-o fa-lg fa-fw"></i>');
						tr.append($("<td/>",{html:link}));
					    $('#details-tbl').append(tr);
					    $('#add_item_form').find("input[type=text], textarea").val("");
					    $("#menu-search").val('').selectpicker('refresh');
					    // $('#select-uom').find('option').remove();
					    deleteRow(id);
					    total();
					},'json');
				}

			}

			
			return false;
		});
		function deleteRow(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'wagon/delete_to_wagon/rec_cart/'+id,function(data){
					$('#row-'+id).remove();
					total();
				},'json');
				return false;
			});
		}
		function total(){
			$.post(baseUrl+'wagon/get_wagon/rec_cart/',function(data){
				var items = data.items;
				var count = items.length;
				if(count > 0){
					$('.no-items-row').hide();
					var total = 0;
					$.each(items,function(line_id,row){
						total += parseFloat(row.qty) * parseFloat(row.cost);
					});
					$('#grand-total').number(total,2);
				}
				else{
					$('.no-items-row').show();
					$('#grand-total').number(0,2);
				}				
			},'json').fail( function(xhr, textStatus, errorThrown) {
			   alert(xhr.responseText);
			});	
		}
		function splitUom(txt){
			var txt = txt.split('-');
			var line = "";
			if(1 in txt){
				if(txt[1] == 'pack'){					
					line = $("#item-ppack-uom").val()+'(@'+txt[2]+' '+txt[0]+')';
				}
				else{
					line = 'Case(@'+txt[2]+' Packs)';
				}
			}
			else
				line = txt[0];
			return line;
		}

	<?php elseif($use_js == 'receiveDetJs'): ?>

		$('#printPreview').click(function(){
			var rec_id = $(this).attr('ref');
			// alert(rec_id);

			var formData = 'rec_id='+rec_id;
			window.open(baseUrl+"receiving/receiving_pdf?"+formData, "_blank");
			
			
			// if($('#calendar_range').val() == ""){
			// 	rMsg('Enter Date Range','error');
			// }
			// else{
			// 	$.rProgressBar();				
			// }

			return false;
		});

		$('#printExcel').click(function(){
			// alert('aw');
			var rec_id = $(this).attr('ref');
			var formData = 'rec_id='+rec_id;
			var href = baseUrl+"receiving/receiving_excel";
			window.location = href+'?'+formData;
			return false;
		});

	<?php elseif($use_js == 'receiveDetMenuJs'): ?>

		$('#printPreview').click(function(){
			var rec_id = $(this).attr('ref');
			// alert(rec_id);

			var formData = 'rec_id='+rec_id;
			window.open(baseUrl+"receiving/receiving_menu_pdf?"+formData, "_blank");
			
			
			// if($('#calendar_range').val() == ""){
			// 	rMsg('Enter Date Range','error');
			// }
			// else{
			// 	$.rProgressBar();				
			// }

			return false;
		});

		$('#printExcel').click(function(){
			// alert('aw');
			var rec_id = $(this).attr('ref');
			var formData = 'rec_id='+rec_id;
			var href = baseUrl+"receiving/receiving_menu_excel";
			window.location = href+'?'+formData;
			return false;
		});

	<?php elseif($use_js == 'adjustMenuListJs'): ?>
		$(document).on('click','#receiving_template',function(e){
			e.preventDefault();
			window.location = baseUrl+'receiving/download_menu_template';		
		});

		$('#main-tbl').rTable({
			loadFrom	: 	 'receiving/get_adjustment_menu',
			noEdit		: 	 true,
			add			: 	 function(){
								goTo('receiving/form_menu_adjustment');
							 },
			// noBtn1 		:   false,
			// btn1Txt		: 	"<i class='fa fa-upload '></i> Upload",				 				 	
			// btn1 		: 	function(data){
			// 					bootbox.dialog({
			// 					  message: baseUrl+'receiving_menu/upload_menu_form',
			// 					  title: "Select Excel Receiving File",
			// 					  buttons: {
			// 					    submit: {
			// 					      label: "<i class='fa fa-check'></i> Upload",
			// 					      className: "btn-success rFormSubmitBtn",
			// 					      callback: function() {
			// 					      		var noError = $('#upload-form').rOkay({
			// 					      			goSubmit 	: 	false
			// 					      		});
			// 					      		if(noError){
			// 					      			$.loadPage();
			// 					      			$('#upload-form').submit();
			// 					      		}
			// 					      		return false;
			// 					      }
			// 					    },
			// 					    close:{
			// 					    	label: "Close",
			// 					    	className: "btn-default",
			// 					    	callback: function() {
			// 					    			return true;
			// 					    	}
			// 					    }
			// 					  }
			// 					});
			// 				},
			afterLoad 	: 	 function(){
								$('.void').each(function(){
									$(this).click(function(){
										var id = $(this).attr('ref');
										var title = $(this).attr('title');
										$.rPopForm({
											loadUrl			:	"trans/void_form/"+id,
											passTo			:	"receiving/void_menu_adjustment/"+id,
											title			:	title,
											rform			:	"main-form",
											onComplete		:	function(response){
																	window.location.reload();
																},
										});
										return false;
									});
								});

								$('.view').rPopView({
									wide : true,
									asJson : true,
									onComplete: function(data)
									{
									//$('[data-bb-handler=cancel]').click();
									}
								});

								var table = $('#main-tbl');
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
								// $('#main-tbl').dataTable();
							 }			 				 	
		});
	
	<?php elseif($use_js == 'adjustMenuJs'): ?>
		// $('.datepicker').datepicker({format:'yyyy-mm-dd h:mm A'});
		$(".timepicker").timepicker({
		    showInputs: false
		});

		$('#save-btn').click(function(){
			$("#adjust_form").rOkay({
				btn_load		: 	$('#save-btn'),
				btn_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(data.error != ""){
											rMsg(data.msg,'error');
										}else{
											window.location.reload();
										}
									}
			});
			return false;
		});
		$('#back-btn').click(function(){
			goTo('receiving/adjustment_menu');
			return false;
		});	
		// $('#item-search').typeaheadmap({
		// 	"source": function(search, process) {
		// 		var url = $('#item-search').attr('search-url');
		// 		var formData = 'search='+search;
		// 		$.post(baseUrl+url,formData,function(data){
		// 			process(data);
		// 		},'json');
		// 	},
		//     "key": "key",
		//     "value": "value",
		//     "listener": function(k, v) {
		// 		set_item_details(v);
		// 	}
		// });
		$('#menu-search').change(function(){
			var item = $(this).val();
			// set_item_details(item);
		});
		function set_item_details(id){
			$.post(baseUrl+'receiving/get_item_details/'+id,function(data){
				$('#item-id').val(data.item_id);
				$('#item-uom').val(data.uom);
				$('#select-uom').find('option').remove();
				$.each(data.opts,function(key,val){
					$('#select-uom').append($("<option/>", {
				        value: val,
				        text: key
				    }));
				});
				$('#item-ppack').val(data.ppack);
				$('#item-ppack-uom').val(data.ppack_uom);
				$('#item-pcase').val(data.pcase);
			},'json');
		}
		$('#add-item-btn').click(function(){
			// alert('aw');
			// if()
			var st = $('#menu-search').find("option:selected").text();
			if(st == ""){
				rMsg('Please select an item.','error');
			}else{

				var noError = $('#add_menu_form').rOkay({
					btn_load	: 	$('#add-item-btn'),
					goSubmit	: 	false,
					bnt_load_remove	: 	true
				});
				if(noError){
					var formData = $("#add_menu_form").serialize();
					$.post(baseUrl+'wagon/add_to_wagon/adj_cart',formData,function(data){
						var row = data.items;
						var id = data.id;
						var tr = $("<tr/>").attr('id','row-'+id);
						$.each(row,function(key,val){
							if(key == 'menu-search' || key == 'qty' || key == 'select-uom' || key == 'cost'){
								if(key == 'menu-search'){
									var selectedText = $('#menu-search').find("option:selected").text();
								    tr.append($("<td/>", {text: selectedText}));
								}	
								else if(key == 'qty' || key == 'cost'){
								    tr.append($("<td/>", {text: $.number(val,2)}));
								}	
								// else if(key == 'select-uom'){
								// 	var txt = splitUom(val);
								// 	tr.append($("<td/>", {text: txt}));
								// }
								else
									tr.append($("<td/>", {text: val}));
							}
						});
						var link = $('<a/>')
							.attr('id','del-'+id)
							.attr('class','del')
							.attr('href','#')
							.html('<i class="fa fa-trash-o fa-lg fa-fw"></i>');
						tr.append($("<td/>",{html:link}));
					    $('#details-tbl').append(tr);
					    $('#add_item_form').find("input[type=text], textarea").val("");
					    $("#menu-search").val('').selectpicker('refresh');
					    // $('#select-uom').find('option').remove();
					    deleteRow(id);
					    total();
					},'json');
				}

			}

			
			return false;
		});
		function deleteRow(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'wagon/delete_to_wagon/adj_cart/'+id,function(data){
					$('#row-'+id).remove();
					total();
				},'json');
				return false;
			});
		}
		function total(){
			$.post(baseUrl+'wagon/get_wagon/adj_cart/',function(data){
				var items = data.items;
				var count = items.length;
				if(count > 0){
					$('.no-items-row').hide();
					// var total = 0;
					// $.each(items,function(line_id,row){
					// 	total += parseFloat(row.qty) * parseFloat(row.cost);
					// });
					// $('#grand-total').number(total,2);
				}
				else{
					$('.no-items-row').show();
					// $('#grand-total').number(0,2);
				}				
			},'json').fail( function(xhr, textStatus, errorThrown) {
			   alert(xhr.responseText);
			});	
		}
		function splitUom(txt){
			var txt = txt.split('-');
			var line = "";
			if(1 in txt){
				if(txt[1] == 'pack'){					
					line = $("#item-ppack-uom").val()+'(@'+txt[2]+' '+txt[0]+')';
				}
				else{
					line = 'Case(@'+txt[2]+' Packs)';
				}
			}
			else
				line = txt[0];
			return line;
		}

	<?php elseif($use_js == 'adjustDetMenuJs'): ?>

		$('#printPreview').click(function(){
			var adj_id = $(this).attr('ref');
			// alert(rec_id);

			var formData = 'adj_id='+adj_id;
			window.open(baseUrl+"receiving/adjustment_menu_pdf?"+formData, "_blank");
			
			
			// if($('#calendar_range').val() == ""){
			// 	rMsg('Enter Date Range','error');
			// }
			// else{
			// 	$.rProgressBar();				
			// }

			return false;
		});

		$('#printExcel').click(function(){
			// alert('aw');
			var adj_id = $(this).attr('ref');
			var formData = 'adj_id='+adj_id;
			var href = baseUrl+"receiving/adjustment_menu_excel";
			window.location = href+'?'+formData;
			return false;
		});

	<?php endif; ?>
});
</script>