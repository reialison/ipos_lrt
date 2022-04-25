<!-- <div class="modal fade" id="basic<?=$item_id?>" tabindex="-1" role="basic" aria-hidden="true"> -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close_btn" data-dismiss="modal" aria-hidden="true"><span style="margin-left:-5px">X</span></button>
                <h4 class="modal-title" style="color:#000"><?//=$mod_title?></h4>
            </div>
            <div class="modal-body col-md-12">
              
            </div>
            <div class="modal-footer">
               <div class="modal-body col-md-12">
                <div class="col-md-1">
                <label class="lbl-title">Qty :</label>
                </div>
                <div class="col-md-1">
                  <button class="btn-sm btn_cart_minus" style="background-color: #DD1D21!important;"><i class="fa fa-minus" aria-hidden="true"></i></button>
                </div>
                <div class="col-xs-2 col-md-2">
                  <!-- <ul> -->
                    <!-- <li> -->
                      <input type="number" name="qty" class="qt" id="qt_val" min="1" value="1" />                             
                    <!-- </li> -->
                  <!-- </ul> -->
                </div>
                <div class="col-md-1">
                  <button class="btn-sm btn_cart_plus" style="background-color:#008443!important;"><i class="fa fa-plus"></i></button>
                </div>
               </div>
            <button type="button" data-dismiss="modal" class="btn_submit btn btn-primary mod_btn">Save</button>
            </div>
        </div>
    </div>
  </div>
</div>