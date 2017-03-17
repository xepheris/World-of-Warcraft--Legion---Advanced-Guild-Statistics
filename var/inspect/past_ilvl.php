<?php

echo '
<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;" class="inspect">';

if($check > '1') {
	
	echo '
	<script type="text/javascript">
		google.charts.load("current", {"packages":["corechart"]});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
			["Date", "Itemlevel equipped", "Itemlevel in bags"],';

			for($i = '28'; $i >= '0'; $i--) {
		
				$min = time('now')-($i-1)*86400;
				$max = time('now')-$i*86400;
		
				$data = mysqli_query($stream, "SELECT `ilvl_on`, `ilvl_bags` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` WHERE `timestamp` >= '" .$max. "' AND `timestamp` < '" .$min. "' ORDER BY `id` ASC");
		
				while($indiv_day = mysqli_fetch_array($data)) {
					echo '["' .date('d M', $max). '", ' .$indiv_day['ilvl_on']. ', ' .$indiv_day['ilvl_bags']. '],';
				}
			}

			echo '
			]);

			var options = {
				width: 740,
				height: 250,
				title: "Itemlevel (last 30 updates)",
				titleTextStyle: {
					color: "orange",
					fontSize: "20",
					bold: "0"
				},
				curveType: "function",
				backgroundColor: "#84724E",
				legend: "none"
			};

			var chart = new google.visualization.LineChart(document.getElementById("ilvl_chart"));

			chart.draw(data, options);
		}
	</script>';
}
elseif($check <= '1') {
	$error = '<span style="color: coral; text-align: center;">Data not available yet - come back tomorrow and update this profile to see progress!</span>';
}

echo '

<div id="ilvl_chart" style="width: 740px; height: 250px; margin: 0 auto;">' .$error. '</div>


</div>';

?>