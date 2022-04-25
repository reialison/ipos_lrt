<script>
$(document).ready(function(){        
	<?php if($use_js == 'expensesEntryJS'): ?>
		$('#expenses_entry-tbl').rTable({
			loadFrom	: 	 'expenses_entry/get_store_order',
			noEdit		: 	 true,
			add			: 	 function(){
								goTo('expenses_entry/form');
							 },
			afterLoad 	: 	 function(){
								$('.print_pdf').each(function(){
									var id = $(this).attr('ref');
									// var action = $(this).attr('action');
									$('#print_pdf-'+id).click(function(){
										// $.post(baseUrl+'store_order/change_status/'+id+'/'+action,function(data){
											window.open(baseUrl+"expenses_entry/expenses_pdf/"+id, "_blank");
											// window.location.href = baseUrl+"store_order";
										// });
									});
								});
								$('.deny').each(function(){
									var id = $(this).attr('ref');
									var action = $(this).attr('action');
									$('#deny-'+id).click(function(){
										$.post(baseUrl+'store_order/change_status/'+id+'/'+action,function(data){
											goTo('store_order');
										});
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

								var table = $('#expenses_entry-tbl');
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
	<?php elseif($use_js == 'expensesItemlistJS'): ?>
		$('#expenses_entry-tbl').rTable({
			loadFrom	: 	 'expenses_entry/get_expenses_items',
			noEdit		: 	 true,
			add			: 	 function(){
								$.rPopForm({
									text 	: 'Add New Expenses Item',
									loadUrl : 'expenses_entry/item_form',
									passTo	: 'expenses_entry/add_item_db',
									title	: 'Add New Expenses Item',
									rform 	: 'expenses_item_form',
									onComplete : function(data){
													goTo('expenses_entry/expenses_items');
												 }
								});
								return false;
							 },
			afterLoad 	: 	 function(){
								$('.print_pdf').each(function(){
									var id = $(this).attr('ref');
									// var action = $(this).attr('action');
									$('#print_pdf-'+id).click(function(){
										// $.post(baseUrl+'store_order/change_status/'+id+'/'+action,function(data){
											window.open(baseUrl+"expenses_entry/expenses_pdf/"+id, "_blank");
											// window.location.href = baseUrl+"store_order";
										// });
									});
								});

								$('.edit').each(function(){
									var id = $(this).attr('ref');
									var action = $(this).attr('action');
									$('#edit-'+id).click(function(){
										$.rPopForm({
											text 	: 'Add New Expenses Item',
											loadUrl : 'expenses_entry/item_form/'+id,
											passTo	: 'expenses_entry/add_item_db',
											title	: 'Add New Expenses Item',
											rform 	: 'expenses_item_form',
											onComplete : function(data){
															goTo('expenses_entry/expenses_items');
														 }
										});
										return false;
									});
								});
								$('.deny').each(function(){
									var id = $(this).attr('ref');
									var action = $(this).attr('action');
									$('#deny-'+id).click(function(){
										$.post(baseUrl+'expenses_entry/change_status/'+id+'/'+action,function(data){
											goTo('expenses_entry/expenses_items');
										});
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

								var table = $('#expenses_entry-tbl');
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
	<?php elseif($use_js == 'expenseFormJS'): ?>
		$(".timepicker").timepicker({
		    showInputs: false
		});
	    let a = 0;

		$('#add_item').click(function(event){
	        a++;
			event.preventDefault();
	        // var row = $("#addbtnRow").parents("tr:first");
	        // row.insertAfter(row.next());
			$.post(baseUrl+'expenses_entry/get_expense_items/'+a,function(data){
				$("#details-tbl").append(data);
			},'json');
			$(document).find("#addbtnRow").hide();
	        // $('#addbtnRow').hide();
		});

		$(document).on("change", ".getdet_item", function(){//on change of the drowndown and append the values in the textfields
			var item = $(this).val();
			var line = $(this).attr('ref');
			if(item == "ADD"){
				$.rPopForm({
					text 	: 'Add New Expenses Item',
					loadUrl : 'expenses_entry/item_form',
					passTo	: 'expenses_entry/add_item_db',
					title	: 'Add New Expenses Item',
					rform 	: 'expenses_item_form',
					onComplete : function(data){
									goTo('expenses_entry/form');
								 }
				});
				return false;
			}
			else{
				$.post(baseUrl+'expenses_entry/get_details_item/'+item,function(data){
					$('.item_name-'+line).val(data.item_name);
					$('.item_uom-'+line).val(data.item_uom);
					$('.item_price-'+line).val(data.item_price);
				},'json');
			}
		});

		$(document).on("click", "#lock_item", function(){
			$(this).parents('tr').find("select").attr("disabled", true);
			$(this).parents('tr').find('input[qty_textfield="qty_textfield"]').attr("readonly", true);
			$(this).parents('tr').find("#edit_item").show();
			$(this).parents('tr').find("#lock_item").hide();
	        var row = $("#addbtnRow").parents("tr:first");
	        row.insertAfter(row.next());
			// $(document).find("#edit_item").show();
			$('#addbtnRow').show();
			return false;
		});
		$(document).on("click", "#remove_item", function(){
			$(this).closest('tr').remove();
	        var row = $("#addbtnRow").parents("tr:first");
	        row.insertAfter(row.next());
			// $(document).find("#edit_item").show();
			$('#addbtnRow').show();
			return false;
		});
		$(document).on("click", "#edit_item", function(){
			$(this).parents('tr').find("select").attr("disabled", false);
			$(this).parents('tr').find('input[qty_textfield="qty_textfield"]').attr("readonly", false);
			$(this).parents('tr').find("#edit_item").hide();
			$(this).parents('tr').find("#lock_item").show();
			$(document).find("#addbtnRow").hide();
			return false;
		});

		$('#save-exp').click(function(){
			$('select').attr("disabled", false);
			$("#so_form").rOkay({
				btn_load		: 	$('#save-so'),
				btn_load_remove	: 	true,
				// asJson			: 	true,
				onComplete		:	function(data){
									rMsg("Successfully added Expenses Entry",'success');
									goTo('expenses_entry');
									}
			});
			return false;
		});
		// $('#back-btn').click(function(){
		// 	goTo('receiving');
		// 	return false;
		// });	
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


	<?php endif; ?>
});
</script>