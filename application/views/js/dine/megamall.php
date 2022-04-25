<script>
$(document).ready(function(){
	<?php if($use_js == 'megamallPageJs'): ?>
		$('#div-content').rLoad({url:'megamall/files'});

		$('#settings-btn').click(function(event){
			$('#div-content').rLoad({url:'megamall/settings'});
			return false;
		});
		$('#files-btn').click(function(event){
			$('#div-content').rLoad({url:'megamall/files'});
			return false;
		});
	<?php elseif($use_js == 'fileJs'): ?>
		// $('#file_date').change(function(){
		// 	load_daily_files();
		// });
		function load_daily_files(){
			var date = $('#file_date').val();
			$("#daily-div").html("");
			$.post(baseUrl+'megamall/daily_files','file_date='+date,function(data){
				$("#daily-div").html(data);
			});
		}

		$('#show-file-btn').click(function(){
			load_daily_files();
		});

		$('#regen-btn').click(function(){
			var date = $('#file_date').val();
			var btn = $(this);
			btn.goLoad2();
			$.post(baseUrl+'megamall/regen_file','date='+date,function(data){
				// alert(data);
				// $('#daily-div').html(data);
				if(data.error == 0){
					rMsg(data.msg,'success');
					// $.post(baseUrl+'araneta/get_file','date='+date,function(data2){
					// 	$('#daily-div').html(data2.daily);										
					// 	btn.goLoad2({load:false});
					// },'json').fail( function(xhr, textStatus, errorThrown) {
					//  	alert(xhr.responseText);
					// 	btn.goLoad2({load:false});
					// });
					btn.goLoad2({load:false});
					load_daily_files();
				}
				else{
					rMsg(data.msg,'error');
					btn.goLoad2({load:false});
				}
			// });
			},'json');
		});
	<?php elseif($use_js == 'settingsJs'): ?>
		$('#save-btn').click(function(e){
 		   $("#settings-form").rOkay({
				btn_load		: 	$('#save-btn'),
				bnt_load_remove	: 	true,
				goSubmit		:   true,
				asJson			:   true,
				onComplete		: 	function(data){
										rMsg(data.msg,'success');
									}
			});
			return false;
		});		
	<?php endif; ?>	
});
</script>