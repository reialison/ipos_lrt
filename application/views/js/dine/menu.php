<script>
$(document).ready(function(){
	<?php if($use_js == 'listFormJs'): ?>
		var tbl_ref = $('table#menus-tbl').attr('ref');
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#menus-tbl').rTable({
			loadFrom	: 	 'menu/get_menus',
			noEdit		: 	 <?php echo ENCRYPT_TXT_FILE ? true: 'tref' ?>,
			noAdd		: 	 <?php echo ENCRYPT_TXT_FILE ? true: 'tref' ?>,
			add			: 	 function(){
								goTo('menu/form');
							 },				 	
			edit		: 	 function(id){
								goTo('menu/form/'+id);
							 },
			noBtn1 		:   false,
			btn1Txt		: 	"<i class='fa fa-upload '></i>Upload",				 				 	
			btn1 		: 	function(data){
								bootbox.dialog({
								  message: baseUrl+'menu/upload_excel_form',
								  title: "Select Excel Menu File",
								  buttons: {
								    submit: {
								      label: "<i class='fa fa-check'></i>Master Upload",
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
			<?php if (ENCRYPT_TXT_FILE){ ?>			
			noBtn2 		:   false,
			btn2Txt		: 	"<i class='fa fa-download '></i> Download",				 				 	
			btn2		: 	function(data){
								window.location.href = baseUrl + "menu/download_menus";
							},

			<?php } ?>
			afterLoad 	: 	 function(){
								var table = $('#menus-tbl');
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
							 }						
		});
	<?php elseif($use_js == 'subcategoryListJs'): ?>
		var tbl_ref = $('table#subcategories-tbl').attr('ref');
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#subcategories-tbl').rTable({
			loadFrom	: 	 'menu/get_subcategories',
			noEdit		: 	 true,
			noAdd		: 	 tref,
			add			: 	 function(){
								$.rPopForm({
									loadUrl : 'menu/subcategories_form',
									passTo	: 'menu/subcategories_form_db',
									title	: 'Add New Type',
									rform 	: 'subcategories_form',
									onComplete : function(){
													goTo('menu/subcategories');
												 }
								});
							 },
			afterLoad	:    function(){
								$('.edit').each(function(){
									var id = $(this).attr('ref');
									$('#edit-'+id).click(function(){
										$.rPopForm({
											loadUrl : 'menu/subcategories_form/'+id,
											passTo	: 'menu/subcategories_form_db',
											title	: 'Edit Type',
											rform 	: 'subcategories_form',
											onComplete : function(){
															goTo('menu/subcategories/');
														 }
										});
										return false;
									});
								});
								var table = $('#subcategories-tbl');
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

								// $('#subcategories-tbl').dataTable();
							 }				 				 	 	
		});	
	<?php elseif($use_js == 'categoryListJs'): ?>
		$('#save-menu-cat').click(function(){
			// alert('haha');
			$("#categories_form").rOkay({
				btn_load		: 	$('#save-menu-cat'),
				// bnt_load_remove	: 	true,
				// asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										// return false;
										// console.log(data.msg);
										// if(typeof data.msg != 'undefined' ){
											// $('#menu_id').val(data.id);
											window.location = baseUrl+'menu/categories';
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											// disEnbleTabs('.load-tab',true);
											rMsg(data.msg,'success');
										// }
									}
			});
			return false;
		});


		var tbl_ref = $('table#categories-tbl').attr('ref');
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#categories-tbl').rTable({
			loadFrom	: 	 'menu/get_menu_categories',
			noEdit		: 	 true,
			noAdd		: 	 tref,
			add			: 	 function(){
								goTo('menu/categories_form');
								// $.rPopForm({
								// 	loadUrl : 'menu/categories_form',
								// 	passTo	: 'menu/categories_form_db',
								// 	title	: 'Add New Category',
								// 	rform 	: 'categories_form',
								// 	asJson	:	true,
								// 	onComplete : function(data){
								// 					if(data.error != ""){
								// 						rMsg(data.error,'error');
								// 					}else{
								// 						goTo('menu/categories/');	
								// 					}
								// 				 }
								// });
							 },
			afterLoad	:    function(){
								// $('.edit').each(function(){
								// 	var id = $(this).attr('ref');
								// 	$('#edit-'+id).click(function(){
								// 		$.rPopForm({
								// 			loadUrl : 'menu/categories_form/'+id,
								// 			passTo	: 'menu/categories_form_db',
								// 			title	: 'Add New Category',
								// 			rform 	: 'categories_form',
								// 			asJson	:	true,
								// 			onComplete : function(data){
								// 							// alert(data);
								// 							if(data.error != ""){
								// 								rMsg(data.error,'error');
								// 							}else{
								// 								goTo('menu/categories/');	
								// 							}
								// 						 }
								// 		});
								// 		return false;
								// 	});
								// });
								var table = $('#categories-tbl');
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

								// $('#categories-tbl').dataTable();
							 }				 				 	
			// edit		: 	 function(id){

			// 					goTo('menu/form/'+id);
			// 				 }				 	
		});	

		$('.noSpecial').keydown(function(event){
	    	// alert('aw');
	    	if(event.keyCode == 222) {
	    		// alert('sss');
				event.keyCode = 0;
				return false;
			}
	    });
	<?php elseif($use_js == 'menuFormJs'): ?>
		loader('#details_link');
		$('.tab_link').click(function(){
			var id = $(this).attr('id');
			loader('#'+id);
		});
		function loader(btn){
			var loadUrl = $(btn).attr('load');
			var tabPane = $(btn).attr('href');
			var menu_id = $('#menu_id').val();

			if(menu_id == ""){
				disEnbleTabs('.load-tab',false);
				$('.tab-pane').removeClass('active');
				$('.tab_link').parent().removeClass('active');
				$('#details').addClass('active');
				$('#details_link').parent().addClass('active');
			}
			else{
				disEnbleTabs('.load-tab',true);
			}
			$(tabPane).rLoad({url:baseUrl+loadUrl+menu_id});
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
		function readURL(input) {
        	if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (ev) {
	               
		            $('#pic-form').submit(function(e){
					    var formObj = $(this);
					    var formURL = formObj.attr("action");
					    var formData = new FormData(this);
					    $.ajax({
					        url: baseUrl+formURL,
					        type: 'POST',
					        data:  formData,
					        dataType:  'json',
					        mimeType:"multipart/form-data",
					        contentType: false,
					        cache: false,
					        processData:false,
					        success: function(data, textStatus, jqXHR){
								if(data.msg == ""){
					                $('#item-pic').attr('src',ev.target.result);
									rMsg('Item Image Uploaded','success');
								}
								else{
									rMsg(data.msg,'error');
								}
					        },
					        error: function(jqXHR, textStatus, errorThrown){
								alert(textStatus);
					        }         
					    });
					    e.preventDefault();
					//     e.unbind();
					});
					$('#pic-form').submit();
	            }
	            reader.readAsDataURL(input.files[0]);
	        }
	    }
    	$("#fileUpload").change(function(){
	        readURL(this);        			
	    });
	    $('#target').click(function(e){
	    	$('#fileUpload').trigger('click');
	    }).css('cursor', 'pointer');


	<?php elseif($use_js == 'menuImageJs'): ?>
		$('#save-image').click(function(){
			
			var noError = $('#images_form').rOkay({
    			asJson			: 	false,
				btn_load		: 	null,
				goSubmit		: 	false,
				bnt_load_remove	: 	true
    		});
    		
    		if(noError){
    			$("#images_form").submit(function(e){
				    var formObj = $(this);
				    var formURL = formObj.attr("action");
				    var formData = new FormData(this);
				    $.ajax({
				        url: baseUrl+formURL,
				        type: 'POST',
				        data:  formData,
				//         dataType:  'json',
				        mimeType:"multipart/form-data",
				        contentType: false,
				        cache: false,
				        processData:false,
				        success: function(data, textStatus, jqXHR){
							if(data != ""){
								rMsg(data,'error');
							}
							else{
								rMsg('Image uploaded.','success');
							}
				        },
				        error: function(jqXHR, textStatus, errorThrown){
				        }         
				    });
				    e.preventDefault();
				    e.unbind();
				});
				$("#images_form").submit();
    		}
    		return false;
		});
		function readURL(input) {
        	if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (e) {
	            	// alert(e.target.result);
	                $('#target').attr('src', e.target.result);
	                // $('#target').html(e.target.result);
	            }
	            reader.readAsDataURL(input.files[0]);
	        }
	    }
    	$("#fileUpload").change(function(){
	        readURL(this);
	    });
	    $('#target').click(function(e){
	    	$('#fileUpload').trigger('click');
	    }).css('cursor', 'pointer');
	<?php elseif($use_js == 'detailsLoadJs'): ?>
		$('#menu_cat_id').rAddOpt({
			text 	: 'Add New Menu Category',
			loadUrl : 'menu/categories_form_pop',
			passTo 	: 'menu/categories_form_db_pop',
			form 	: 'categories_form'
		});
		$('#menu_sub_cat_id').rAddOpt({
			text 	: 'Add New Menu SubCategory',
			loadUrl : 'menu/subcategories_form',
			passTo 	: 'menu/subcategories_form_db',
			form 	: 'subcategories_form'
		});
		$('#save-menu').click(function(){
			$("#details_form").rOkay({
				btn_load		: 	$('#save-menu'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(typeof data.msg != 'undefined' ){
											// $('#menu_id').val(data.id);
											if(data.id == 'dup'){

												rMsg(data.msg,'error');
											}else{

												window.location = baseUrl+'menu/form/'+data.id;
											}
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											// disEnbleTabs('.load-tab',true);
											// rMsg(data.msg,'success');
										}
									}
			});
			return false;
		});
		$('#save-new-menu').click(function(){
			// alert('aa');
			$("#details_form").rOkay({
				btn_load		: 	$('#save-new-menu'),
				bnt_load_remove	: 	true,
				addData			: 	'new=1',
				asJson			: 	true,
				onComplete		:	function(data){
										// alert(data);
										if(typeof data.msg != 'undefined' ){
											
											if(data.id == 'dup'){

												rMsg(data.msg,'error');
											}else{
												window.location = baseUrl+'menu/form/'+data.id;
											}
											// $('#menu_id').val(data.id);
											// $('#details').rLoad({url:baseUrl+'branches/details_load/'+sel+'/'+res_id});
											// disEnbleTabs('.load-tab',true);
										}
									}
			});
			return false;
		});
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

		$('.noSpecial').keydown(function(event){
	    	// alert('aw');
	    	if(event.keyCode == 222) {
	    		// alert('sss');
				event.keyCode = 0;
				return false;
			}
	    });
	<?php elseif($use_js == 'schedulesFormJs'): ?>
		$(".timepicker").timepicker({
	       showInputs: false,
	       minuteStep: 1
	    });
	    var schd_id = $('#menu_sched_id').val();
	    if(schd_id == ''){
	    	$('#add-schedule').attr('disabled','disabled');
	    }
		// $('#add-schedule').click(function(){
		// 	alert('haha');
		// 	return false;
		// });
	    $('#add-schedule').click(function(){

	    	$("#schedules_details_form").rOkay({
					btn_load		: 	$('#add-schedule'),
					bnt_load_remove	: 	true,
					asJson			: 	true,
					onComplete		:	function(data){
											// alert(data.id);
											// return false;
											if(data.msg == 'error'){
												msg = 'Day already duplicated in this schedule.';
												rMsg(msg,'error');
											}
											else if(typeof data.msg != 'undefined' ){
												var sel = $('#promo-drop').val();
												location.reload();
												// $('#group-detail-con').rLoad({url:baseUrl+'menu/schedules_form/'+data.id});
												rMsg(data.msg,'success');
											}
										}
				});
	    	return false;
	    });
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
				$.post(baseUrl+'menu/remove_schedule_promo_details',formData,function(data){
					// alert('zxc');
					rMsg(data.msg,'success');
					li.remove();
					var noLi = $('#staff-list li').length;
					if(noLi == 0){
						$('ul#staff-list').append('<li class="no-staff"><span class="handle"><i class="fa fa-fw fa-ellipsis-v"></i></span><span class="text">No Staffs found.</span></li>');
					}
					// });
				},'json').fail( function(xhr, textStatus, errorThrown) {
				   alert(xhr.responseText);
				});
				return false;
			});
		}
		$(".timepicker").timepicker({
			    showInputs: false,
			    minuteStep: 1
			});
			$('#save-list-form').click(function(){
				// alert('zxczxc');
				return false;
			});
			// $('#add-schedule').click(function(){
			// 	var sched_id = $('#menu_sched_id').val();
			// 	var timeon = $('#time-on').val();
			// 	var timeoff = $('#time-off').val();
			// 	var day = $('#day').val();
			// 	var formData = 'sched_id='+sched_id+'&time_on='+encodeURIComponent(timeon)+'&time_off='+encodeURIComponent(timeoff)+'&day='+day;
			// 	// alert(formData);
			// 	$.post(baseUrl+'menu/menu_sched_details_db',formData,function(data){

			// 		rMsg(data.msg,'success');
			// 	},'json');
			// 	// });
			// 	// return false;

			// 	return false;
			// });
		$('#save-btn').click(function(){
			var btn = $(this);
			var noError = $('#schedules_form').rOkay({
    			btn_load		: 	btn,
				bnt_load_remove	: 	true,
				goSubmit		: 	false,
    		});

    		
    		if(noError){
    			btn.goLoad();
    			$("#schedules_form").submit(function(e){
				    var formObj = $(this);
				    var formURL = formObj.attr("action");
				    var formData = new FormData(this);
				    $.ajax({
				        url: baseUrl+formURL,
				        type: 'POST',
				        data:  formData,
				        dataType:  'json',
				        mimeType:"multipart/form-data",
				        contentType: false,
				        cache: false,
				        processData:false,
				        success: function(data, textStatus, jqXHR){
			    			// console.log(data);
			    			// alert(data);
			    			if(data.act == "error"){
			    				rMsg(data.msg,'error');
		    					btn.goLoad({load:false});
			    			}
			    			else{
								goTo('menu/schedules');
			    			}	
				        },
				        error: function(jqXHR, textStatus, errorThrown){
							alert(jqXHR.responseText);
				        }         
				    });
				    e.preventDefault();
				//     e.unbind();
				});
				$("#schedules_form").submit();
    		}
    		return false;
		});
		function readURL(input) {
        	if (input.files && input.files[0]) {
	            var reader = new FileReader();
	            reader.onload = function (e) {
	            	// alert(e.target.result);
	                $('#target').attr('src', e.target.result);
	                // $('#target').html(e.target.result);
	            }
	            reader.readAsDataURL(input.files[0]);
	        }
	    }
    	$("#fileUpload").change(function(){
	        readURL(this);
	    });
	    $('#target').click(function(e){
	    	$('#fileUpload').trigger('click');
	    }).css('cursor', 'pointer');
	<?php elseif($use_js == 'recipeLoadJs'): ?>
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
		// 		$('#item-id-hid').val(v);
		// 		get_item_details(v);
		// 	}
		// });

		$('#item-search').change(function(){
			var item = $(this).val();
			$('#item-id-hid').val(item);
			get_item_details(item);
		});

		$('#add-btn').click(function(event)
		{
			event.preventDefault();
			$('#recipe-details-form').rOkay({
				btn_load	         : $('#add-btn'),
				btn_load_remove 	 : true,
				asJson            	 : true,
				onComplete        	 : function(data) {
											if (data.act == 'add') {
												$('#details-tbl').find('tr:last').prev().after(data.row);
												rMsg(data.msg,'success');
											} else {
												var i = $('#row-'+data.id);
												$('#row-'+data.id).remove();
												$('#details-tbl').find('tr:last').prev().after(data.row);
												rMsg(data.msg,'success');
											}
											$('#item-cost').val('');
											$('#item-uom-hid').val('');
											$('#uom-txt').text('');
											$('#qty').val('');
											$('#item-search').val('').selectpicker('refresh')
											get_recipe_total();
											remove_row(data.id);
										}
			});
		});
		function get_item_details(v){
			$.post(baseUrl+'menu/recipe_item_details/'+v,function(data){
				$('#item-cost').val(data.cost);
				$('#item-uom-hid').val(data.uom);
				$('#uom-txt').text(data.uom);
				
			},'json');
		}

		function get_recipe_total()
		{
			var mid = $( '#menu-id-hid' ).val();
			if ( mid != "" ) {
				$.post( baseUrl+'menu/get_recipe_total','menu_id='+mid,function(data){
					// $('#total').val(data.total);
					$('#total').text(data.total);
				},'json');
			}
		}
		function remove_row(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'menu/remove_recipe_item','recipe_id='+id,function(data){
					$('#row-'+id).remove();
					rMsg(data.msg,'success');
				},'json');
				get_recipe_total();
				return false;
			});
		}
		$('#override-price').click(function(event){
			event.preventDefault();
			var mid = $( '#menu-id-hid' ).val();
			if ( mid != "" ) {
				var total = $('#total').val();
				$.post(baseUrl+'menu/override_price_total','menu_id='+mid+'&total='+total,function(data){
					rMsg('<b>Recipe price has been updated</b>','success');
				});
			}
		});

		$('.del-item').click(function(event)
		{
			event.preventDefault();
			var id = $(this).attr('ref');
			$.post(baseUrl+'menu/remove_recipe_item','recipe_id='+id,function(data){
				$('tr#row-'+id).remove();
				get_recipe_total();
				rMsg(data.msg,'success');
			},'json');
		});
	<?php elseif($use_js == 'menuModifierJs'): ?>
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
		// 		$('#mod-group-id-hid').val(v);

		// 		$('#menu-modifier-form').rOkay({
		// 			btn_load	         : $('#add-btn'),
		// 			btn_load_remove 	 : true,
		// 			asJson            	 : true,
		// 			onComplete        	 : function(data) {
		// 										if (data.result == 'success') {
		// 											$('#details-tbl').append(data.row);
		// 											rMsg(data.msg,'success');
		// 											remove_row(data.id);
		// 										} else {
		// 											rMsg(data.msg,'error');
		// 										}
		// 									}
		// 		});
		// 	}
		// });
		$('#add-grp-modifier').click(function(){
			var grp_id = $('#item-search').val();
			
			$('#mod-group-id-hid').val(grp_id);
			$('#menu-modifier-form').rOkay({
				btn_load	         : $('#add-grp-modifier'),
				btn_load_remove 	 : true,
				asJson            	 : true,
				onComplete        	 : function(data) {
											if (data.result == 'success') {
												$('#details-tbl').append(data.row);
												rMsg(data.msg,'success');
												remove_row(data.id);
												$('#item-search').selectpicker('val','');
											} else {
												rMsg(data.msg,'error');
											}
										}
			});			
			return false;
		});
		// $('#add-btn').click(function(event)
		// {
		// 	event.preventDefault();
		// 	$('#menu-modifier-form').rOkay({
		// 		btn_load	         : $('#add-btn'),
		// 		btn_load_remove 	 : true,
		// 		// asJson            	 : true,
		// 		onComplete        	 : function(data) {
		// 			alert(data);
		// 									// if (data.result == 'success') {
		// 									// 	$('#details-tbl').append(data.row);
		// 									// 	rMsg(data.msg,'success');
		// 									// 	remove_row(data.id);
		// 									// } else {
		// 									// 	rMsg(data.msg,'error');
		// 									// }
		// 									// remove_row(data.id);
		// 								}
		// 	});
		// });
		function remove_row(id){
			$('#del-'+id).click(function(){
				$.post(baseUrl+'menu/remove_menu_modifier','recipe_id='+id,function(data){
					$('#row-'+id).remove();
					rMsg(data.msg,'success');
				},'json');
				return false;
			});
		}
		$('.del-item').click(function(event)
		{
			event.preventDefault();
			var id = $(this).attr('ref');
			$.post(baseUrl+'menu/remove_menu_modifier','id='+id,function(data){
				$('tr#row-'+id).remove();
				rMsg(data.msg,'success');
			},'json');
		});

	<?php elseif($use_js == 'subcategoryListNewJs'): ?>
		var tbl_ref = $('table#subcategory-tbl').attr('ref');
		if(tbl_ref == 1){
			tref = true;
		}else{
			tref = false;
		}
		$('#subcategory-tbl').rTable({
			loadFrom	: 	 'menu/get_subcategories_new',
			noEdit		: 	 true,
			noAdd		: 	 tref,
			add			: 	 function(){
								$.rPopForm({
									loadUrl : 'menu/subcategories_form_new',
									passTo	: 'menu/subcategories_form_new_db',
									title	: 'Add New SubCategory',
									rform 	: 'subcategories_form_new',
									onComplete : function(){
												// alert(data);
													goTo('menu/subcategories_new');
												 }
								});
							 },
			afterLoad	:    function(){
								$('.edit').each(function(){
									var id = $(this).attr('ref');
									$('#edit-'+id).click(function(){
										$.rPopForm({
											loadUrl : 'menu/subcategories_form_new/'+id,
											passTo	: 'menu/subcategories_form_new_db',
											title	: 'Edit SubCategory',
											rform 	: 'subcategories_form_new',
											onComplete : function(){
															goTo('menu/subcategories_new/');
														 }
										});
										return false;
									});
								});
								var table = $('#subcategory-tbl');
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
						            responsive: false,

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

								// $('#subcategories-tbl').dataTable();
							 }				 				 	 	
		});	

	<?php elseif($use_js == 'menuPricesJs'): ?>
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
		// 		$('#mod-group-id-hid').val(v);

		// 		$('#menu-modifier-form').rOkay({
		// 			btn_load	         : $('#add-btn'),
		// 			btn_load_remove 	 : true,
		// 			asJson            	 : true,
		// 			onComplete        	 : function(data) {
		// 										if (data.result == 'success') {
		// 											$('#details-tbl').append(data.row);
		// 											rMsg(data.msg,'success');
		// 											remove_row(data.id);
		// 										} else {
		// 											rMsg(data.msg,'error');
		// 										}
		// 									}
		// 		});
		// 	}
		// });
		$('#add-price').click(function(){
			var trans_type = $('#trans_type').val();
			
			// $('#mod-group-id-hid').val(grp_id);
			$('#menu-price-form').rOkay({
				btn_load	         : $('#add-price'),
				btn_load_remove 	 : true,
				asJson            	 : true,
				onComplete        	 : function(data) {
											if (data.result == 'success') {
												$('#details-tbl').append(data.row);
												rMsg(data.msg,'success');
												// remove_row(data.id);
												// $('#item-search').selectpicker('val','');
											} else {
												rMsg(data.msg,'error');
											}
										}
			});			
			return false;
		});
		// $('#add-btn').click(function(event)
		// {
		// 	event.preventDefault();
		// 	$('#menu-modifier-form').rOkay({
		// 		btn_load	         : $('#add-btn'),
		// 		btn_load_remove 	 : true,
		// 		// asJson            	 : true,
		// 		onComplete        	 : function(data) {
		// 			alert(data);
		// 									// if (data.result == 'success') {
		// 									// 	$('#details-tbl').append(data.row);
		// 									// 	rMsg(data.msg,'success');
		// 									// 	remove_row(data.id);
		// 									// } else {
		// 									// 	rMsg(data.msg,'error');
		// 									// }
		// 									// remove_row(data.id);
		// 								}
		// 	});
		// });
		function remove_row(id){
			// alert('wawa');
			$('#del-'+id).click(function(){
				$.post(baseUrl+'menu/remove_menu_price','id='+id,function(data){
					$('#row-'+id).remove();
					rMsg(data.msg,'success');
				},'json');
				return false;
			});
		}
		// $('.del-item').click(function(event)
		$('.del-item').off('click');
		$(document).on('click','.del-item',function(event)
		{	
			// alert('wewe');
			// event.preventDefault();
			var id = $(this).attr('ref');
			$.post(baseUrl+'menu/remove_menu_price','id='+id,function(data){
				$('tr#row-'+id).remove();
				rMsg(data.msg,'success');
			},'json');
		});
	<?php elseif($use_js == 'menuQuickEditJS'): ?>
		var scrolled=0;

		loadMenuCategories();

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
				$.post(baseUrl+'menu/get_menus_search_quick_edit',formData,function(data){
					var div = $('.menus-div .items-lists').append('<div class="row"></div>');
					
			 		$.each(data,function(key,opt){			 			
			 			var menu_id = opt.id;
			 			var sCol = $('<div class="col-md-2"></div>');
			 			$('<button/>')
			 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default media-order-btns','style':'color:'+opt.branch_text_color+'!important;background-color:#00B0F0!important'})
			 			.text(opt.name)
			 			.appendTo(sCol)
			 			.click(function(){
			 				menu_form(menu_id,opt.name);
				 				// if(opt.free == 1){
					 			// 	$.callManager({
					 			// 		success : function(){
									//  				// addTransCart(menu_id,opt);
					 			// 				  }	
					 			// 	});
				 				// }
				 				// else{
				 				// 	// addTransCart(menu_id,opt);
				 				// }
				 			
			 				return false;
			 			});
			 			sCol.appendTo(div);
			 			$('.scrollers-menu').remove();
			 		});
			 		$('.menus-div .items-lists').after('<div class="scrollers-menu" style="width:100%"><div class="row"><div class="col-md-6 text-left "><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
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

		$('#search-menu')
        .keyboard({
            alwaysOpen: false,
            usePreview: false,
			autoAccept : true,
			change   : function(e, keyboard, el) { 
				var search = $('#search-menu').val();
				if(search != ""){
					remeload('menu');
					$('.scrollers-menu').remove();
					$('.loads-div').hide();
					$('.menus-div').show();
					$('.menus-div .title').text('Search: '+search);
					$('.menus-div .items-lists').html('');
					var formData = 'search='+search;
					$.post(baseUrl+'menu/get_menus_search_quick_edit',formData,function(data){
						var div = $('.menus-div .items-lists').append('<div class="row"></div>');
						
				 		$.each(data,function(key,opt){			 			
				 			var menu_id = opt.id;
				 			var sCol = $('<div class="col-md-2"></div>');
				 			$('<button/>')
				 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default media-order-btns','style':'color:'+opt.branch_text_color+'!important;background-color:#00B0F0!important'})
				 			.text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				menu_form(menu_id,opt.name);
					 				// if(opt.free == 1){
						 			// 	$.callManager({
						 			// 		success : function(){
										//  				// addTransCart(menu_id,opt);
						 			// 				  }	
						 			// 	});
					 				// }
					 				// else{
					 				// 	// addTransCart(menu_id,opt);
					 				// }
					 			
				 				return false;
				 			});
				 			sCol.appendTo(div);
				 			$('.scrollers-menu').remove();
				 		});
				 		$('.menus-div .items-lists').after('<div class="scrollers-menu" style="width:100%"><div class="row"><div class="col-md-6 text-left "><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
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
				}else{
					loadMenuCategories();
				}
				return false;
			},
        })
        .addNavigation({
            position   : [0,0],
            toggleMode : false,
            focusClass : 'hasFocus'
        });

		function loadMenuCategories(){

			ttype = $('#ttype').val();
			// alert(ttype);

			if(ttype == 'mcb'){
				$.post(baseUrl+'cashier/get_menu_cats_quick_edit',function(data){
			 		// alert(data);return false;
			 		showMenuCategories(data,1);
		 		// });
			 	},'json');
			}else{
			 	// $.post(baseUrl+'cashier/get_menu_categories',function(data){
			 	$.post(baseUrl+'cashier/get_menu_cats_quick_edit',function(data){
			 		// alert(data);return false;
			 		showMenuCategories(data,1);
		 		// });
			 	},'json');
			}
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
	 			.attr({'id':'menu-cat-'+cat_id,'ref':cat_id,'class':'btn-block category-btns counter-btn-blue double btn btn-default','style':'height:60px;width:130px;color:'+val['branch_text_color']+'!important;background-color:'+val['bcolor']+'!important'})
	 			.text(val.name)
	 			.appendTo('.menu-cat-container')
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
				ctr++;
			});
			if(ctr < 10){
				for (var i = 0; i <= (10-ctr); i++) {
					$('<button/>')
		 			.attr({'class':'btn-block category-btns counter-btn-red-gray double btn btn-default media-cats-btns'})
		 			.text('')
		 			.appendTo('.menu-cat-container');
				};
			}
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

		function loadsDiv(type,id,opt,trans_id,other,mandatory){
			if(type == 'menus'){
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
			
				
				//for category -> menu
				formData = 'ttype='+ttype;
			 	$.post(baseUrl+'menu/get_menus_quick_edit/'+id,formData,function(data){
			 		// alert(data);
					
			 		if(data.show_img == 1){
			 			$.each(data.json,function(key,opt){
				 			// alert(opt.reorder_qty);
				 			var menu_id = opt.id;
				 			var sCol = $('<div class="col-md-3" style="margin-top:10px;position:relative;text-align: center;color: white; cursor:pointer; border-style:solid;"></div>');
				 			$('<img src='+baseUrl+opt.img_path+' width="100%" height="100%" id="menu-'+menu_id+'"><div style="position: absolute;bottom:1px;background-color:rgba(0,0,0,.6); width:100%; text-align:center;font-size:11px;">'+opt.name+'</div>')
				 			// .text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				// alert(opt.menu_oh);
				 				
				 				menu_form(menu_id,opt.name);

				 			});
				 			sCol.appendTo(div);
				 			
				 		});
			 		}else{
						// var div = $('.menus-div .items-lists').append('<div class="row"></div>');
				 		$.each(data.json,function(key,opt){
				 			// alert(opt.reorder_qty);
				 			var menu_id = opt.id;
				 			var sCol = $('<div class="col-md-2" style=""></div>');
				 			$('<button/>')
				 			.attr({'id':'menu-'+menu_id,'ref':menu_id,'class':'counter-btn-silver btn btn-block btn-default media-order-btns','style':'color:'+opt.branch_text_color+'!important;background-color:#00B0F0!important'})
				 			.text(opt.name)
				 			.appendTo(sCol)
				 			.click(function(){
				 				var btn = $(this);
				 				// alert(opt.name);
				 				menu_form(menu_id,opt.name);


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

		 		$('.menus-div .items-lists').after('<div class="scrollers-menu" style="width:100%"><div class="row"><div class="col-md-6 text-left "><button id="menu-item-scroll-up" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-up fa-2x fa-fw"></i></button></div><div class="col-md-6 text-left"><button id="menu-item-scroll-down" class="btn-block counter-btn double btn btn-default " style="height:60px;"><i class="fa fa-fw fa-chevron-circle-down fa-2x fa-fw"></i></button></div></div></div>');
		 		$("#menu-item-scroll-down").on("click" ,function(){
			 		 //    scrolled=scrolled+100;
			 			// $(".items-lists").animate({
			 			//         scrollTop:  scrolled
			 			// });
			 			// alert(1);
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
			}
			
		}

		// $('.menu-form').off();
		$(document).off('submit', '#menu-form');
		$(document).on('submit','#menu-form',function(){
		// $('#menu-form').submit(function(){
			var formData = $(this).serialize();
			
			$.post(baseUrl+'menu/menu_quick_edit_db',formData,function(data){
				// alert(data);
				if(data == ""){
					rMsg('Menu successfully updated','success');
					$('.close').last().click();
				}
			});

			return false;
		});

		$(document).on('click','.radio-btn',function(){
			// $('#inactive').removeAttr('checked');
			// $('#unavailable').removeAttr('checked');
			// $('#set-qty').removeAttr('checked');
			// var $rbtn_id = $(this).attr('id'); 
			// $('.radio-btn :not(#'+$rbtn_id+')').removeAttr('checked');
			$('.radio-btn').removeAttr('checked');

			if($(this).attr('id') != 'set-qty'){
				$('#qty').hide();
			}else{
				$('#qty').show();
			}
			// $('.radio-btn').attr('checked','checked');
			$(this).prop('checked','checked');
			
			// $(this).change();
			// return false;
		});

		$(document).on('click','#set-qty',function(){
			if($(this).is(':checked')){
				$('#div-qty').removeClass('hidden');
			}else{
				$('#div-qty').addClass('hidden');
				$('#qty').val('');
			}

			$('#qty').keyboard({
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

		    // alert($('ui-keyboard-keyset-normal').length());
		    // $('ui-keyboard-keyset-normal').css('display','block')


		     // $('#qty').getkeyboard().reveal();
		});

		function menu_form(menu_id,menu_name){

			$.rPopForm({
									loadUrl : 'menu/menu_quick_edit_form/'+menu_id,
									// passTo	:  'menu/menu_quick_edit_db/'+menu_id,
									title	: '<font size="4">'+menu_name+'</font>',
									rform 	: 'menu-form',
									noButton: 1,
									// onComplete : function(){
									// 				alert(1);
									// 			 }
								});
		}
	<?php endif; ?>
});
</script>