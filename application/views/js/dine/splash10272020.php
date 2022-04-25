<script>
$(document).ready(function(){
   <?php if($use_js == 'splashJs'): ?>
        $('#splashLoad').rLoad({url:baseUrl+'splash/commercial'});
        setInterval(function() {
            $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                if(data.ctr > 0){
                    window.location = baseUrl+'splash/transactions';
                }
            },'json');
        }, 1000);
   <?php elseif($use_js == 'splashComJs'): ?> 
        var height = $(document).height();
        var width = $(document).width();
        var currentBackground = 0;
        var backgrounds = [];
        var ctr = 0;
        $('.splash-imgs').each(function(){
            backgrounds[ctr] = $(this).val();
            ctr++;
        });
        $('.splash-img-div').height(height).width(width).css({
            "background": "url("+backgrounds[0]+") no-repeat center top",
            // "width":"100%",
            // "display":"block"
            // "height":"700px",
            // "-webkit-background-size": "contain",
            // "-moz-background-size": "contain",
            // "-o-background-size": "contain",
            "background-size": "100% 100%"
        });
        if(backgrounds.length > 1){
            setTimeout(changeBackground, 5000);  
        }
        function changeBackground() {
            currentBackground++;
            if(currentBackground > (ctr-1) ) currentBackground = 0;
            $('.splash-img-div').fadeOut(1500,function() {
                $('.splash-img-div').css({
                    "background": "url("+backgrounds[currentBackground]+")  no-repeat center top",
                    // "-webkit-background-size": "fill",
                    // "-moz-background-size": "fill",
                    // "-o-background-size": "fill",
                    // "background-size": "fill"
                    "background-size": "100% 100%"
                });
                $('.splash-img-div').fadeIn(1500);
            });
            setTimeout(changeBackground, 5000);
        }
   <?php elseif($use_js == 'endtransJs'): ?> 
        $('#splashLoad').rLoad({url:baseUrl+'splash/end_trans_images'});
        setInterval(function() {
            // $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                // if(data.ctr > 0){
                    window.location = baseUrl+'splash/';
                // }
            // },'json');
        }, 5000);
   <?php elseif($use_js == 'splashTransJs'): ?> 
        $("body").css("overflow", "hidden");
        var height = $(document).height();
        var width = $(document).width();
        var img_set = $('#img_vid').val();
        var vid_src = $('#video-splash').val();
        var currentBackground = 0;
        var backgrounds = [];
        var ctr = 0;
        $('.splash-imgs').each(function(){
            backgrounds[ctr] = $(this).val();
            ctr++;
        });
        $('.splash-img-div').height(height-200).width(width-650).css({
            "background": "url("+backgrounds[0]+")  no-repeat center top",
            "-webkit-background-size": "contain",
            "-moz-background-size": "contain",
            "-o-background-size": "contain",
            "background-size": "contain",
            // "position": "fixed;"
            
        });


        if(img_set == 0){
            setTimeout(changeBackground, 5000);          
        }
        else{
            // $(".splash-vid-div").append('<center><video width="838" class="col-md-12" loop autoplay style="margin-top:80px;right: 0;bottom: 0;min-width: 100%; min-height: 100%;"><source src="'+vid_src+'" type="video/mp4"></video></center>');
        //     // alert("bb");
        }


        function changeBackground() {
            currentBackground++;
            if(currentBackground > (ctr-1) ) currentBackground = 0;
            $('.splash-img-div').fadeOut(1500,function() {
                $('.splash-img-div').css({
                    "background": "url("+backgrounds[currentBackground]+")  no-repeat center top",
                    "-webkit-background-size": "contain",
                    "-moz-background-size": "contain",
                    "-o-background-size": "contain",
                    "background-size": "contain",
                    
                });
                $('.splash-img-div').fadeIn(1500);
            });
            setTimeout(changeBackground, 5000);
        }

        setInterval(function() {
            $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                if(data.ctr == 0){
                    window.location = baseUrl+'splash';
                }
            },'json');
            transTotal();
            get_trans();
        }, 1000);
        function get_trans(){
            $.post(baseUrl+'splash/get_counter', function (data) {
                var head = data.counter;
                $('#trans-header').html(head.type);
                $('#trans-datetime').html(head.datetime);
                $('#transBody').html(data.code);
                $('#transBody').perfectScrollbar({suppressScrollX: true});
            },'json');
        }
        function transTotal(){
            $.post(baseUrl+'cashier/total_trans',function(data){
                var total = data.total;
                var discount = data.discount;
                $("#total-txt").number(total,2);
                $("#discount-txt").number(discount,2);
            },'json');
        }
   <?php elseif($use_js == 'momentsplashJs'): ?> 
        $("body").css("overflow", "hidden");
        // var height = $(document).height();
        // var width = $(document).width();
        var img_set = $('#img_vid').val();
        var vid_src = $('#video-splash').val();
        var currentBackground = 0;
        var backgrounds = [];
        var ctr = 0;
        $('.splash-imgs').each(function(){
            backgrounds[ctr] = $(this).val();
            ctr++;
        });
        // alert(height);
        $('.splash-img-div').height(768).width(475).css({
            "background": "url("+backgrounds[0]+")  no-repeat center top",
            "-webkit-background-size": "100% 100%",
            "-moz-background-size": "100% 100%",
            "-o-background-size": "100% 100%",
            "background-size": "100% 100%",
            // "background-size":"100% 100%",
            "position": "fixed;"
            
        });


        if(img_set == 0){
            setTimeout(changeBackground, 5000);          
        }
        else{
            // $(".splash-vid-div").append('<center><video width="838" class="col-md-12" loop autoplay style="margin-top:80px;right: 0;bottom: 0;min-width: 100%; min-height: 100%;"><source src="'+vid_src+'" type="video/mp4"></video></center>');
        //     // alert("bb");
        }


        function changeBackground() {
            currentBackground++;
            if(currentBackground > (ctr-1) ) currentBackground = 0;
            $('.splash-img-div').fadeOut(1500,function() {
                $('.splash-img-div').css({
                    "background": "url("+backgrounds[currentBackground]+")  no-repeat center top",
                    "-webkit-background-size": "100% 100%",
                    "-moz-background-size": "100% 100%",
                    "-o-background-size": "100% 100%",
                    "background-size": "100% 100%",
                    
                });
                $('.splash-img-div').fadeIn(1500);
            });
            setTimeout(changeBackground, 5000);
        }

        setInterval(function() {
            $.post(baseUrl+'splash/check_trans', function (data) {
                // $("#test").text(data.ctr);
                if(data.ctr == 0){
                    window.location = baseUrl+'splash/end_trans';
                }
            },'json');
            transTotal();
            get_trans();
        }, 1000);
        function get_trans(){
            $.post(baseUrl+'splash/get_counter', function (data) {
                var head = data.counter;
                $('#trans-header').html("Your Cravings");
                // $('#trans-datetime').html(head.datetime);
                $('#transBody').html(data.code);
                $('#transBody').perfectScrollbar({suppressScrollX: true});
            },'json');
        }
        function transTotal(){
            $.post(baseUrl+'cashier/total_trans',function(data){
                var total = data.total;
                var discount = data.discount;
                var grand_total = total - discount;
                var cash = data.payment_amount;
                var change = cash - grand_total;
                
                $("#sub-total-txt").number(total,2);
                $("#discount-txt").number(discount,2);
                $("#item-total-txt").number((total - discount),2);
                $("#cash-txt").number((cash),2);
                if(cash){
                    $("#change-txt").number((change),2);
                }
            },'json');
        }
   <?php endif; ?>
});
</script>