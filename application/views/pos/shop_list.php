<body>
  <div data-role="page" id="features" class="secondarypage" data-theme="b">
    <div data-role="header" data-position="fixed" class="ui-panel-page-content-position-left ui-panel-page-content-display-reveal ui-panel-page-content upper-header">
      <div class="nav_left_button col-md-4">
        <img src="<?=base_url().'img/food_rest.png'?>" class="rest_logo"> <label  class="rest_logo_label">IPOS</label>
      </div>
      <div class="nav_right_button_new cart-main col-md-4"> 
        <!-- <div> -->
          <a href="#" id="recall_btn" rel="external" class="btn blue btn-primary" style="width: 120px;margin-right:5px">View Table</a> 
          <a href="#" id="checkout_cart" class="btn blue btn-primary" rel="external" style="width: 80px;">Submit</a>    
        <!-- </div>           -->
        <div id="cart-main">
          <div>
            <ul id="mini_cart">
              <?php 
              if(isset($item_cart) && !empty($item_cart)){
                foreach($item_cart as $i_c){
                  $img_src =  base_url().'img/noimage.jpg';
                  $qty = (isset($i_c['qty'])) ? $i_c['qty'] : 1 ;
                  if(!empty($i_c['item_img'])){
                    $img_src = base_url().$i_c['item_img'];
                  } ?>
                   <!-- <li ref="<?= $i_c['item_id']; ?>"> -->
                  <li>
                    <div class="description">
                    <div class="closeit"><i class="fa fa-trash" style="font-size:20px"></i></div>
                    <div class="clickedit" id="<?=$i_c['item_id']?>"><i class="fa fa-pencil editedit" ></i></div>                            
                    <p>
                      <!-- <label align="left"><span class='label label-nature'><?= $i_c['unit_price_label'] ?></span>&nbsp;&nbsp;&nbsp;  <?=$i_c['item_name']?>  
                        <?php
                        if(!empty($i_c['modname'])){
                          $modname = explode(',', $i_c['modname']);
                          foreach ($modname as $key) {
                            if($key != null){
                            ?>
                                <span class='label label-nature'><?= $key ?></span>&nbsp;
                            <?php
                            }
                          }
                        }
                        ?>
                      </label> -->
                      <input type="hidden" name="srp_cart" value="<?= $i_c['unit_price'] ?>" />
                      <div class="position edit_qty<?=$i_c['item_id']?>" ref="<?=$i_c['item_id']?>">
                        <span class="sign">x</span>
                        <button class="btn-sm btn_cart_minus" style="background-color: #3598dc!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                        <input type="number" name="qty" min="1" value="<?=$qty?>" />
                        <button class="btn-sm btn_cart_plus" style="background-color:#3598dc!important;"><i class="fa fa-plus"></i></button>
                    </div>
                    </p>
                    </div>
                  </li>
                  <?php 
                }
              } ?>               
            </ul>
            <div class="bottom">
              <span class="total">Total: 41.00</span>
              <div class="clearfix"></div>
              <a href="#" id="checkout_cart" rel="external" style="width: 150px;">Submit <i class="fa fa-angle-right"></i></a>
            </div>
             <div class="bottom2"></div>
          </div>
        </div>
      </div>
       <div class="col-md-4" style="float: right;margin-right: -28px ">
        <form class="search" method="GET" action="<?=base_url().'app/search'?>">
          <input type="search" name="search" placeholder="Search entire store...">
          <button type="submit" id="search_btn"><i class="fa fa-search"></i></button>
       </form>
      </div>
        <!-- <label  class="rest_logo_name">iPos OrderApp</label> -->
    </div>
    <!-- <div data-role="header" data-position="fixed" style="margin-top: 50px;" class="ui-panel-page-content-position-left ui-panel-page-content-display-reveal ui-panel-page-content-open row"> -->
      
      <!-- <div class="div_left_head">
        <h3 class="left_head">Restaurant Menu</h3>
      </div> -->
     
    <!-- </div> -->
    <!-- content -->
    <div data-role="main" class="ui-content overflw">
      <div class="row">
        <div class="col-md-12 item_slide">
          <div role="main" class="ui-content overflw main_b">
           <div class="portlet-body">
              <div class="tabbable tabbable-tabdrop">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab1">
                    <ul class="features_list_detailed">
                      <?php 
                      if(empty($items)){ ?>
                        <div class="col-md-12">
                            There are no available items on this category.
                        </div>
                      <?php  
                      }else{ ?>
                        <div class="col-md-11 col-md-offset-1">
                          <?php 
                          // echo $category;die
                          if($category != "$1"){
                            foreach ($menu_cats as $cat_key => $cat_val) {
                              if($cat_val->menu_cat_id == $category){
                                foreach($items[$cat_val->menu_cat_id] as $item){ 
                                  $modifiers = array();
                                  $div_ctr = count($items[$cat_val->menu_cat_id]);
                                  if($div_ctr == 2){
                                    $ctr = 6;
                                  }elseif($div_ctr == 1){
                                    $ctr = 12;
                                  }elseif($div_ctr > 3){
                                    $ctr = 4;
                                  }else{
                                    $ctr = 4;
                                  }
                                  $item_id = $item->menu_id ;
                                  $modifiers = $this->Pos_app->get_by_modifier($item_id);
                                    ?>
                                  <div class="modal fade" id="basic<?=$item_id?>" ref="<?=$item_id?>" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="">X</span></button>
                                                <h4 class="modal-title" style="color:#000"><?//=$mod_title?></h4>
                                            </div>
                                            <div class="modal-body col-md-12">
                                              <?php foreach ($modifiers as $mod_key) {
                                                  ?>
                                                  <div class="col-md-3 ">
                                                    <label class="new-mod-background">
                                                      <input type="checkbox" class="modcheck" name="modifier[]" brefid="<?=$mod_key->mod_id?>" brefname="<?=$mod_key->modifier_name?>" value="<?=$mod_key->mod_id?>"/>
                                                      <span><?=$mod_key->modifier_name?></span>
                                                    </label>
                                                 </div>
                                               <?php } ?>
                                               <div class="modal-body col-md-12">
                                                <div class="col-md-1">
                                                <label class="lbl-title">Qty :</label>
                                                </div>
                                                <div class="col-md-1">
                                                  <button class="btn-sm btn_cart_minus" style="background-color: #3598dc!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-xs-2 col-md-2">
                                                  <!-- <ul> -->
                                                    <!-- <li> -->
                                                      <input type="number" name="qty" class="qt" id="qt_val" min="1" value="1" />                             
                                                    <!-- </li> -->
                                                  <!-- </ul> -->
                                                </div>
                                                <div class="col-md-1">
                                                  <button class="btn-sm btn_cart_plus" style="background-color:#3598dc!important;"><i class="fa fa-plus"></i></button>
                                                </div>
                                               </div>
                                            </div>
                                            <div class="modal-footer">
                                              <div class="col-md-12">
                                                  <div class="add_info"><span style="font-size:20px;color:#000;float:left">
                                                    Remarks: </span>
                                                    <textarea name="remarks" class="remarks" style="font-size:25px!important"></textarea>
                                                  </div>
                                                </div>
                                            <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary mod_btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                  </div>

                                  <div class="modal fade" id="nomodi<?=$item_id?>" ref="<?=$item_id?>" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog"  style="width: 350px!important;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="">X</span></button>
                                                <h4 class="modal-title" style="color:#1f95c3;font-size:23px;font-weight:600"><?=$item->menu_name?></h4>
                                            </div>
                                            <div class="modal-body col-md-12">                      
                                               <div class="modal-body col-md-12">
                                                <div class="col-md-2">
                                                <label class="lbl-title">Qty :</label>
                                                </div>
                                                <div class="col-md-2">
                                                  <button class="btn-sm btn_cart_minus" style="background-color: #3598dc!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-xs-4 col-md-4">
                                                    <input type="number" name="qty" class="qt" id="qt_val" min="1" value="1" />
                                                </div>
                                                <div class="col-md-2">
                                                  <button class="btn-sm btn_cart_plus" style="background-color:#3598dc!important;"><i class="fa fa-plus"></i></button>
                                                </div>
                                               </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="col-md-12">
                                                  <div class="add_info"><span style="font-size:20px;color:#000;float:left">
                                                    Remarks: </span>
                                                    <textarea name="remarks" class="remarks" style="font-size:25px!important"></textarea>
                                                  </div>
                                                </div>
                                                  <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary no_mod_btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-<?=$ctr?>">
                                    <?php                                          
                                    $unit_price = $item->cost;
                                    $srp_label = number_format($item->cost,2,'.',',');
                                    $srp = number_format($item->cost,2);
                                    // echo $item_id;die();
                                    $img_src =  base_url().'img/noimage.jpg';

                                    if(!empty($item->img_id)){
                                      $img_src = base_url().$item->img_path;
                                    } ?>
                                    <div class="product-info" ref="<?=$item_id?>">
                                      <div class="mt-element-ribbon ">
                                        <div class="ribbon ribbon-border-hor ribbon-clip ribbon-color-danger uppercase product-price">
                                          <div class="ribbon-sub ribbon-clip "></div><?=$srp_label?>
                                        </div>
                                      </div>
                                      <div class="product-thumb"  ref="<?=$item_id?>" imref="<?=$item->img_id; ?>">
                                        <img src="<?= $img_src ?>" class="img-responsive" alt=""/>
                                      </div>
                                      <h4 class="name_search"><a><?=$item->menu_name?></a></h4>
                                      <input type="hidden" id="srp" value="<?=$srp?>">
                                      <input type="hidden" id="desc" value="<?=$item->menu_short_desc;?>">
                                      <?php
                                      if (!empty($modifiers)) { ?>
                                        <div class="marginize_bottom">
                                          <a href="#basic<?=$item_id?>" data-toggle="modal" ref="<?=$item_id?>" imref="<?=$item->img_id; ?>" aref="01" class="shop-btn"> 
                                          <img src="<?=base_url().'img/basket.png'?>" class="basket_img basket"> Buy &nbsp;&nbsp;</a>
                                        </div>
                                      <?php
                                      }else{ ?>
                                        <div class="marginize_bottom">
                                          <a href="#nomodi<?=$item_id?>" ref="<?=$item_id?>" data-toggle="modal" imref="<?=$item->img_id; ?>" aref="02"  class="shop-btn">
                                          <img src="<?=base_url().'img/basket.png'?>" class="basket_img basket"> Buy &nbsp;&nbsp;</a>
                                        </div>
                                        <?php 
                                        } ?>
                                    </div>
                                  </div>
                                  <?php 
                                }
                              }
                            }
                          }else{
                            foreach ($menu_cats as $cat_key => $cat_val) {
                              foreach($items[$cat_val->menu_cat_id] as $item){ 
                                $item_id = $item->menu_id ;
                                $modifiers = $this->Pos_app->get_by_modifier($item_id); ?>
                                <div class="modal fade" id="basics<?=$item_id?>" ref="<?=$item_id?>" tabindex="-1"  role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="">X</span></button>
                                                <h4 class="modal-title" style="color:#000"><?//=$mod_title?></h4>
                                            </div>
                                            <div class="modal-body col-md-12">
                                              <?php foreach ($modifiers as $mod_key) {
                                                  ?>
                                                  <div class="col-md-3 ">
                                                    <label class="new-mod-background">
                                                      <input type="checkbox" class="modcheck" name="modifier[]" brefid="<?=$mod_key->mod_id?>" brefname="<?=$mod_key->modifier_name?>" value="<?=$mod_key->mod_id?>"/>
                                                      <span><?=$mod_key->modifier_name?></span>
                                                    </label>
                                                 </div>
                                               <?php } ?>
                                               <div class="modal-body col-md-12">
                                                <div class="col-md-1">
                                                <label class="lbl-title">Qty :</label>
                                                </div>
                                                <div class="col-md-1">
                                                  <button class="btn-sm btn_cart_minus" style="background-color: #3598dc!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-xs-2 col-md-2">
                                                  <!-- <ul> -->
                                                    <!-- <li> -->
                                                      <input type="number" name="qty" class="qt" id="qt_val" min="1" value="1" />                             
                                                    <!-- </li> -->
                                                  <!-- </ul> -->
                                                </div>
                                                <div class="col-md-1">
                                                  <button class="btn-sm btn_cart_plus" style="background-color:#3598dc!important;"><i class="fa fa-plus"></i></button>
                                                </div>
                                               </div>
                                               <div class="col-md-12">
                                                  <div class="add_info"><span style="font-size:20px;color:#000;float:left">
                                                    Remarks: </span>
                                                    <textarea name="remarks" class="remarks" style="font-size:25px!important"></textarea>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary mod_btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                  </div>

                                  <div class="modal fade" id="nomodi<?=$item_id?>" ref="<?=$item_id?>" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog"  style="width: 350px!important;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="">X</span></button>
                                                <h4 class="modal-title" style="color:#1f95c3;font-size:23px;font-weight:600"><?=$item->menu_name?></h4>
                                            </div>
                                            <div class="modal-body col-md-12">                      
                                               <div class="modal-body col-md-12">
                                                <div class="col-md-2">
                                                <label class="lbl-title">Qty :</label>
                                                </div>
                                                <div class="col-md-2">
                                                  <button class="btn-sm btn_cart_minus" style="background-color: #3598dc!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-xs-4 col-md-4">
                                                    <input type="number" name="qty" class="qt" id="qt_val" min="1" value="1" />
                                                </div>
                                                <div class="col-md-2">
                                                  <button class="btn-sm btn_cart_plus" style="background-color:#3598dc!important;"><i class="fa fa-plus"></i></button>
                                                </div>
                                               </div>
                                            </div>
                                            <div class="modal-footer">
                                               <div class="col-md-12">
                                                  <div class="add_info"><span style="font-size:20px;color:#000;float:left">
                                                    Remarks: </span>
                                                    <textarea name="remarks" class="remarks" style="font-size:25px!important"></textarea>
                                                  </div>
                                                </div>
                                                  <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary no_mod_btn">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                <div class="col-md-4">
                                  <?php     
                                  $unit_price = $item->cost;
                                  $srp_label = number_format($item->cost,2,'.',',');
                                  $srp = number_format($item->cost,2);
                                  $img_src =  base_url().'img/noimage.jpg';
                                  if(!empty($item->img_id)){
                                    $img_src = base_url().$item->img_path;
                                  } ?>
                                  <div class="product-info" ref="<?=$item_id?>">
                                    <div class="mt-element-ribbon ">
                                      <div class="ribbon ribbon-border-hor ribbon-clip ribbon-color-danger uppercase product-price">
                                        <div class="ribbon-sub ribbon-clip "></div>
                                        <?=$srp_label?>
                                      </div>
                                     </div>
                                    <div class="product-thumb"  ref="<?=$item_id?>" imref="<?=$item->img_id; ?>">
                                      <img src="<?= $img_src ?>" class="img-responsive" alt=""/>
                                    </div>
                                    <h4 class="name_search"><a><?=$item->menu_name?></a></h4>
                                    <input type="hidden" id="srp" value="<?=$srp?>">
                                    <input type="hidden" id="desc" value="<?=$item->menu_short_desc;?>">
                                    <?php
                                    if (!empty($modifiers)) {  //echo $item_id;die(); ?>
                                      <div class="marginize_bottom">
                                        <a href="#basics<?=$item_id?>" data-toggle="modal"  ref="<?=$item_id?>" imref="<?=$item->img_id; ?>" aref="01" class="shop-btn"> 
                                        <img src="<?=base_url().'img/basket.png'?>" class="basket_img basket"> Buy &nbsp;&nbsp;</a>
                                      </div>
                                      <?php
                                    }else{ ?>
                                      <div class="marginize_bottom">
                                        <a href="#nomodi<?=$item_id?>" ref="<?=$item_id?>" data-toggle="modal"  imref="<?=$item->img_id; ?>" aref="02"  class="shop-btn">
                                        <img src="<?=base_url().'img/basket.png'?>" class="basket_img basket"> Buy &nbsp;&nbsp;</a>
                                      </div>
                                      <?php
                                    } ?>
                                  </div>
                                </div>
                                <?php 
                              }
                            }
                          }
                        ?>
                        </div>
                        <?php
                      } ?>
                      <!-- </li> -->
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div data-role="main" id="left-panel" class="ui-content ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-inherit ui-panel-animate ui-panel-open" data-position="left">
    <nav class="main-nav">
      <ul class="ullist">
        <?php
        foreach ($menu_cats as $cate_key => $cate_val) { 
          // if($cate_val->menu_cat_name == "DRINKS"){
          //   $icon = "drinks";
          // }elseif( $cate_val->menu_cat_name =="FOODS"){
          //   $icon = "meals";
          // }elseif( $cate_val->menu_cat_name =="DESSERT"){
          //   $icon = "dessert";
          // }elseif( $cate_val->menu_cat_name =="SNACKS"){
          //   $icon = "chips";
          // }elseif( $cate_val->menu_cat_name =="BREAD"){
          //   $icon = "bread";
          // }
          ?>        
          <li class="first_li">
            <a href="javascript:void(0);" onclick="loading('<?=base_url().'app/shop/'.$cate_val->menu_cat_id?>');" rel="external">
              <span class="icon-size">
              <?php if(isset($icon)){ ?>
                <!-- <img src ="<?= base_url().'img/icons/'.$icon.'.png'?>" /> -->
              <?php  } ?>
              </span>
              <span class="classic"><?=$cate_val->menu_cat_name?></span>
            </a>
          </li>
          <?php
        } ?>
      </ul>
    </nav>
  </div>
  <input type="hidden" id="is_finish" value="<?=$finish?>">
  <!-- footer -->
  <div data-role="footer">
    <h4 class="banner_footer">Powered by <img class="btm_logo" src="<?=base_url().'img/header_logo_p1.png'?>"></h4>

  <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>

  <script>
    $(function(){
      update_total();
      $(document).on('click',' .product-thumb',function(){
        $(this).parents('.product-info').find('.shop-btn').click();
      });

      
      $('.btn_submit').removeClass('ui-btn');

      $(document).on('click','.shop-btn',function(e){
        e.preventDefault();
        var ref = $(this).attr('ref');
        var imref = $(this).attr('imref');
        var aref = $(this).attr('aref');

        $.post("<?= base_url().'app/get_qty/'?>",{'ref':ref},function(resp){
            var parse = JSON.parse(resp);
            console.log("p: "+parse.qty);
            console.log("aa: "+parse.remarks);
            setTimeout(function(){
            $('.modal.fade.in').find('input[name=qty]').val(parse.qty);
              
            },200);
              console.log(resp);
              // location.reload();
             // alert(resp);
            // return false;
        });
        
        if(aref == 01){
          var mod = '';
          var mod2 = '';
          var mod3 = '';
          var bref = $(this).attr('bref');
          $('.mod_btn').on('click',function(){
            // $.post("<?= base_url().'app/add_to_cart/'?>", {'ref':ref ,'imref':imref,'bref':mod,'brefname':mod2} ,function(resp){
            //   console.log(resp);
            //    // alert(resp);
            //   // return false;             
            // });
           // var items = [];
           var ref = $(".modal.fade.in").attr('ref');
           var imref = $('.shop-btn').attr('imref');
           var aref = $('.shop-btn').attr('aref');
           var qty = $(".modal.fade.in").find(".qt").val();
           var remarks = $(".modal.fade.in").find(".remarks").val();
           var mod = '';
           var mod2 = '';
            $('.modcheck:checked').each(function(){        
                var values = $(this).val();
                var val = $(this).attr('brefname');
                mod += values+' ';
                mod2 += val+',';
            });
            // items.push({'ref':ref,'qty':qty,'imref':imref,'bref':mod,'brefname':mod2});
            $.post("<?= base_url().'app/update_to_cart_queue/'?>",{'ref':ref,'qty':qty,'imref':imref,'bref':mod,'brefname':mod2,'remarks':remarks},function(resp){

              console.log(resp);
              // location.reload();
             // alert(resp);
            // return false;
            });
          });
        }else{
          $('.no_mod_btn').on('click',function(){
           // var items = [];
           var ref = $(".modal.fade.in").attr('ref');
           var imref = $('.shop-btn').attr('imref');
           var aref = $('.shop-btn').attr('aref');
           // var ,'remarks':remarks = $('.rmk').val();
           var qty = $(".modal.fade.in").find(".qt").val();
           var remarks = $(".modal.fade.in").find(".remarks").val();
            // $.post("<?= base_url().'app/add_to_cart/'?>", {'ref':ref ,'imref':imref,'bref':'','brefname':''} ,function(resp){
                // alert(rm);
                // return false;  
            // })
          console.log(ref);
           // alert(qty);
           // return false;
            // items.push({'ref':ref,'qty':qty,'imref':imref});
            $.post("<?= base_url().'app/update_to_cart/'?>", {'ref':ref,'qty':qty,'imref':imref,'remarks':remarks} ,function(resp){
              // $(".qt").val(1);

              console.log(resp);
              // var ses_items = JSON.parse(items);
              // $("#qt_val").val(items[0].qty);
             // location.reload();
             // console.log(items[0].qty);
            // return false;
            });
          });
        }


          
          

      });

      $(document).on('click',".closeit" , function() {
        var ref = $(this).parents('li').attr('ref');
        $.post("<?= base_url().'app/remove_to_cart/'?>", {'ref':ref} ,function(resp){

          if(resp){

            $("li[ref='"+ref+"']").hide(500);

            setTimeout(function(){
              $("li[ref='"+ref+"']").remove();
              
               count_cart();
               update_total();
            },600);
          }          
       });
      });

      function update_total(){
        var total = 0;
       
        $('ul#mini_cart').find('li').each(function(i,e){
          var srp = $(e).find('input[name=srp_cart]').val();
          var str = parseInt(srp.replace(",",""));
          var qty = $(e).find('input[name=qty]').val();

           total += str * qty;
        // console.log(total); 
            
        });  

        $('span.total').html("Total:  " + total.toFixed(2));

        if(total > 0){

          $('span.header_total').html(""+ total.toFixed(2))
          $(".shop-cart span").html();
        }else{
           $('span.header_total').text('');
        }
        // return total;
      }

      function count_cart(){
          var ctr_i = $('ul#mini_cart li').length;
          $(".shop-cart span:last").html(ctr_i);

      }

      function wave($this){
        var cart = $('.cart-main');
        var imgtodrag = $this.parent().parent('.product-info').find("img").eq(0);
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                    top: imgtodrag.offset().top,
                    left: imgtodrag.offset().left
                })
                .css({
                    'opacity': '0.5',
                    'position': 'absolute',
                    'height': '150px',
                    'width': '150px',
                    'z-index': '10000'
                })
                .appendTo($('body'))
                .animate({
                    'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
                    'height': 75
                }, 1000, 'easeInOutExpo');

            setTimeout(function() {
                cart.effect("shake", {
                    times: 2
                }, 200);
            }, 1500);

            imgclone.animate({
                'width': 0,
                'height': 0
            }, function() {
                // $this.detach()
            });
        }
      }

      $('form').on('submit',function(e){
        e.preventDefault();
        var search = $('input[name=search]').val();
        if(search == ""){
          swal({
            title: "Oopss..",
          text: "No items to be search.",
          type: "warning",
          showCancelButton: false,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Okay",
          closeOnConfirm: true
          });
          return false;
        }else{

          window.location.href= "<?=base_url().'app/search/'?>"+search;
          // console.log(search);
        }
      });

      // $(document).on('click',' .clickedit',function(){
      //     var iid = $(this).attr('id');
      //     $('.edit_qty'+iid).toggle('show');
      // });

    });
      function close_cart(){
        $('a.shop-cart').click();
      }
      function loading(href){
            swal({
            title: "",
            text: "Loading...",
            showConfirmButton: false,
            imageUrl: "../../img/ajax-loader-green.gif"
          });
        window.location.href= href;
      };

      $(document).on('click','.btn_cart_plus',function(e){
        var qt = $('.modal.fade.in').find(".qt");
        console.log(qt.val());
        var num1 = (qt.val() != "" && !isNaN(qt.val())) ? parseInt(qt.val()) : 0;
        qt.val(num1 + 1);
      });

      $(document).on('click','.btn_cart_minus',function(e){
        var qt = $('.modal.fade.in').find(".qt");
        var num1 = (qt.val() != "" && !isNaN(qt.val())) ? parseInt(qt.val()) : 0;
        qt.val(num1 - 1);
      });



      $('#checkout_cart').on('click',function(){
        window.location.href =  "<?= base_url().'app/cart'?>";
      });
       
      $('#recall_btn').on('click',function(){
        window.location.href =  "<?= base_url().'app/recall'?>";
      });

    // alert('clicked me');
    // swal.enableLoading();
  // });
  </script>