<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect">
<span style="color: orange; font-size: 20px;">Artifact Power Gain (last 4 weeks)</span>

<script type="text/javascript">
      google.charts.load("current", {"packages":["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Timespan", "Sales"],
          ["4 weeks ago",  1000],
          ["3 weeks ago",  1170],
          ["2 weeks ago",  660],
          ["last week",  1030]
        ]);

        var options = {
			width: 740,
			height: 250,
			title: "Company Performance",
			curveType: "function",
			backgroundColor: "#84724E",
			legend: { position: "none" }
        };

        var chart = new google.visualization.LineChart(document.getElementById("ap_chart"));

        chart.draw(data, options);
      }
</script>

<div id="ap_chart" style="width: 740px; height: 250px; margin: 0 auto;"></div>



</div>';

?>