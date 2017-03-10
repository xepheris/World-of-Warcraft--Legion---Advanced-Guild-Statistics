<?php

echo '
<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect">';

$check = mysqli_num_rows(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past`"));

if($check > '1') {
	
	echo '
	<script type="text/javascript">
		google.charts.load("current", {"packages":["corechart"]});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
			["Date", "Artifact Power"],';
	
			// FOREACH OF THE LAST 28 DAYS
			for($i = '28'; $i >= '0'; $i--) {
				
				$k = $i+1;
				
				// START TIME
				$past = time('now')-($i*86400);
				// END TIME
				$future = time('now')-($k*86400);
				
				// CHECK HOW OFTEN THE CHARACTER WAS UPDATED A DAY
				$update_amount = mysqli_num_rows(mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` <= '" .$past. "' AND `timestamp` > '" .$future. "'"));
				if($update_amount > '1') {
					$update_value = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`ap`) AS `day_sum` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` <= '" .$past. "' AND `timestamp` > '" .$future. "'"));
					$result = $update_value['day_sum']/$update_amount;
				}
				// IF DAY UPDATED ONLY ONCE
				elseif($update_amount <= '1') {
					$update_amount = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` <= '" .$past. "' AND `timestamp` > '" .$future. "'"));
					$result = $update_amount['ap'];
					// IF DAY WAS NOT UPDATED AT ALL
					if($result == '') {
						$past1 = time('now')-(($i-1)*86400);
						$future1 = time('now')-(($k-1)*86400);
						$update_amount1 = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` <= '" .$past1. "' AND `timestamp` > '" .$future1. "'"));
						$result1 = $update_amount1['ap'];
						
						$past2 = time('now')-(($i+1)*86400);
						$future2 = time('now')-(($k+1)*86400);
						$update_amount2 = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` <= '" .$past2. "' AND `timestamp` > '" .$future2. "'"));
						$result2 = $update_amount2['ap'];
						
						$result = round(($result2+$result1)/2, 0);
					}
				}
				echo '["' .date('d M', $past). '", ' .$result . '],';
			}

			echo '
			]);

        	var options = {
				width: 740,
				height: 250,
				title: "Artifact Power Gain (last 4 weeks)",
				titleTextStyle: {
					color: "orange",
					fontSize: "20",
					bold: "0"
				},
				curveType: "function",
				backgroundColor: "#84724E",
				legend: { position: "none" }
	        };

    	    var chart = new google.visualization.LineChart(document.getElementById("ap_chart"));

    	    chart.draw(data, options);
		}
	</script>';
}
elseif($check <= '1') {
	$error = '<span style="color: coral; text-align: center;">Data not available yet - come back tomorrow and update this profile to see progress!</span>';
}

echo '
<div id="ap_chart" style="width: 740px; height: 250px; margin: 0 auto;">' .$error. '</div>



</div>';

?>