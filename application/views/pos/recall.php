
<body style="overflow-y: auto;">

	<div data-role="page" id="features" class="secondarypage" data-theme="b">
		<div id="tables">
			<div class="select-table-div loads-div" id="select-table">
				<div class="nav-btns-con">
					<div class="row">
						<div class="col-md-10">
							<div class="title background-new-blue">
								<h3 class="headline text-center text-uppercase" style="padding:12px">TABLE VIEW</h3>
							</div>
						</div>
						<div class="col-md-2">
							<div class="exit" style="margin-top:20px!important">
								<button id="exit-btn" class="btn-block tables-btn background-new-blue" style="padding: 14px;"><i class="btn-block tables-btn"></i>EXIT </button>
							</div>
						</div>
					</div>
				</div>
				<div id="image-con">								
				</div>
			</div>
			<div class="no-guest-div loads-div" style="display:none;">
				<div class="nav-btns-con">
					<div class="row">
						<div class="col-md-10">
							<div class="title bg-red">
								<h3 class="headline text-center text-uppercase" style="padding:12px;">No. Of Guest</h3>
							</div>
						</div>
					</div>
				</div>							
			</div>
		</div>
		<div class="modal fade" id="srecall" tabindex="-1" role="basic" aria-hidden="true">
		  <div class="modal-dialog" id="recalldata">
		      <div class="modal-content">
		          <div class="modal-header">
		              <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="margin-left:-5px">X</span></button>
		              <h4 class="modal-title" style="color:#000"><?//=$mod_title?></h4>
		          </div>
		          <div class="modal-body col-md-12">
		          </div>
		          <div class="modal-footer">
		          <!-- <button type="button" id="print_bill" class="btn_submit btn btn-primary mod_btn">Print Billing</button> -->
		          </div>
		      </div>
		  </div>
		</div>

				
	<div data-role="footer">
		<h4 class="banner_footer">Powered by <img class="btm_logo" src="<?=base_url().'img/header_logo_p1.png'?>"></h4>	
	</div>	

	<script>
		$(document).ready(function(){
			$.post("<?= base_url().'app/get_branch_details/'?>",function(data){
				var img = data.layout;
				if(img != "" ){
					var img_real_width=0,
					    img_real_height=0;
					$("<img/>")
					.attr("src", img)
				    .attr("id", "image-layout")
				    .attr("style", "width:760px")
				    .load(function(){
			           img_real_width = this.width;
			           img_real_height = this.height;
			           $(this).appendTo('#image-con');
			           $("<div/>")
					    .attr("class", " retag col-md-10")
					    .attr("id", "rtag-dv")
					    .css("height", img_real_height)
					    .css("width", img_real_width)
					    .appendTo('#image-con');
						loadMarks();

					});
				}
			},'json');

			$('.btn_submit').removeClass('ui-btn');

			// $('#print_bill').on('click',function(){
			$(document).on('click',"#print_bill" , function(a) {
				var ss = $('#print_bill').val();
				// alert(ss);
				// return false;
		        window.location.href =  "<?= base_url().'app/pos/print_billing/'?>"+ss;
				a.preventDefault();
	      	});
		});
		function loadMarks(){
			$.post("<?= base_url().'app/pos/get_tables/'?>",function(data){
				var top = '60px;';
				var left = '100px';
				$.each(data,function(tbl_id,val){
					$('<a/>')
	    			.attr('href','#')
	    			.attr('class','col-md-2 markers recall marks-'+val.stat)
	    			.attr('id','mark-'+tbl_id)
	    			.attr('ref',tbl_id)
	    			.html('<h5 style="text-align:center;padding-top:7px;color:#fff">'+val.name+'</h5>')
	    			.appendTo('#rtag-dv')
	    			.click(function(e){
	    				$.post("<?= base_url().'app/pos/check_tbl_activity/'?>"+tbl_id,function(data){
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
		}
		function updateTblStatus(){
			$.post("<?= base_url().'app/pos/get_tbl_status/'?>",function(data){
				$.each(data,function(tbl_id,val){
					var mark = $('#mark-'+tbl_id);
					mark.removeClass('marks-green');
					mark.removeClass('marks-orange');
					mark.removeClass('marks-red');
					mark.addClass('marks-'+val.stat);
					mark.unbind('click');
					if(val.stat == 'green'){
						mark.click(function(){
							$.post("<?= base_url().'app/pos/check_tbl_activity/'?>"+tbl_id,function(data){
								if(data.error == ""){
									$('#select-table').attr('ref',tbl_id);
			    					$('#select-table').attr('ref_name',val.name);
			    					// loadDivs('no-guest');
			    					$('#guest-input').focus();
								}	
							},'json');	
						});
					}
					else{
						mark.click(function(){
							$.post("<?= base_url().'app/pos/check_tbl_activity/'?>"+tbl_id,function(data){
								// console.log(tbl_id);
								if(data.error == ""){
									$("#occ-num").text(val.name);
									$('#select-table').attr('ref',tbl_id);
									$('#select-table').attr('ref_name',val.name);
									// loadDivs('occupied');
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
		function get_table_orders(tbl_id){
			$('.occ-orders-div').html('<br><center><i class="fa fa-spinner fa-spin fa-2x"></i></center>');
			$.post("<?= base_url().'app/pos/get_table_orders/true/'?>"+tbl_id,function(data){
				$('.occ-orders-div').html(data.code);
				if(data.ids != null){
					$.each(data.ids,function(id,val){
						// $('#order-btn-'+id).click(function(){
							//check if settled na
							$.post("<?= base_url().'app/pos/check_trans_settled/'?>"+id+'/'+tbl_id,function(data1){
								 var dta = JSON.parse(data1);
								// console.log(dta);
								// return false;
								if(dta.error == 1){
									// rMsg('This transaction has been settled.','error');
									toastr.error('', 'This transaction has been settled.',{timeOut: 2000});
								}else{
									$('#srecall').modal('show');
									// $('.modal.fade.in').find('.modal-body').html(dta.table_trans);
									// console.log(dta.table_trans);
									// return false;
									var tbl=$("<table/>").attr("id","ajxtable").attr('class','table table-bordered');
									var obj   = dta.table_trans;
									var th    = "<thead>";
									var	ttr   = "<tr>";
									var	tmenu =	"<th>Menu Name</th>";
									var tmodi = "<th>Modifier Name</th>";
									var tprice=	"<th>Price</th>";
									var tqty  =	"<th>Qty</th>";
									var ettr  =	"</tr>";
									var etth  = "</thead>";
									var ettb  = "<tbody id='tablebody'>";
									$(".modal-body").append(tbl);
									$("#ajxtable").append(th+ttr+tmenu+tmodi+tprice+tqty+ettr+etth+ettb);
									for(var i=0;i<obj.length;i++)
									{
									    var tr="<tr>";
									    var td1="<td>"+obj[i]["mname"]+"</td>";
									    if(obj[i]["mod_name"] != null){
									    	var td2="<td>"+obj[i]["mod_name"]+"</td>";
										}else{
										    var td2="<td></td>";
										}
									    var td3="<td>"+obj[i]["menu_price"]+"</td>";
									    var td4="<td>"+obj[i]["menu_qty"]+"</td></tr>";
									    var td_loop = tr+td1+td2+td3+td4;
									   $("#tablebody").append(td_loop); 
									  
									}
									var btn_a = '<button type="button" id="print_bill" value='+id+' class="btn_submit btn btn-primary mod_btn">Print Billing</button> ';
									$(".modal-footer").append(btn_a);
								}
							});
							return false;
						// });	
					});
				}
			},'json');
		}

		$(".modal").on("hidden.bs.modal", function(){
		    $(".modal-body").html("");
		    $(".modal-footer").html("");
		});

		$('#exit-btn').on('click',function(){
	        window.location.href =  "<?= base_url().'app/'?>";
	      });

		




	</script>			