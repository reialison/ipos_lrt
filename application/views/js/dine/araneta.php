<script>
$(document).ready(function(){
	<?php if($use_js == 'aranetaPageJs'): ?>
		$('#content').rLoad({url:'araneta/daily_files'});
		$('#daily-files-btn').click(function(){
			$('#content').rLoad({url:'araneta/daily_files'});
			return false;
		});
		$('#settings-btn').click(function(){
			$('#content').rLoad({url:'araneta/settings'});
			return false;
		});
	<?php elseif($use_js == 'fileJs'): ?>
		$('#file_date').change(function(){
			load_daily_files();
		});
		function load_daily_files(){
			var date = $('#file_date').val();
			$("#summary-div").html("");
			$("#monthly-div").html("");
			$("#trans-list-div").html("");
			var btn = $('#regen-btn');
			btn.goLoad2();
			$.post(baseUrl+'araneta/daily_files','file_date='+date,function(data){
				$("#summary-div").html(data.sum);
				$("#trans-list-div").html(data.list);
				$("#monthly-div").html(data.month);
				btn.goLoad2({load:false});
			},'json');
		}
		$('#regen-btn').click(function(){
			var date = $('#file_date').val();
			var btn = $(this);
			btn.goLoad2();
			$.post(baseUrl+'araneta/regen_file','date='+date,function(data){
				if(data.error == 0){
					rMsg(data.msg,'success');
					load_daily_files();
				}
				else{
					rMsg(data.msg,'error');
					btn.goLoad2({load:false});
				}
			},'json').fail( function(xhr, textStatus, errorThrown) {
						   alert(xhr.responseText);
						});	
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
	
	<?php elseif($use_js == 'dailyFilesJS'): ?>
		$('#show-file-btn').click(function(){
			var date = $('#file_date').val();
			var btn = $(this);
			btn.goLoad2();
			$.post(baseUrl+'araneta/get_file','date='+date,function(data){
				$('#daily-div').html(data.daily);								
				btn.goLoad2({load:false});
			},'json').fail( function(xhr, textStatus, errorThrown) {
			 	alert(xhr.responseText);
				btn.goLoad2({load:false});
			});
		});	
		$('#regen-btn').click(function(){
			var date = $('#file_date').val();
			var btn = $(this);
			btn.goLoad2();
			$.post(baseUrl+'araneta/regen_file','date='+date,function(data){
				// alert(data);
				// $('#daily-div').html(data);
				if(data.error == 0){
					rMsg(data.msg,'success');
					$.post(baseUrl+'araneta/get_file','date='+date,function(data2){
						$('#daily-div').html(data2.daily);										
						btn.goLoad2({load:false});
					},'json').fail( function(xhr, textStatus, errorThrown) {
					 	alert(xhr.responseText);
						btn.goLoad2({load:false});
					});
				}
				else{
					rMsg(data.msg,'error');
					btn.goLoad2({load:false});
				}
			// });
			},'json');
		});

	<?php endif; ?>	
});
</script>