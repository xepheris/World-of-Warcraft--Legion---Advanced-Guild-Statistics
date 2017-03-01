<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">
<span style="color: orange; font-size: 20px;">Raidprogress & highest parse for difficulty</span>
<br />
<span style="color: black; font-size: 10px;">heroic & mythic - hover over numbers to see other difficulties<br />
Warcraftlogs API is still in development and may not necessarily be absolutely correct</span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$en_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_1`");
		
while($en_name = mysqli_fetch_array($en_names)) {
	echo '
	<th>' .$en_name['name']. '</th>';
}
		
echo '
</tr>
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
		$color_hc = 'black';
	}
	
	if($en_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($en_kills['heroic_log'] != '') {
		if($en_kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($en_kills['heroic_parse'] >= '75' && $en_kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($en_kills['heroic_parse'] < '75' && $en_kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($en_kills['heroic_parse'] < '50' && $en_kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($en_kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$en_kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$en_kills['heroic_parse']. '%)</a>';
	}
	
	if($en_kills['mythic_log'] != '') {
		
		if($en_kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($en_kills['mythic_parse'] >= '75' && $en_kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($en_kills['mythic_parse'] < '75' && $en_kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($en_kills['mythic_parse'] < '50' && $en_kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($en_kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$en_kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$en_kills['mythic_parse']. '%)</a>';
	}
	
	echo '<td>
		<span title="' .$en_kills['lfr']. ' LFR ' .$en_kills['normal']. ' N">
			<span style="color: ' .$color_hc. ';">' .$en_kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$en_kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($hc_log); unset($m_log);
}	
		
echo '
</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$tov_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_2`");
		
while($en_name = mysqli_fetch_array($tov_names)) {
	echo '
	<th>' .$en_name['name']. '</th>';
}
		
echo '
</tr>
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
		$color_hc = 'black';
	}
	
	if($tov_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($tov_kills['heroic_log'] != '') {
		if($tov_kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($tov_kills['heroic_parse'] >= '75' && $tov_kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($tov_kills['heroic_parse'] < '75' && $tov_kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($tov_kills['heroic_parse'] < '50' && $tov_kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($tov_kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$tov_kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$tov_kills['heroic_parse']. '%)</a>';
	}
	
	if($tov_kills['mythic_log'] != '') {
		
		if($tov_kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($tov_kills['mythic_parse'] >= '75' && $tov_kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($tov_kills['mythic_parse'] < '75' && $tov_kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($tov_kills['mythic_parse'] < '50' && $tov_kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($tov_kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$tov_kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$tov_kills['mythic_parse']. '%)</a>';
	}
	
	echo '	<td>
		<span title="' .$tov_kills['lfr']. ' LFR ' .$tov_kills['normal']. ' N">
			<span style="color: ' .$color_hc. ';">' .$tov_kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$tov_kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($hc_log); unset($m_log);
}	
		
echo '
</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$nh_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_3`");

while($nh_name = mysqli_fetch_array($nh_names)) {
	echo '
	<th>' .$nh_name['name']. '</th>';
}
	
echo '
</tr>
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
		$color_hc = 'black';
	}
	
	if($nh_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($nh_kills['heroic_log'] != '') {
		if($nh_kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($nh_kills['heroic_parse'] >= '75' && $nh_kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($nh_kills['heroic_parse'] < '75' && $nh_kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($nh_kills['heroic_parse'] < '50' && $nh_kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($nh_kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$nh_kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$nh_kills['heroic_parse']. '%)</a>';
	}
	
	if($nh_kills['mythic_log'] != '') {
		
		if($nh_kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($nh_kills['mythic_parse'] >= '75' && $nh_kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($nh_kills['mythic_parse'] < '75' && $nh_kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($nh_kills['mythic_parse'] < '50' && $nh_kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($nh_kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$nh_kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$nh_kills['mythic_parse']. '%)</a>';
	}
		
	echo '<td>
		<span title="' .$nh_kills['lfr']. ' LFR ' .$nh_kills['normal']. ' N">
			<span style="color: ' .$color_hc. ';">' .$nh_kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$nh_kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($hc_log); unset($m_log);
}	
		
echo '
</tr>
</tbody>
</table>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>';
		
$tos_names = mysqli_query($stream, "SELECT `name` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_4`");

while($tos_name = mysqli_fetch_array($tos_names)) {
	echo '
	<th>' .$tos_name['name']. '</th>';
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
		$color_hc = 'black';
	}
	
	if($tos_kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($tos_kills['heroic_log'] != '') {
		if($tos_kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($nh_kills['heroic_parse'] >= '75' && $tos_kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($tos_kills['heroic_parse'] < '75' && $tos_kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($tos_kills['heroic_parse'] < '50' && $tos_kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($tos_kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$tos_kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$tos_kills['heroic_parse']. '%)</a>';
	}
	
	if($tos_kills['mythic_log'] != '') {
		
		if($tos_kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($tos_kills['mythic_parse'] >= '75' && $tos_kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($tos_kills['mythic_parse'] < '75' && $tos_kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($tos_kills['mythic_parse'] < '50' && $tos_kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($tos_kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$tos_kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$tos_kills['mythic_parse']. '%)</a>';
	}
		
	echo '<td>
		<span title="' .$tos_kills['lfr']. ' LFR ' .$tos_kills['normal']. ' N">
			<span style="color: ' .$color_hc. ';">' .$tos_kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$tos_kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';	
	
	unset($hc_log); unset($m_log);
}	
		
echo '
</tr>
</tbody>
</table>
</div>';

?>