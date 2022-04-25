<?php
function kitchenPage(){
	$CI =& get_instance();
	$CI->make->sDiv(array('class'=>'tabbable-line'));
		
		// $CI->make->sDivRow(array('style'=>'margin:0;'));
		// // $CI->make->sDivCol(4);
		// // 	$CI->make->button(fa('fa-bar-chart-o fa-lg fa-fw')."<br> Current Day Summary",array('id'=>'day-report-btn','class'=>'btn-block manager-btn-green double'));
		// // $CI->make->eDivCol();
		// // foreach ($buttons as $id => $text) {
		// // 		$CI->make->sDivCol(4);
		// // 			$CI->make->button($text,array('id'=>$id.'-btn','class'=>'btn-block manager-btn-red-gray double'));
		// // 		$CI->make->eDivCol();
		// // }
		// $CI->make->eDivRow();
		$CI->make->append("<ul class='nav nav-tabs '>   
			<li class='active'>
                                                                    <a href='#tab_1' data-toggle='tab'> Comments </a>
                                                                </li>
                                                                <li>
                                                                    <a href='#tab_2' data-toggle='tab'> History </a>
                                                                </li>
                                                            </ul>");
		$CI->make->append('<div class="tab-content">
                                                                <div class="tab-pane active" id="tab_1">
                                                                    <!-- TASK COMMENTS -->
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <ul class="media-list">
                                                                                <li class="media">
                                                                                    <a class="pull-left" href="javascript:;">
                                                                                        <img class="todo-userpic" src="../assets/pages/media/users/avatar8.jpg" width="27px" height="27px"> </a>
                                                                                    <div class="media-body todo-comment">
                                                                                        <button type="button" class="todo-comment-btn btn btn-circle btn-default btn-sm">&nbsp; Reply &nbsp;</button>
                                                                                        <p class="todo-comment-head">
                                                                                            <span class="todo-comment-username">Christina Aguilera</span> &nbsp;
                                                                                            <span class="todo-comment-date">17 Sep 2014 at 2:05pm</span>
                                                                                        </p>
                                                                                        <p class="todo-text-color"> Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis.
                                                                                            </p>
                                                                                        <!-- Nested media object -->
                                                                                        <div class="media">
                                                                                            <a class="pull-left" href="javascript:;">
                                                                                                <img class="todo-userpic" src="../assets/pages/media/users/avatar4.jpg" width="27px" height="27px"> </a>
                                                                                            <div class="media-body">
                                                                                                <p class="todo-comment-head">
                                                                                                    <span class="todo-comment-username">Carles Puyol</span> &nbsp;
                                                                                                    <span class="todo-comment-date">17 Sep 2014 at 4:30pm</span>
                                                                                                </p>
                                                                                                <p class="todo-text-color"> Thanks so much my dear! </p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="media">
                                                                                    <a class="pull-left" href="javascript:;">
                                                                                        <img class="todo-userpic" src="../assets/pages/media/users/avatar5.jpg" width="27px" height="27px"> </a>
                                                                                    <div class="media-body todo-comment">
                                                                                        <button type="button" class="todo-comment-btn btn btn-circle btn-default btn-sm">&nbsp; Reply &nbsp;</button>
                                                                                        <p class="todo-comment-head">
                                                                                            <span class="todo-comment-username">Andres Iniesta</span> &nbsp;
                                                                                            <span class="todo-comment-date">18 Sep 2014 at 9:22am</span>
                                                                                        </p>
                                                                                        <p class="todo-text-color"> Cras sit amet nibh libero, in gravida nulla. Scelerisque ante sollicitudin commodo Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum
                                                                                            in vulputate at, tempus viverra turpis.
                                                                                            <br> </p>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="media">
                                                                                    <a class="pull-left" href="javascript:;">
                                                                                        <img class="todo-userpic" src="../assets/pages/media/users/avatar6.jpg" width="27px" height="27px"> </a>
                                                                                    <div class="media-body todo-comment">
                                                                                        <button type="button" class="todo-comment-btn btn btn-circle btn-default btn-sm">&nbsp; Reply &nbsp;</button>
                                                                                        <p class="todo-comment-head">
                                                                                            <span class="todo-comment-username">Olivia Wilde</span> &nbsp;
                                                                                            <span class="todo-comment-date">18 Sep 2014 at 11:50am</span>
                                                                                        </p>
                                                                                        <p class="todo-text-color"> Cras sit amet nibh libero, in gravida nulla. Scelerisque ante sollicitudin commodo Nulla vel metus scelerisque ante sollicitudin commodo. Cras purus odio, vestibulum
                                                                                            in vulputate at, tempus viverra turpis.
                                                                                            <br> </p>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <!-- END TASK COMMENTS -->
                                                                    <!-- TASK COMMENT FORM -->
                                                                    <div class="form-group">
                                                                        <div class="col-md-12">
                                                                            <ul class="media-list">
                                                                                <li class="media">
                                                                                    <a class="pull-left" href="javascript:;">
                                                                                        <img class="todo-userpic" src="../assets/pages/media/users/avatar4.jpg" width="27px" height="27px"> </a>
                                                                                    <div class="media-body">
                                                                                        <textarea class="form-control todo-taskbody-taskdesc" rows="4" placeholder="Type comment..."></textarea>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                            <button type="button" class="pull-right btn btn-sm btn-circle green"> &nbsp; Submit &nbsp; </button>
                                                                        </div>
                                                                    </div>
                                                                    <!-- END TASK COMMENT FORM -->
                                                                </div>
                                                                <div class="tab-pane" id="tab_2">
                                                                    <ul class="todo-task-history">
                                                                        <li>
                                                                            <div class="todo-task-history-date"> 20 Jun, 2014 at 11:35am </div>
                                                                            <div class="todo-task-history-desc"> Task created </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="todo-task-history-date"> 21 Jun, 2014 at 10:35pm </div>
                                                                            <div class="todo-task-history-desc"> Task category status changed to "Top Priority" </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="todo-task-history-date"> 22 Jun, 2014 at 11:35am </div>
                                                                            <div class="todo-task-history-desc"> Task owner changed to "Nick Larson" </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="todo-task-history-date"> 30 Jun, 2014 at 8:09am </div>
                                                                            <div class="todo-task-history-desc"> Task completed by "Nick Larson" </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>');
	$CI->make->eDiv();
	// $CI->make->sBox('default',array('class'=>'box-solid'));
	// 	// $CI->make->sBoxBody(array('style'=>'background-color:#015D56;min-height:535px;padding:0 0 10px 0;'));
	// 	// 	$CI->make->sDiv(array('id'=>'endofday-content'));
	// 	// 	$CI->make->eDiv();
	// 	// $CI->make->eBoxBody();
	// $CI->make->eBox();
	return $CI->make->code();
}

?>