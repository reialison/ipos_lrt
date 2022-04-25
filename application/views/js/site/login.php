<script>
$(document).ready(function(){
	<?php if($use_js == 'loginJs'): ?>
		$('#pin').keypress(function(event){
		  if(event.keyCode == 13){
		   $('#pin-login').trigger('click');
		  }
		});
		if($('#shift_end').exists()){
			rMsg('Last Shift has ended.','success');
		}
		if($('.rot-login-by').exists()){
			var rot = $('.rot-login-by').first();
			$('.logins').hide();
			$('#pin-user').hide();
			$('#pin-id').val('');
			$(rot.attr('act')).show();
			if(rot.attr('act') == '#loginPin'){
				if(rot.attr('name') !== undefined){
					$('#pin-user').text(rot.attr('name'));
					$('#pin-user').show();
					$('#pin-id').val(rot.attr('user'));
				}
				else{
					$('#pin-user').text("");
					$('#pin-user').hide();
					$('#pin-id').val('');			
				}
			}
		}
		$('.login-by').click(function(){
			$('.logins').hide();
			$('#pin-user').hide();
			$('#pin-id').val('');
			$($(this).attr('act')).show();
			if($(this).attr('act') == '#loginPin'){
				if($(this).attr('name') !== undefined){
					$('#pin-user').text($(this).attr('name'));
					$('#pin-user').show();
					$('#pin-id').val($(this).attr('user'));
				}
				else{
					$('#pin-user').text("");
					$('#pin-user').hide();
					$('#pin-id').val('');
				}
			}
			$('#pin').focus();			
			return false;
		});
		$('#training').click(function(){
			// alert(baseUrl);
			window.location = 'http://localhost/dineTrain';
			return false;
		});
		$('#uname-login').click(function(){
			$("#uname-login-form").rOkay({
				btn_load		: 	$('#uname-login'),
				bnt_load_remove	: 	true,
				asJson			: 	true,
				onComplete		:	function(data){
										if(data.error_msg != null){
											rMsg(data.error_msg,'error');
										}
										else{
											window.location = baseUrl+'cashier';
										}
									}
			});
			return false;
		});
		$('#pin-login').click(function(){
			var pin = $('#pin').val();
			pin = pin.replace("%", "");
			pin = pin.replace("+", "");
			var pin_id = $('#pin-id').val();
			// alert(pin);
			$.post(baseUrl+'site/go_login','pin='+pin+'&pin_id='+pin_id,function(data){
				if(data.error_msg != null){
					rMsg(data.error_msg,'error');
					$('#pin').focus();
				}
				else{
					window.location = data.redirect_address;
				}
			// },'json');
			},'json');
			return false;
		});
		// $('#login-btn').click(function(){
		// 	$("#login-form").rOkay({
		// 		btn_load		: 	$('#login-btn'),
		// 		bnt_load_remove	: 	true,
		// 		asJson			: 	true,
		// 		onComplete		:	function(data){
		// 								// alert(data);
		// 								if(data.error_msg != null){
		// 									rMsg(data.error_msg,'error');
		// 								}
		// 								else{
		// 									window.location = baseUrl;
		// 								}
		// 							}
		// 	});
		// 	return false;
		// });
		setInterval(function(){
		<?php if(PRODUCT_KEY){ ?>
			check_pr_key();
		<?php } ?>
	  		checkNewShifts();	  		
		}, 3000);
		function checkNewShifts(){
			$.post(baseUrl+'site/get_login_unclosed_shifts',function(data){
				$.each(data,function(user_id,row){
					if(!$('#shift-btn-'+user_id).exists()){
						console.log('added');
						var div = $('<div act="#loginPin" id="shift-btn-'+user_id+'" user="'+user_id+'" name="'+row['name']+'"'+
									'class="login-by tsc_awb_large tsc_awb_white tsc_flat">'+
										'<img style="height:40px;" src="'+baseUrl+'img/avatar.jpg">'+
										'<h5>'+row['username']+'</h5>'+
									'</div>');
						$('#shift-column').append(div);
						$('.login-by').click(function(){
							$('.logins').hide();
							$('#pin-user').hide();
							$('#pin-id').val('');
							$($(this).attr('act')).show();
							if($(this).attr('act') == '#loginPin'){
								if($(this).attr('name') !== undefined){
									$('#pin-user').text($(this).attr('name'));
									$('#pin-user').show();
									$('#pin-id').val($(this).attr('user'));
								}
								else{
									$('#pin-user').text("");
									$('#pin-user').hide();
									$('#pin-id').val('');
								}
							}
							$('#pin').focus();			
							return false;
						});
					}
				});
			},'json');	
		}

		function check_pr_key(){
			$.post(baseUrl+'api/check_key',function(data){
				if(data == 1){
					window.location.href = baseUrl+"site/login";
				}else if(data == 2){
					window.location.reload();
				}
			});
		}

	<?php if(CONSOLIDATOR){ ?>
			setInterval(function(){ update_terminals(); }, 10000);                  

            //update terminals
            function update_terminals(){
                $.post('<?= base_url() ?>site/execute_migration',function(data){
                                             
                });
            }
	<?php } ?>

	<?php elseif($use_js == 'autoZreadJs'): ?>
		$.post(baseUrl+'reads/auto_zread',function(data){
			// alert(data);
			$('.ztxt').html(data);
			setTimeout(function() {
			  window.location.href = baseUrl+"site/login";
			}, 2000);
		});

	<?php elseif($use_js == 'PosKeyJs'): ?>
		$('#key-btn').click(function(){
			$('#key-btn').text('Validating..').prop('disabled',true);
			$.post(baseUrl+'api/set_key','key='+$('#key_code').val(),function(data){
			// 	alert(data);
				if(data == ''){
					swal({
					    title: "Success!",
					    text: "Product key is valid",
					    type: "success"
					}, function() {
					     window.location.reload();
					});									
				}else{
					 swal(data,'','error');
					 $('#key-btn').text('Enter').prop('disabled',false);
				}
			// // },'json');
			});
			
			return false;
		});
	<?php endif; ?>
});
</script>