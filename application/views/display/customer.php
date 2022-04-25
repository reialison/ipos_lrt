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
            <div class="portlet-title"><div class="caption kitchen_caption">
                                            <label class="btn btn-lg btn-circle btn-outline red-soft kitchen_label">
                                                <i class="fa fa-fire font-red-mint kitchen_icon "></i>
                                                <span class="caption-helper"></span>
                                                <span class="caption-subject font-red-mint bold uppercase em_title">Order Status Display</span>
                                           </label>
                                            </div></div>

            <div class="portlet-body">
               <!-- <div class="tabbable-custom"> -->
                 
                 <div class="row" style="margin: 0px">
                    <div class="col-md-6 portlet box green-sharp">
                      <span class=" bold uppercase em_title">Now Preparing</span>
                       <!-- TASK COMMENTS -->
                          <!-- <div class="row"> -->
                             <div class="portlet-body" id="preparing" style="height:500px">
                               loading...
                             </div>
                          <!-- </div> -->
                   
                    </div>
                    <div class="col-md-6 portlet box green-sharp">
                      <span class="bold uppercase em_title">Now Serving</span>
                         <!-- <div class="row"> -->
                             <div class="portlet-body" id="serving" style="height:500px">
                                  loading...
                             </div>
                          <!-- </div> -->
                   
                    </div>
                 </div>
               <!-- </div> -->
            </div>
          </div>
       </div>
    </div>
  </div>
</div>

  
  <script>
    $(function(){
        setInterval(function(){
          customer_order_status('kitchen',$('#preparing'));
          customer_order_status('dispatch',$('#serving'));
        },3000);

       function customer_order_status(type,$obj){
          $.post("<?=base_url().'display/customer_order_status/'?>"+type,function(data){            
                  $($obj).html(data.code);
            },'json');
       }
       
    });

   // $(document).ready(function(){
   //        $.mobile.activePage.find( "#left-panel" ).panel( "open" );

   // })
  </script>