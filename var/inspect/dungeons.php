<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;" class="inspect">
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

 echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load("current", {"packages":["corechart"]});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
         ["Month", "Bolivia", "Ecuador", "Madagascar", "Papua New Guinea", "Rwanda", "Average"],
         ["2004/05",  165,      938,         522,             998,           450,      614.6]
      ]);

    var options = {
      title : "Monthly Coffee Production by Country",
      vAxis: {title: "Cups"},
      hAxis: {title: "Month"},
      seriesType: "bars",
      series: {5: {type: "line"}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById("chart_div"));
    chart.draw(data, options);
  }
    </script>


	<div id="chart_div" style="width: 900px; height: 500px;"></div>
	</div>';
	
?>