<script>
$(document).ready(function(){
   <?php if($use_js == 'shiftJs'): ?>
        $('.loads-div').rLoad({url:'shift/time'});
        $('#time-btn').click(function(){
            $('.loads-div').rLoad({url:'shift/time'});
            return false;
        }); 
        $('#cashier-btn').click(function(){
            window.location = baseUrl+'cashier';
            return false;
        });
        $('#lock-btn').click(function(){
            window.location = baseUrl+'site/go_logout';
            return false;
        });	
        
   <?php elseif($use_js == 'timeJs'): ?>
        $('#start-shift-btn').click(function(){
            $('.loads-div').shiftLoad({url:'shift/start_amount'});
            return false;
        });
        $('#end-shift-btn').click(function(){
            $.callManager({
                success : function(){
                    window.location = baseUrl+'manager';
                }
            });
            return false;
        }); 
        function startTime(){
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                var day_or_night = (h > 11) ? "PM" : "AM";
                if (h > 12)
                    h -= 12;
                $('#timer').html(h + ":" + m + ":" + s + " " + day_or_night);
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
        startTime();
   <?php elseif($use_js == 'startAmountJs'): ?>
        $('#amount-input').keypress(function(event){
          if(event.keyCode == 13){
           $('#enter-amount-btn').trigger('click');
          }
        });
        $('#enter-amount-btn').click(function(){
            var amt   = $('#amount-input').val();
            var amt1  = $('#deno-btn-1 .deno-qty').text();
            var amt2  = $('#deno-btn-2 .deno-qty').text();
            var amt3  = $('#deno-btn-3 .deno-qty').text();
            var amt4  = $('#deno-btn-4 .deno-qty').text();
            var amt5  = $('#deno-btn-5 .deno-qty').text();
            var amt6  = $('#deno-btn-6 .deno-qty').text();
            var amt7  = $('#deno-btn-7 .deno-qty').text();
            var amt8  = $('#deno-btn-8 .deno-qty').text();
            var amt9  = $('#deno-btn-9 .deno-qty').text();
            var amt10 = $('#deno-btn-10 .deno-qty').text();
            var amt11 = $('#deno-btn-11 .deno-qty').text();
            var btn = $(this);
            btn.goLoad2();
            if($.isNumeric(amt) && amt > 0){
                var formData = 'amount='+amt+'&amt1='+amt1+'&amt2='+amt2+'&amt3='+amt3+'&amt4='+amt4+'&amt5='+amt5+'&amt6='+amt6+'&amt7='+amt7+'&amt8='+amt8+'&amt9='+amt9+'&amt10='+amt10+'&amt11='+amt11;
                $.post(baseUrl+'shift/timeIn',formData,function(data){
                    // console.log(data);
                    // return false;
                    if(data.error == ""){
                        // $('.loads-div').rLoad({url:'cashier/counter/takeout'});
                        rMsg('Shift Started.','success');                        
                        window.location.replace(baseUrl+"cashier");
                    }
                        btn.goLoad2({load:false});
                // });
                },'json');
            }
            else{
                rMsg('Input a valid amount','error');
                btn.goLoad2({load:false});
            }
            return false;
        }); 
        $('#deno-enter-amount-btn').click(function(){
            var amt   = $('#amount-input').val();
            // alert(amt);
            // return false;
            var amt1  = $('#deno-btn-1 .deno-qty').text();
            var amt2  = $('#deno-btn-2 .deno-qty').text();
            var amt3  = $('#deno-btn-3 .deno-qty').text();
            var amt4  = $('#deno-btn-4 .deno-qty').text();
            var amt5  = $('#deno-btn-5 .deno-qty').text();
            var amt6  = $('#deno-btn-6 .deno-qty').text();
            var amt7  = $('#deno-btn-7 .deno-qty').text();
            var amt8  = $('#deno-btn-8 .deno-qty').text();
            var amt9  = $('#deno-btn-9 .deno-qty').text();
            var amt10 = $('#deno-btn-10 .deno-qty').text();
            var amt11 = $('#deno-btn-11 .deno-qty').text();
            var btn = $(this);
            btn.goLoad2();
            if($.isNumeric(amt) && amt > 0){
                var formData = 'amount='+amt+'&amt1='+amt1+'&amt2='+amt2+'&amt3='+amt3+'&amt4='+amt4+'&amt5='+amt5+'&amt6='+amt6+'&amt7='+amt7+'&amt8='+amt8+'&amt9='+amt9+'&amt10='+amt10+'&amt11='+amt11;
                $.post(baseUrl+'shift/timeIn',formData,function(data){
                    // console.log(data);
                    // return false;
                    if(data.error == ""){
                        // $('.loads-div').rLoad({url:'cashier/counter/takeout'});
                        rMsg('Shift Started.','success');                        
                        window.location.replace(baseUrl+"cashier");
                    }
                        btn.goLoad2({load:false});
                // });
                },'json');
            }
            else{
                rMsg('Input a valid amount','error');
                btn.goLoad2({load:false});
            }
            return false;
        });
        $('#cancel-amount-btn').click(function(){
            $('.loads-div').rLoad({url:'shift/time'});
            return false;
        });
        $("#count-btn").click(function(){
            if($(this).hasAttr('target')){
                var type = $(this).attr('target');
                var amt = $('#count-input').val();
                var ref = "";
                // $('#amount-input').val('500');
                if(amt != ""){
                    var btn = $('.deno-sel');
                    var val = parseFloat(btn.attr('val'));
                    if($.isNumeric(val)){
                        var qty = parseFloat(amt);
                        // alert(val);
                        // return false;
                        // var a = qty * val;
                        addToCountCart(type,(qty * val),val);
                        // var s = $('#amount-input').val(a);
                        var lastQty = parseFloat(btn.find('.deno-qty').text());
                        btn.find('.deno-qty').number(qty+lastQty,2);
                        // $('#amount-input').val(t);
                    }
                    else{
                        rMsg('Select a Cash Amount.','error');
                    }
                }
                else{
                    rMsg('Invalid amount.','error');
                }
            }
            return false;
        });
        $('#extra-amount-btn').click(function(){
            // var type = $(this).attr('ref');
            var type = 'cash';
            var label = type+' Amount';
            $('#amt-label').text(label.toUpperCase());
            $("#count-btn").attr('target',type);
                // $('#ref-input').prop('disabled', true);
                // $('.refy').hide();
                // if(!$(this).hasClass('cash-only')){
                    $('#amt-label').text('Quantity');
                    $("#shift-count-cash-div").modal('show');
                    // countsDivs('count-cash');
                    showDenominations();
                    // $("#count-btn").removeClass('cash-only');
                // }
                // else{
                    // $("#count-btn").addClass('cash-only');
                // }
            // }
            resetInput();
            return false;
        });
        $('#cash-go-back-btn').click(function() {
            $('#shift-count-cash-div').modal('hide');
        });
        // function countsDivs(type){
        //     $('.counts-div').hide();
        //     $('#'+type+'-div').show();
        // }
        function showDenominations(){
            $.post(baseUrl+'shift/shift_show_denominations/',function(data){
                // console.log(data);
                $('#sdeno-div').html(data.code);
                $('#sdeno-div').perfectScrollbar({suppressScrollX: true});
                $.each(data.ids,function(key,id){
                    $('#deno-btn-'+id).click(function(){
                        $('.orders-list-div-btnish').removeClass('bg-green');
                        $('.orders-list-div-btnish').removeClass('deno-sel');
                        $('#deno-btn-'+id).addClass('bg-green');
                        $('#deno-btn-'+id).addClass('deno-sel');
                        resetInput();
                        return false;
                    });
                    $('#del-cash-'+id).click(function(){
                        var val = $(this).attr('val');
                        $.post(baseUrl+'drawer/del_cash_in_count_cart/'+val,function(data){
                            $('#deno-btn-'+id+' .deno-qty').number(0,2);
                            totalCounts('cash');
                            resetInput();
                            $.each(data.ids,function(key,wid){
                                $('#cash-del-btn-'+wid).parent().parent().remove();
                            });
                        },'json');
                        return false;
                    });
                });
            },'json');
            // alert(data);
            // });
        }
        function resetInput(){
            $('#count-input').val('');
            $('#amount-input').val('');
        }
        function addToCountCart(type,amt,ref){
            var formData = 'type='+type+'&amount='+amt+'&ref='+ref;
            $.post(baseUrl+'wagon/add_to_wagon/count_cart',formData,function(data){
                var row = data.items;
                var id = data.id;

                // if(row.type == 'gift' || row.type=='check')
                createRow(id,row);
                totalCounts(row.type);
                resetInput();
            },'json');
            // alert(data);
            // });
        }
        function createRow(id,row){
            var sDivRow = $('<div/>').attr({'class':'row orders-list-div-btnish'}).css({'padding-right':'20px'});
            var sDivCol1 = $('<div/>').attr({'class':'col-md-8'}).appendTo(sDivRow);
            $('<h4/>').css({'margin-left':'10px'}).number(row.amount,2).appendTo(sDivCol1);
            $('<h5/>').css({'margin-left':'10px'}).text(row.ref).appendTo(sDivCol1);
            var sDivCol2 = $('<div/>').attr({'class':'col-md-4'}).appendTo(sDivRow);
            // if(row.type != 'cust-deposits'){
            if(row.type == 'cash'){
                $('<button/>').attr({'id':'cash-del-btn-'+id,'class':'btn-block manager-btn-red'})
                              .css({'margin-top':'10px'})
                              .html('<i class="fa fa-times"></i>')
                              .click(function(){
                                var btn = $(this);
                                $.post(baseUrl+'wagon/delete_to_wagon/count_cart/'+id,function(data){
                                    totalCounts(row.type);
                                    resetInput();
                                    btn.parent().parent().remove();
                                },'json');
                                // alert(data);
                                // });
                                return false;
                              })
                              .appendTo(sDivCol2);
            }

            $('#'+row.type+'-list').append(sDivRow);
            $('#'+row.type+'-list').perfectScrollbar({suppressScrollX: true});
        }
        function totalCounts(type){
            $.post(baseUrl+'drawer/count_totals/'+type,function(data){
                $('*[ref="'+type+'"]').find('.amt').number(data.total,2);
                $('#amount-input').val(data.overall);
                // checkCart();
            // });
            },'json');
        }
        $('#count-inputs').focus(function(){
            $('#count-key-tbl').attr('target','#'+$(this).attr('id'));
        });
   <?php endif; ?>
});
</script>