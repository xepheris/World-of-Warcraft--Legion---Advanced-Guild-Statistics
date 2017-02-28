<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">
<span style="color: orange; font-size: 20px;">Raidprogress</span>		
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$en_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_1`");
		
while($en_name = mysqli_fetch_array($en_names)) {
	echo '<th>' .$en_name['name']. '</th>';
}
		
echo '</tr>
</thead>
<tbody>
<tr>';
		
for($i = '1'; $i <= '7'; $i++) {
	$en_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_1` WHERE `id` = '" .$i. "'"));
	
	if($en_kills['mythic'] > '0') {
		$color = 'yellowgreen';
	}
	else {
		$color = 'red';
	}
	
	echo '<td><span title="' .$en_kills['lfr']. ' LFR ' .$en_kills['normal']. ' N ' .$en_kills['heroic']. ' HC" style="color: ' .$color. ';">' .$en_kills['mythic']. '</span></td>';			
}	
		
echo '</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$tov_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_2`");
		
while($en_name = mysqli_fetch_array($tov_names)) {
	echo '<th>' .$en_name['name']. '</th>';
}
		
echo '</tr>
</thead>
<tbody>
<tr>';
		
for($i = '1'; $i <= '3'; $i++) {
	$tov_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_2` WHERE `id` = '" .$i. "'"));	
	
	if($tov_kills['mythic'] > '0') {
		$color = 'yellowgreen';
	}
	else {
		$color = 'red';
	}
		
	echo '<td><span title="' .$tov_kills['lfr']. ' LFR ' .$tov_kills['normal']. ' N ' .$tov_kills['heroic']. ' HC" style="color: ' .$color. ';">' .$tov_kills['mythic']. '</span></td>';			
}	
		
echo '</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$nh_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_3`");

while($nh_name = mysqli_fetch_array($nh_names)) {
	echo '<th>' .$nh_name['name']. '</th>';
}
	
echo '</tr>
</thead>
<tbody>
<tr>';
		
for($i = '1'; $i <= '10'; $i++) {
	$nh_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_3` WHERE `id` = '" .$i. "'"));
			
	if($nh_kills['mythic'] > '0') {
		$color = 'yellowgreen';
	}
	else {
		$color = 'red';
	}
	
	echo '<td><span title="' .$nh_kills['lfr']. ' LFR ' .$nh_kills['normal']. ' N ' .$nh_kills['heroic']. ' HC" style="color: ' .$color. ';">' .$nh_kills['mythic']. '</span></td>';			
}	
		
echo '</tr>
</tbody>
</table>
</div>';

?>