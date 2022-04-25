<script>
$(document).ready(function(){
	<?php if($use_js == 'dashBoardJs'): ?>
	startTime();
	load_trans_chart();
	set_last_gt();
	set_top_ten_menu();
	function set_last_gt(){
		$('#last-gt').html('<i class="fa fa-refresh fa-spin"></i>');
		$.post(baseUrl+'dashboard/get_last_gt',function(data){
			$('#last-gt').html(data);
		});
	}
	function set_top_ten_menu(){
		$('#top-menu-box').parent().parent().goBoxLoad();
		$.post(baseUrl+'dashboard/get_top_menus',function(data){
			$('#top-menu-box').parent().parent().goBoxLoad({load:false});
			$('#top-menu-box').html(data);
		});
	}
	function load_trans_chart(){
		// $('#bar-chart').goLoad();
		$('#bars-div').goLoad();
		$.post(baseUrl+'dashboard/summary_orders',function(data){
			// alert(data.orders);
			// var orders = new Array();
			var shift_sales = new Array();
			$.each(data.shift_sales,function(key,val){
				shift_sales.push(val);
			});
			// var bar = new Morris.Bar({
	  //           element: 'bar-chart',
	  //           resize: true,
	  //           data: orders,
	  //           barColors: ["#428BCA", "#00A65A","#F39C12", "#F56954"],
	  //           xkey: 'label',
	  //           ykeys: ['open','settled','cancel','void'],
	  //           labels: ['open','settled','cancel','void'],
	  //           hideHover: 'auto'
	  // 		});
			// $('#bar-chart').goLoad({load:false});
			//DONUT CHART
		    var donut = new Morris.Donut({
		        element: 'sales-chart',
		        resize: true,
		        data:shift_sales,
		        hideHover: 'auto'
		    });
		    console.log(shift_sales);
			$('#bars-div').html(data.code);
						// $(".knob").knob({
			//     draw: function() {

			//         // "tron" case
			//         if (this.$.data('skin') == 'tron') {

			//             var a = this.angle(this.cv)  // Angle
			//                     , sa = this.startAngle          // Previous start angle
			//                     , sat = this.startAngle         // Start angle
			//                     , ea                            // Previous end angle
			//                     , eat = sat + a                 // End angle
			//                     , r = true;

			//             this.g.lineWidth = this.lineWidth;

			//             this.o.cursor
			//                     && (sat = eat - 0.3)
			//                     && (eat = eat + 0.3);

			//             if (this.o.displayPrevious) {
			//                 ea = this.startAngle + this.angle(this.value);
			//                 this.o.cursor
			//                         && (sa = ea - 0.3)
			//                         && (ea = ea + 0.3);
			//                 this.g.beginPath();
			//                 this.g.strokeStyle = this.previousColor;
			//                 this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
			//                 this.g.stroke();
			//             }

			//             this.g.beginPath();
			//             this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
			//             this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
			//             this.g.stroke();

			//             this.g.lineWidth = 2;
			//             this.g.beginPath();
			//             this.g.strokeStyle = this.o.fgColor;
			//             this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
			//             this.g.stroke();

			//             return false;
			//         }
			//     }
			// });
			$('.easy-pie-chart .blue').easyPieChart({
                animate: 1000,
                size: 85,
                lineWidth: 3,
                barColor: App.getBrandColor('blue')
            });

            $('.easy-pie-chart .green').easyPieChart({
                animate: 1000,
                size: 85,
                lineWidth: 3,
                barColor: App.getBrandColor('green')
            });

            $('.easy-pie-chart .yellow').easyPieChart({
                animate: 1000,
                size: 85,
                lineWidth: 3,
                barColor: App.getBrandColor('yellow')
            });
	        $('.easy-pie-chart .red').easyPieChart({
	            animate: 1000,
	            size: 85,
	            lineWidth: 3,
	            barColor: App.getBrandColor('red')
	        });



            $('..knob-reload').click(function() {
                $('.knob .number').each(function() {
                    var newValue = Math.floor(100 * Math.random());
                    $(this).data('easyPieChart').update(newValue);
                    $('span', this).text(newValue);
                });
            });
		// });
		},'json');
	}

	function startTime(){
	    var today = new Date();
	    var h = today.getHours();
	    var m = today.getMinutes();
	    var s = today.getSeconds();
	    var weekday = new Array(7);
	        weekday[0]=  "Sunday";
	        weekday[1] = "Monday";
	        weekday[2] = "Tuesday";
	        weekday[3] = "Wednesday";
	        weekday[4] = "Thursday";
	        weekday[5] = "Friday";
	        weekday[6] = "Saturday";
	    var d = weekday[today.getDay()];

	    var today = moment();
	    var to = today.format('MMMM  D, YYYY');
	    // add a zero in front of numbers<10
	    m = checkTime(m);
	    s = checkTime(s);

	    //Check for PM and AM
	    var day_or_night = (h > 11) ? "PM" : "AM";

	    //Convert to 12 hours system
	    if (h > 12)
	        h -= 12;

	    //Add time to the headline and update every 500 milliseconds
	    $('#box-time').html(h + ":" + m + ":" + s + " " + day_or_night);
	    $('#box-day').html(d);
	    $('#box-date').html(to);
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

	<?php if(CONSOLIDATOR){ ?>
			setInterval(function(){ update_terminals(); }, 10000);                  

            //update terminals
            function update_terminals(){
                $.post('<?= base_url() ?>site/execute_migration',function(data){
                                             
                });
            }
	<?php } ?>

	<?php elseif($use_js == 'memberDashboardJs'): ?> 
	startTime();
	load_trans_chart();
	

	function load_trans_chart(){
		$.post(baseUrl+'member_dashboard/get_member_payment',function(data){          

		var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        new Morris.Line({
		  // ID of the element in which to draw the chart.
		  element: 'sales-chart',

		  data: data.datas,
		  
		  // The name of the data record attribute that contains x-values.
		  xkey: 'date',
		  // A list of names of data record attributes that contain y-values.
		  ykeys: ['value'],
		  // Labels for the ykeys -- will be displayed when you hover over the
		  // chart.
		  labels: ['Amount'],
		  xLabelFormat: function(x) { // <--- x.getMonth() returns valid index
		    var month = months[x.getMonth()];
		    return month;
		  },
		  dateFormat: function(x) {
		    var month = months[new Date(x).getMonth()];
		    return month;
		  },
		});

		// new Morris.Line({
		//   // ID of the element in which to draw the chart.
		//   element: 'sales-chart',
		//   // Chart data records -- each entry in this array corresponds to a point on
		//   // the chart.
		//   data: [
		//     { year: '2008', value: 20 },
		//     { year: '2009', value: 10 },
		//     { year: '2010', value: 5 },
		//     { year: '2011', value: 5 },
		//     { year: '2012', value: 20 }
		//   ],
		//   // The name of the data record attribute that contains x-values.
		//   xkey: 'year',
		//   // A list of names of data record attributes that contain y-values.
		//   ykeys: ['value'],
		//   // Labels for the ykeys -- will be displayed when you hover over the
		//   // chart.
		//   labels: ['Value']
		// });
		

        },'json');
	}

	function startTime(){
	    var today = new Date();
	    var h = today.getHours();
	    var m = today.getMinutes();
	    var s = today.getSeconds();
	    var weekday = new Array(7);
	        weekday[0]=  "Sunday";
	        weekday[1] = "Monday";
	        weekday[2] = "Tuesday";
	        weekday[3] = "Wednesday";
	        weekday[4] = "Thursday";
	        weekday[5] = "Friday";
	        weekday[6] = "Saturday";
	    var d = weekday[today.getDay()];

	    var today = moment();
	    var to = today.format('MMMM  D, YYYY');
	    // add a zero in front of numbers<10
	    m = checkTime(m);
	    s = checkTime(s);

	    //Check for PM and AM
	    var day_or_night = (h > 11) ? "PM" : "AM";

	    //Convert to 12 hours system
	    if (h > 12)
	        h -= 12;

	    //Add time to the headline and update every 500 milliseconds
	    $('#box-time').html(h + ":" + m + ":" + s + " " + day_or_night);
	    $('#box-day').html(d);
	    $('#box-date').html(to);
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

	<?php if(CONSOLIDATOR){ ?>
			setInterval(function(){ update_terminals(); }, 10000);                  

            //update terminals
            function update_terminals(){
                $.post('<?= base_url() ?>site/execute_migration',function(data){
                                             
                });
            }
	<?php } ?>
	<?php endif; ?>
});
</script>