<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;" class="inspect" id="dungeons">
<span style="color: orange; font-size: 20px;">Dungeons</span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>
	<th><span title="Black Rook Hold">BRH</span></th>
	<th><span title="Cathedral of Eternal Night">CEN</span></th>
	<th><span title="Court of Stars">CoS</span></th>
	<th><span title="Darkheart Thicket">DHT</span></th>
	<th><span title="Eye of Azshara">EoA</span></th>
	<th><span title="Halls of Valor">HoV</span></th>
	<th><span title="Lower Karazhan">LKZ</span></th>
	<th><span title="Maw of Souls">MoS</span></th>
	<th><span title="Neltharions Lair">NEL</span></th>
	<th><span title="The Arcway">ARC</span></th>
	<th><span title="The Violet Hold">VH</span></th>
	<th><span title="Upper Karazhan">UKZ</span></th>	
	<th><span title="Vault of the Wardens">VotW</span></th>	
	<th>Total</th>
</tr>
</thead>
<tbody>
<tr>';
		
$sum = array();
for($i = '1'; $i <= '13'; $i++) {
	$dungeon_stats = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_dungeons` WHERE `id` = '" .$i. "'"));
	echo '<td><span title="' .$dungeon_stats['normal']. ' N ' .$dungeon_stats['heroic']. ' HC">' .$dungeon_stats['mythic']. '</span></td>';
	array_push($sum, $dungeon_stats['mythic']);
}

echo '<td>' .array_sum($sum). '</td>';
		
echo '</tr>
</tbody>
</table>';

$m_plus_overview = mysqli_fetch_array(mysqli_query($stream, "SELECT `m2`, `m5`, `m10`, `m15` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_general`"));

$m0 = array_sum($sum)-$m_plus_overview['m2'];
$m2_to_m5 = $m_plus_overview['m2']-$m_plus_overview['m5'];
$m5_to_m10 = $m_plus_overview['m5']-$m_plus_overview['m10'];
$m10_to_15 = $m_plus_overview['m10']-$m_plus_overview['m15'];

if($m2_to_m5 < '0') {
	$m2_to_m5 = '0';
	$m5_to_m10 = '0';
	$m10_to_15 = '0';
	$m_plus_overview['m15'] = '0';
}
if($m5_to_m10 < '0') {
	$m5_to_m10 = '0';
	$m10_to_15 = '0';
	$m_plus_overview['m15'] = '0';
}
if($m10_to_15 < '0') {
	$m10_to_15 = '0';
	$m_plus_overview['m15'] = '0';
}

 echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
	google.charts.load("current", {packages: ["corechart", "bar"]});
	google.charts.setOnLoadCallback(drawStacked);
	 
	function drawStacked() {
		var data = google.visualization.arrayToDataTable([
		["Genre", "M0", "M2-M5", "M5-M10", "M10-M15", "M15+", { role: "annotation" } ],
		["Mythics", ' .$m0. ',' .$m2_to_m5. ', ' .$m5_to_m10. ', ' .$m10_to_15. ', ' .$m_plus_overview['m15']. ', ""]
		]);
		
		var options = {
			width: 740,
			height: 137,
			legend: "none",
			bar: { groupWidth: "30%" },
			backgroundColor: "#84724E",
			isStacked: "percent",
		};
		
		var chart = new google.visualization.BarChart(document.getElementById("mythic_chart"));
		chart.draw(data, options);
	}
	</script>

	<div id="mythic_chart" style="width: 740px; height: 137px; margin: 0 auto;"></div>
	</div>';
	
?>