<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">
<span style="color: orange; font-size: 20px;">General Stats</span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>
	<th>Total AP collected</th>
	<th>Artifact Level</th>
	<th>Artifact Knowledge</th>
</tr>
</thead>
<tbody>';
	
$general_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_general`"));
		
echo '<tr><td>' .number_format($general_table['ap']). '</td><td>' .$general_table['alvl']. '</td><td>' .$general_table['ak']. '</td>
</tbody>
</table>
</div>';

?>