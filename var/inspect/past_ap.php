<?php

echo '
<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect" id="ap">';

$check = mysqli_num_rows(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past`"));

if($check > '1') {
	
	echo '
	<script type="text/javascript">
		google.charts.load("current", {"packages":["corechart"]});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
			["Date", "Artifact Power"],';
	
			for($i = '28'; $i >= '0'; $i--) {
		
				$min = time('now')-($i-1)*86400;
				$max = time('now')-$i*86400;
		
				$data = mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` >= '" .$max. "' AND `timestamp` < '" .$min. "' ORDER BY `id` ASC");
		
				while($indiv_day = mysqli_fetch_array($data)) {
					echo '["' .date('d M', $max). '", ' .$indiv_day['ap']. '],';
				}
			}
		
			echo '
			]);

        	var options = {
				width: 740,
				height: 250,
				title: "Artifact Power Gain",
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