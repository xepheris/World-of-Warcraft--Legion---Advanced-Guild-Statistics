<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">
<span style="color: orange; font-size: 20px;">Raidprogress</span>
<br />
<span style="color: black; font-size: 10px;">heroic & mythic - hover over numbers to see other difficulties</span>
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

// EMERALD NIGHTMARE PROGRESS
		
for($i = '1'; $i <= '7'; $i++) {
	$en_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_1` WHERE `id` = '" .$i. "'"));
	
	if($en_kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'coral';
	}
	
	if($en_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'coral';
	}
	
	echo '<td><span title="' .$en_kills['lfr']. ' LFR ' .$en_kills['normal']. ' N"><span style="color: ' .$color_hc. ';">' .$en_kills['heroic']. ' HC</span> <span style="color: ' .$color_m. ';">' .$en_kills['mythic']. ' M</span></span></td>';
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

// TRIAL OF VALOR PROGRESS
		
for($i = '1'; $i <= '3'; $i++) {
	$tov_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_2` WHERE `id` = '" .$i. "'"));	
	
	if($tov_kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'coral';
	}
	
	if($tov_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'coral';
	}
		
	echo '<td><span title="' .$tov_kills['lfr']. ' LFR ' .$tov_kills['normal']. ' N"><span style="color: ' .$color_hc. ';">' .$tov_kills['heroic']. ' HC</span> <span style="color: ' .$color_m. ';">' .$tov_kills['mythic']. ' M</span></span></td>';		
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

// NIGHTHOLD PROGRESS
		
for($i = '1'; $i <= '10'; $i++) {
	$nh_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_3` WHERE `id` = '" .$i. "'"));
			
	if($nh_kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'coral';
	}
	
	if($nh_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'coral';
	}
		
	echo '<td><span title="' .$nh_kills['lfr']. ' LFR ' .$nh_kills['normal']. ' N"><span style="color: ' .$color_hc. ';">' .$nh_kills['heroic']. ' HC</span> <span style="color: ' .$color_m. ';">' .$nh_kills['mythic']. ' M</span></span></td>';	
}	
		
echo '</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$tos_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_4`");

while($tos_name = mysqli_fetch_array($tos_names)) {
	echo '<th>' .$tos_name['name']. '</th>';
}
	
echo '</tr>
</thead>
<tbody>
<tr>';

// TOMB OF SARGERAS PROGRESS
		
for($i = '1'; $i <= '9'; $i++) {
	$tos_kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_4` WHERE `id` = '" .$i. "'"));
			
	if($tos_kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'coral';
	}
	
	if($tos_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'coral';
	}
		
	echo '<td><span title="' .$tos_kills['lfr']. ' LFR ' .$tos_kills['normal']. ' N"><span style="color: ' .$color_hc. ';">' .$tos_kills['heroic']. ' HC</span> <span style="color: ' .$color_m. ';">' .$tos_kills['mythic']. ' M</span></span></td>';	
}	
		
echo '</tr>
</tbody>
</table>
</div>';

?>