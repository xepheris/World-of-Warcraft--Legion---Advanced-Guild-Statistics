<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;" class="inspect">
<span style="color: orange; font-size: 20px;">Mythic dungeons (last 4 weeks)</span>

<script type="text/javascript">
      google.charts.load("current", {"packages":["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Year", "Sales", "Expenses"],
          ["2004",  1000,      400],
          ["2005",  1170,      460],
          ["2006",  660,       1120],
          ["2007",  1030,      540]
        ]);

        var options = {
			width: 740,
			height: 250,
          title: "Company Performance",
          curveType: "function",
		  backgroundColor: "#84724E",
          legend: { position: "bottom" }
        };

        var chart = new google.visualization.LineChart(document.getElementById("mythics_chart"));

        chart.draw(data, options);
      }
</script>

<div id="mythics_chart" style="width: 740px; height: 250px; margin: 0 auto;"></div>


</div>';

?>