
<body style="overflow-y: auto;">

<div data-role="page" id="features" style="" class="secondarypage" data-theme="b">
   <div role="main" class="">
    <div class="col-md-12" style="    margin-top: 10px;">
            <div class="cart-main table-responsive non-overflow">
             <!-- <div class="cart-main"> -->

             <?php  //echo "<pre>",print_r($item_cart),"</pre>";die(); ?>
				<h5> <!--<img src="<?=base_url().'img/basket_red.png'?>" class="basket_img_top_cart">-->Order List</h5>
				<table class="cart-tables table">
					<thead>
						<tr>
							<!-- <th width="15%">&nbsp;</th> -->
							<th width="15%">Product Name</th>
							<th width="15%">Modifier</th>
							<th width="10%">Remarks</th>
							<th width="10%">Unit Price</th>
							<th width="20%">Quantity</th>
							<th width="15%">Total</th>
							<th width="2%">&nbsp;</th>
						</tr>
					</thead>

					<tbody>

						<?php 


                       // echo "<pre>",print_r($occupied_table),"</pre>";die();
						if(!empty($item_cart)){
							foreach($item_cart as $item){ 
								if(empty($item['item_id'])){
									continue;
								}
								$img_src =  base_url().'img/noimage.jpg';
		                        $qty = (isset($item['qty'])) ? $item['qty'] : 1 ;
		                         if(!empty($item['item_img'])){
                                   $img_src =  base_url().$item['item_img'];//base_url()."app/image/".$item['file_id'];//'data:image/jpeg;base64,'.base64_encode( $i_c['item_img'] );
                        	   }
                        	   $modname = (isset($item['modname'])) ? $item['modname'] : '' ;
                        	   $namemod = explode(',', $modname);
                        	   $modifiers = (isset($item['modifiers'])) ? $item['modifiers'] : '' ;

						?>
						
							<tr ref="<?=$item['item_id']?>">
								<td>
									<?=$item['item_name']?>
								</td>
								<td><?php 
										foreach ($namemod as $nkey => $nval) {
											echo $nval."<br>";
		                        	   }
									?></td>
								<td>
									<?=$item['remarks']?>
								</td>
								<td><?= " ".$item['unit_price_label']; ?></td>

								<td>
									<button class="btn-sm btn_cart_minus btn_mns" style="background-color: #3598dc!important;float: left;max-width: 30px;margin-top: 20px;"><i class="fa fa-minus" aria-hidden="true"></i></button>										
									<input type="number" name="qty" maxlength="2" value="<?=$qty?>" class="input-text qty bk_color qty ipt_qty" style="" />
									<button class="btn-sm btn_cart_plus btn_pls" style="background-color: #3598dc!important;float: left;max-width: 30px;margin-top: 20px;"><i class="fa fa-plus"></i></button>
								</td>
							    <input type='hidden' name='srp_cart' value="<?=$item['unit_price']?>" />
								<input type="hidden" name="mod" value="<?=$modifiers?>"/>
								<input type="hidden" name="menu_id" value="<?=$item['item_id']?>"/>
								<input type="hidden" name="mod_group_id" value="<?=(isset($item['mod_group_id']) ? $item['mod_group_id'] : '')?>"/>
								<input type="hidden" name="cost" value="<?=(isset($item['cost']) ? $item['cost'] : '')?>"/>
								<input type="hidden" name="mod_id" value="<?=(isset($item['mod_id']) ? $item['mod_id'] : '')?>"/>
								<input type="hidden" name="tableinfo" value="<?=(isset($item['tableinfo']) ? $item['tableinfo'] : '')?>"/>
								<input type="hidden" name="foodserver" value="<?=(isset($item['foodserver']) ? $item['foodserver'] : '')?>"/>
								<input type="hidden" name="remarks" value="<?=(isset($item['remarks']) ? $item['remarks'] : '')?>"/>
								<td>

									<label class="fontsize20" id="l_subtotal">0.00</label>
								</td>
								<td>
									<div class="closeit">X</div>
								</td>
							</tr>

						<?php
							}
						}else{ ?>
							<tr><td colspan="6">No ordered Menu</td></tr>
						<?php } ?>
					
					</tbody>
				</table>

				<?php
				 if(!empty($item_cart)) {  ?>
					<div class="row cart-bottom">
						
						<div class="col-md-12 ">
							<ul style="max-width: 515px;margin: 0 auto;">
								<li>Bag Subtotal <span id="subtotal"></span></li>
								<!-- <li>Shipping Service <span>Free Shipping</span></li> -->
								<li>Order Total <span id="total"></span></li>
							</ul>
						</div>
					</div>

					<div class="row cart-bottom">

						<div class="modal fade" id="tableorder" tabindex="-1" role="basic" aria-hidden="true">
	                      <div class="modal-dialog">
	                          <div class="modal-content">
	                              <div class="modal-header">
	                                  <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="margin-left:-5px">X</span></button>
	                                  <h4 class="modal-title" style="color:#1f95c3;font-size:25px;font-weight:600">SELECT TABLE</h4>
	                              </div>
	                              <div class="modal-body col-md-12 col-xs-12">
	                                <?php foreach ($tableinfo as $tblkey => $tblval) {
	                                    ?>
	                                    <div class="col-md-3  col-xs-3">
	                                      <label>
	                                      	<?php if(isset($occupied_table[$tblval['tbl_id']])) { ?>
	                                        <input type="checkbox" class="modcheck" required='required' name="tableinfos" brefid="<?=$tblval['tbl_id'];?>"  tname="<?=$tblval['name'];?>" value="<?=$tblval['tbl_id'];?>" style="background-color:gray:color:white;" disabled/>
	                                        <?php }else{  ?>
	                                        <input type="checkbox" class="modcheck" required='required' name="tableinfos" brefid="<?=$tblval['tbl_id'];?>" tname="<?=$tblval['name'];?>" value="<?=$tblval['tbl_id'];?>"/>

	                                        <?php } ?>
	                                        <span><?=$tblval['name'];?></span>
	                                      </label>
	                                   </div>
	                                 <?php } ?>
	                              </div>
	                              <div class="modal-footer">
	                              <!-- <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary mod_btn">Save</button> -->
	                              </div>
	                          </div>
	                      </div>
	                  </div>
						
						<div class="col-md-12">
							<table id="make_payment_table" class="table">
									 <tr>
										<td>
											<label accesskey="y" for="payment_types" class="fontsize20"><?php echo 'Select Table'; ?>:</label>
										</td>
										<td>
										     <label id="tbl_info"><span style="margin-right:10px;font-size:16px;font-weight:600"></span> <a href="#tableorder" data-toggle="modal" class="btn green btn-outline" ref="" imref="" aref="01" class="">View Table</a></label>
											
											<!-- <select name="tableinfo" required='required' class="fontsize20" id="payment_types">
												<option value="">None</option>
												<?php
												foreach ($tableinfo as $tblkey => $tblval) {
                                                ?>
                                                	<option value="<?=$tblval['tbl_id'];?>"><?=$tblval['name'];?></option>
                                                <?php }  ?>
                                            </select> -->
										</td>
									</tr> 
									<tr>
										<td>
											<label accesskey="y" for="payment_types" class="fontsize20"><?php echo 'Food Server'; ?>:</label>
										</td>
										<td>
											<select name="foodserver" required='required' class="fontsize20" id="payment_types">
												<option value="">None</option>
												<?php
												foreach ($food_server as $fskey => $fsval) {
                                                ?>
                                                	<option value="<?=$fsval['id'];?>"><?=$fsval['username'];?></option>
                                                <?php }  ?>
                                            </select>
										</td>
									</tr>
							</table>
							
						</div>
						<div class="col-md-12">
							<div class="add_info"><span style="font-size:20px;color:#000;">
								Any Request: </span><textarea name="comment"  value="" class="" ></textarea>
							</div>
						</div>
						
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-12 mgn">
							<div class="dual-btns" style="    margin-bottom: 40px;">
								<!-- <button class="btn-1 btn-orange btn-2 btn-style" style="max-width:180px;" id="update_cart">Update Bag</button> -->
								<button class="btn-1 btn-blue btn-2 btn-style btn-wdt" id="checkout">Submit</button>
								<button class="btn-1 btn-green btn-2 btn-style btn-wdt" id="back_shopping">Add Order</button>
							</div>

						</div>

	 <div data-role="footer">
    <h4 class="banner_footer">Powered by <img class="btm_logo" src="<?=base_url().'img/header_logo_p1.png'?>"></h4>
					<!-- </div> -->

	<!-- <div id='navmenu'>
        <ul>
          <li class='has-sub'>
          </ul>
        </li>
    </div> -->

	


    <script>
    $(function(){
    	update_total();
    });
	$(document).on('click',".closeit" , function() {
      var ref = $(this).parents('tr').attr('ref');
      // console.log(ref);
      $.post("<?= base_url().'app/remove_to_cart/'?>", {'ref':ref} ,function(resp){

        if(resp){

          $("tr[ref='"+ref+"']").hide(500);

          setTimeout(function(){
            $("tr[ref='"+ref+"']").remove();
            
             count_cart();
          },600);

        }

        
     });
    });

     $(document).on('change keyup',"input[name=qty]" , function() {
      update_total();
     });

     $("#back_shopping").on('click',function(){
 
		 var payment_amount = $('input[name=payment_amount]').val();
		 var comment = $('textarea[name=comment]').val();
		 var payment_type = $('select[name=payment_type]').val();

     	$.post("<?= base_url().'app/add_checkout_details/'?>", {'comment':comment,'payment_amount':payment_amount,'payment_type':payment_type} ,function(resp){

  									// console.log(resp);
  									if(resp)
     									window.location.href = "<?= base_url().'app/'?>" ;

  								});
     });

     $(".modcheck").on('click',function(){
     	// $('#tableorder').modal('toggle');
     	toastr.success('', 'Table selected successfully!',{timeOut: 2000});

     	var $box = $(this);
     	$('label#tbl_info span').html($('input.modcheck:checked').attr('tname'));
     	// console.log($box.attr('id'));
		if ($box.is(":checked")) {
		    var group = "input:checkbox[name='" + $box.attr("name") + "']";
		    $(group).prop("checked", false);
		    $box.prop("checked", true);
		} else {
		    $box.prop("checked", false);
		}
		$('.ui-btn-icon-left').addClass('btncheck');
		$('.ui-btn-icon-left').removeClass('ui-checkbox-on');
     });

     $('#update_cart').on('click',function(){
       var items = [];

        $('table tr').each(function(){
          var ref = $(this).attr('ref');
          var qty = $(this).find('input[name=qty]').val();
          var mod = $(this).find('input[name=mod]').val();
          var menu_id = $(this).find('input[name=menu_id]').val();
          var mod_id = $(this).find('input[name=mod_id]').val();
          var cost = $(this).find('input[name=cost]').val();
          var mod_group_id = $(this).find('input[name=mod_group_id]').val();
          var tableinfo = $(this).find('input[name=tableinfo]').val();
          var foodserver = $(this).find('input[name=foodserver]').val();
          var remarks = $(this).find('input[name=remarks]').val();

          if(ref !==undefined){

          	items.push({'ref':ref,'qty':qty,'mod':mod,'menu_id':menu_id,'mod_id':mod_id,'cost':cost,'mod_group_id':mod_group_id,'tableinfo':tableinfo,'foodserver':foodserver,'remarks':remarks});
          }
        });

         $.post("<?= base_url().'app/update_to_cart/'?>", {'item_list':items} ,function(resp){

              // console.log(resp);
            if(resp){
            	swal({
				  title: "Your cart was successfully updated!",
				  text: "Do you want to proceed to checkout?",
				  type: "success",
				  showCancelButton: true,
				  confirmButtonColor: "#DD6B55",
				  confirmButtonText: "Yes, I'm done!",
				  cancelButtonText: "No, I want more!",
				  closeOnConfirm: false
				},
				function(){
		   			
					var payment_amount = $('input[name=payment_amount]').val();
					var comment = $('textarea[name=comment]').val();
	
				   		window.location.href =  "<?= base_url().'app/checkout'?>";
					
				});
              // swal('Your cart was successfully updated!','','success');
            }

        
         });
     });

     $('#checkout').on('click',function(){

     	 var items = [];

        $('table tr').each(function(){
          var ref = $(this).attr('ref');
          var qty = $(this).find('input[name=qty]').val();
          var mod = $(this).find('input[name=mod]').val();
          var menu_id = $(this).find('input[name=menu_id]').val();
          var mod_id = $(this).find('input[name=mod_id]').val();
          var cost = $(this).find('input[name=cost]').val();
          var mod_group_id = $(this).find('input[name=mod_group_id]').val();
          var tableinfo = $(this).find('input[name=tableinfo]').val();
          var foodserver = $(this).find('input[name=foodserver]').val();
          var remarks = $(this).find('input[name=remarks]').val();
          // console.log(tableinfo);
          if(ref !==undefined){

          	items.push({'ref':ref,'qty':qty,'mod':mod,'menu_id':menu_id,'mod_id':mod_id,'cost':cost,'mod_group_id':mod_group_id,'tableinfo':tableinfo,'foodserver':foodserver,'remarks':remarks});
          }
        });

          // console.log(items);
          // return false;
         $.post("<?= base_url().'app/update_to_cart/'?>", {'item_list':items} ,function(resp){
         	// alert(resp);
         	// return false;
         	if(resp){

		     	swal({
						  title: "Are you sure you want to submit?",
						  text: "",
						  type: "warning",
						  showCancelButton: true,
						  confirmButtonColor: "#DD6B55",
						  confirmButtonText: "Yes, I'm done!",
						  cancelButtonText: "No, I want more!",
						  closeOnConfirm: false
						},
						function(){
						 //    var pump_number = $('input[name=pump_number]').val();
							// var plate_number = $('input[name=plate_number]').val();
							var payment_amount = $('select[name=payment_amount]').val();
							var comment = $('textarea[name=comment]').val();
							var payment_type = $('select[name=payment_type]').val();
							var confirm_pp_number = "Please fill out pump number and plate number.";
							var confirm_amount = "Please fill out amount tendered.";
							var serial_tableinfo = $('input[name="tableinfos"]:checked').serialize();
							var tableinfo = serial_tableinfo.replace('tableinfos=','');
							// var response = JSON.parse(tableinfo);
							// alert(strValue);
							// return false;
							var foodserver = $('select[name=foodserver]').val();
							var confirm_tableinfo = "Please select table.";
							var confirm_foodserver = "Please select food server.";

							if((tableinfo == "") ){
								swal({
								  title: "Oopss..",
								  text: confirm_tableinfo,
								  type: "warning",
								  showCancelButton: false,
								  confirmButtonColor: "#DD6B55",
								  confirmButtonText: "Okay",
								  closeOnConfirm: true
								
								});
							}else if((foodserver == "") ){
								swal({
								  title: "Oopss..",
								  text: confirm_foodserver,
								  type: "warning",
								  showCancelButton: false,
								  confirmButtonColor: "#DD6B55",
								  confirmButtonText: "Okay",
								  closeOnConfirm: true
								
								});
							}
							else{
  								$.post("<?= base_url().'app/add_checkout_details/'?>", {'comment':comment,'payment_amount':payment_amount,'payment_type':payment_type,'tableinfo':tableinfo,'foodserver':foodserver} ,function(resp){

  									// console.log(resp);
  									if(resp)
  									// alert(resp);
         							// return false;
						 	 			window.location.href =  "<?= base_url().'app/checkout'?>";

  								});
							}
				});
         	}
	     });
     });

 	$(document).on('click',".btn_cart_plus" , function() {
       var ref = $(this).parents('tr').attr('ref');
       var close_val = parseInt($('table.cart-tables tr[ref='+ref+']').find('input[name=qty]').val());
       $('table.cart-tables tr[ref='+ref+']').find('input[name=qty]').val(close_val + 1);
        update_total();

     });

     $(document).on('click',".btn_cart_minus" , function() {
       var ref = $(this).parents('tr').attr('ref');
        var close_val = parseInt($('table.cart-tables tr[ref='+ref+']').find('input[name=qty]').val());
       if(close_val > 1)
        $('table.cart-tables tr[ref='+ref+']').find('input[name=qty]').val(close_val - 1);

       update_total();
     });

     $(document).on('change keyup',"input[name=qty]" , function() {
      update_total();
     });

	 function update_total(){
      var total = 0;

      $('table tr').each(function(i,e){
      	var ref = $(this).attr('ref');
        var srp = $(e).find('input[name=srp_cart]').val();
        var qty = $(e).find('input[name=qty]').val();

		if(srp !== undefined){
			var str = parseInt(srp.replace(",",""));
			 var equate = str * qty;

			 $('tr[ref='+ref+']').find('#l_subtotal').html(" " + equate.toFixed(2));

        	 total += equate;

       	}
      // console.log(srp); 
          
      });  

      $('span#subtotal').html(' ' + total.toFixed(2));
      $('span#total').html(' ' + total.toFixed(2));
      // return total;
    }


    function count_cart(){
        var ctr_i = $('.cart-tables tbody tr').length;
        $(".shop-cart span").html(ctr_i);

        if(ctr_i <= 0){
			swal({
				title: "Oopss..",
				text: "No items in your cart.",
				type: "warning",
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Okay",
				closeOnConfirm: true
								
			},function(){
				  window.location.href = "<?= base_url().'app/'?>" ;
			});
        }

    }
    $(document).ready(function(){
		$('.disable').prop('disabled', false);
		$(document).on('change','#payment_types',function(){

			// console.log('test');
		if($('#payment_types').val() == 'Credit Card'){
			$('input[name=payment_amount]').val('');
			$('.disable').prop('disabled', true);
		}else{
			$('.disable').prop('disabled', false);
			$('.disable').prop('readonly', false);
		}
	
		});

		$('.remove_input_class').closest("div.ui-back").remove();
		$('#payment_types').closest("div.ui-btn-icon-right").remove();
		$('div[id^="payment_types-button"]').remove();		

	});
    </script>

