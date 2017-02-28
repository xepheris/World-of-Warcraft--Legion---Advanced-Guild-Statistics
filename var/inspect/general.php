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

// ALVL THRESHOLDS
if($general_table['alvl'] == '54') {
	$alvl = '<span style="color: yellowgreen;">54</span>';
}
elseif($general_table['alvl'] < '54' && $general_table['alvl'] >= '35') {
	$alvl = '<span style="color: orange;">' .$general_table['alvl']. '</span>';
}
elseif($general_table['alvl'] < '35') {
	$alvl = '<span style="color: red;">' .$general_table['alvl']. '</span>';
}

// AK THRESHOLDS
if($general_table['ak'] == '25') {
	$ak = '<span style="color: yellowgreen;">25</span>';
}
elseif($general_table['ak'] < '25' && $general_table['ak'] >= '13') {
	$ak = '<span style="color: orange;">' .$general_table['ak']. '</span>';
}
elseif($general_table['ak'] < '12') {
	$ak = '<span style="color: red;">' .$general_table['ak']. '</span>';
}


		
echo '<tr><td>' .number_format($general_table['ap']). '</td><td>' .$alvl. '</td><td>' .$ak. '</td>
</tbody>
</table>
</div>';

?>