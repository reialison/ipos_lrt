<?php
  $finish = (isset($finish)) ? $finish : false;
?>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<div class="clearfix"></div>
<div class="">
   <div class="page-content">
     <div class="row">
        <div class="col-md-12">
          <div class="portlet light port_neg20">
            <div class="portlet-title">
                                        <div class="caption kitchen_caption">
                                            <label class="btn btn-lg btn-circle btn-outline green-sharp kitchen_label">
                                                <i class="fa fa-external-link-square  kitchen_icon "></i>
                                                <span class="caption-helper"></span> 
                                                <span class="caption-subject  bold uppercase em_title">Dispatch Display</span>
                                           </label>
                                        </div>
            </div>

            <div class="portlet-body">
               <div class="tabbable-custom custom_blue">
                 <ul class='nav nav-tabs '>
                    <li class='active'>
                       <a href='#tab_1' data-toggle='tab'> For Dispatch </a>
                    </li>
                    <li>
                       <a href='#tab_2' data-toggle='tab'> Completed </a>
                    </li>
                 </ul>
                 <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                       <!-- TASK COMMENTS -->
                          <div class="col-md-12">
                             <div class="todo-tasklist_ongoing">
                               
                             </div>
                          </div>
                   
                    </div>
                    <div class="tab-pane" id="tab_2">
                         <div class="col-md-12">
                             <div class="todo-tasklist_completed">
                                  
                             </div>
                          </div>
                   
                    </div>
                 </div>
               </div>
            </div>
          </div>
       </div>
    </div>
  </div>
</div>


    <div class="modal fade draggable-modal" id="dispatch_modal" tabindex="-1" role="basic" aria-hidden="true">
     <div class="modal-dialog">
        <div class="modal-content">
           <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
             <h4 class="modal-title">Are you sure want to mark complete this order?</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                  <button type="button" class="btn dark btn-outline" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
                  <button type="button" class="btn green dispatch_btn" ><i class="fa fa-check-circle"></i> Dispatch</button>
            </div>
        </div>
         <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
  </div>
<!--visibility hidden-->
  <div style="visibility:hidden;">
    <div class="col-md-3 clonable_div " ></div>

  </div>
<!--visibility hidden-->
  <script>
    $(function(){
        setInterval(function(){
          // $('.todo-tasklist_ongoing').html('');
          // $('.todo-tasklist_completed').html('');

          // $('#dispatch_modal').find('.modal-body').html('');
          // $('#dispatch_modal').find('.modal-footer').html('');

          capture_order();
          show_completed_order();

        },3000);     

    });

     /**
        display_order_details() - after capturing sales id, fill in the cloned divs with the details of the order
        @jx 03012019
       **/

      function display_order_details(){
        $(document).find('div.clonable_div:empty').each(function(i,e){
          var  sales_id = $(e).attr('ref');
          var this_element = $(this);
          // console.log(e);
          if( typeof sales_id !== "undefined"){
          console.log("testxxx: "+ sales_id);

            $.post("<?=base_url().'display/get_dispatch_view/'?>"+sales_id,function(data){
                  // console.log(data);
                  $('div[ref='+sales_id+']').html(data.code);
                   play_notif('dispatched.mp3',2);
                  // $('.order-view-list').html(data.code);
                  // $('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
                  // alert(data);
                // });
            },'json');
          }
          // console.log(sales_id);
          // $(this).html('test');
          // $(document).find('.todo-tasklist-item-text[ref='+sales_id+']').html('test');
          // $().html();
        });
      }

       /**
        display_completed_order_details() - after capturing sales id, fill in the cloned divs with the details of the order
        @jx 03042019
       **/

      function display_completed_order_details(){
        $(document).find('div.clonable_div:empty').each(function(i,e){
          var  sales_id = $(e).attr('ref');
          var this_element = $(this);
          console.log(e);
          if( typeof sales_id !== "undefined"){
          console.log("testxxx: "+ sales_id);

            $.post("<?=base_url().'display/get_dispatch_view/'?>"+sales_id,function(data){
                  console.log(data);
                  $('div[ref='+sales_id+']').html(data.code);
                   // play_notif('dispatched.mp3',2);
                  // $('.order-view-list').html(data.code);
                  // $('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
                  // alert(data);
                // });
            },'json');
          }
          // console.log(sales_id);
          // $(this).html('test');
          // $(document).find('.todo-tasklist-item-text[ref='+sales_id+']').html('test');
          // $().html();
        });
      }


      /**
        capture_order() - get all sales order for kitchen display
        @jx 03012019
      **/
      function capture_order(){
        $.post("<?=base_url().'display/get_ready_to_dispatch_display'?>",{},function(e){
          console.log(e);
          var parse_data = JSON.parse(e);
          // console.log("capture order:"+parse_data);
          $.each(parse_data.kitchen_sales_id,function(i,d){

            if(i%3 == 0 && $('#tab_1 .clonable_div').length %3 == 0){
              $('.todo-tasklist_ongoing').append('<div class="clearfix"></div>');
            }

            if($('[ref='+d.sales_id+']').length == 0){
              var cloned_div = $('.clonable_div:last').clone();
              console.log("lll: "+d.sales_id);
               cloned_div.attr('ref',d.sales_id);
               cloned_div.find('div.todo-tasklist-item-text').attr('ref',d.sales_id);
               $('.todo-tasklist_ongoing').append(cloned_div);
              display_order_details();
            }else{
              $.post("<?=base_url().'display/get_dispatch_view/'?>"+d.sales_id,function(data){
                    // console.log(data);
                    $('div[ref='+d.sales_id+']').html(data.code);
                     play_notif('dispatched.mp3',2);
                    // $('.order-view-list').html(data.code);
                    // $('.order-view-list .body').perfectScrollbar({suppressScrollX: true});
                    // alert(data);
                  // });
              },'json');
             // console.log(d.sales_id);
            }
          });


          // console.log(parse_data.kitchen_sales_id);

        });
      }

     /**
        show_completed_order() - get all sales completed order
        @jx 03012019
      **/
      function show_completed_order(){
        $.post("<?=base_url().'display/get_for_completed_display'?>",{},function(e){
          var parse_data = JSON.parse(e);
          // console.log(parse_data);
          $.each(parse_data.kitchen_sales_id,function(i,d){

            if(i%3 == 0 && $('#tab_2 .clonable_div').length %3 == 0){
              $('.todo-tasklist_completed').append('<div class="clearfix"></div>');
            }
            
            if($('[ref='+d.sales_id+']').length == 0){
              var cloned_div = $('.clonable_div:last').clone();
              // console.log("lll: "+d.sales_id);
               cloned_div.attr('ref',d.sales_id);
               cloned_div.find('div.todo-tasklist-item-text').attr('ref',d.sales_id);
               $('.todo-tasklist_completed').append(cloned_div);
                display_completed_order_details();
            }
             // console.log(d.sales_id);
          });


          // console.log(parse_data.kitchen_sales_id);

        });
      }

     
      function check_finish(){
        var is_finish = $('#is_finish').val();

        if(is_finish){

          swal({
            title: "Do you want",
            type:'success',
            text: "Your order is now on process and will be ready in a minute.",
            timer: 7000,
            showConfirmButton: false,

          });
          setTimeout(function(){
            window.location.href= "<?=base_url().'app/'?>";
          },4000);
        // $.mobile.activePage.find( "#left-panel" ).panel( "open" );
          
        }      
      }

     $(document).on('click','.completed',function(){
         var ref = $(this).attr('ref');
        console.log(ref);
         $.post(baseUrl()+'display/complete_order',{'ajax':true,'ref':ref},function(e){
          console.log(e);
            var json = JSON.parse(e);
            if(json.status){
              // $('div[ref='+ref+']').find('.pending').hide();
              // $('div[ref='+ref+']').find('.for_dispatch').hide();
              // $('div[ref='+ref+']').find('.completed').show();

              // $('.todo-tasklist_completed').append($('div[ref='+ref+']').clone());

              // $('div[ref='+ref+']').find('.portlet').removeClass('green-sharp').addClass('green-meadow');
              
              // $('.todo-tasklist_ongoing').find('div[ref='+ref+']').fadeOut();             
              

              swal('','Order has been completed.','success');

              $('.todo-tasklist_ongoing').html('');
              $('.todo-tasklist_completed').html('');

              $('#dispatch_modal').find('.modal-body').html('');
              $('#dispatch_modal').find('.modal-footer').html('');

              capture_order();
              show_completed_order();
            }else{
                swal('Oops!','Please try again later.','warning');
            }

         });
       
    });

   // $(document).ready(function(){
   //        $.mobile.activePage.find( "#left-panel" ).panel( "open" );

   // })
  </script>