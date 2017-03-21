<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect">
<span style="color: orange; font-size: 20px;">Raidprogress</span>
<br />
<span style="color: black; font-size: 10px;">heroic & mythic - hover over numbers to see other difficulties<br />
parse is directly from within the log, not the profile & rounded to 2 digits<br />
the Warcraftlogs API is subject of change - bugs are possible</span>
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
	$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_1` WHERE `id` = '" .$i. "'"));
	
	if($kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'black';
	}
	
	if($kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($kills['lfr_log'] != '') {			
		$lfr_log = '(' .$kills['lfr_parse']. '%)';
	}
	
	if($kills['normal_log'] != '') {			
		$n_log = '(' .$kills['normal_parse']. '%)';
	}
	
	if($kills['heroic_log'] != '') {
		if($kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['heroic_parse'] >= '75' && $kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['heroic_parse'] < '75' && $kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['heroic_parse'] < '50' && $kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['heroic_parse']. '%)</a>';
	}
	
	if($kills['mythic_log'] != '') {
		
		if($kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['mythic_parse'] >= '75' && $kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['mythic_parse'] < '75' && $kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['mythic_parse'] < '50' && $kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['mythic_parse']. '%)</a>';
	}
	
	echo '
	<td>
		<span title="' .$kills['lfr']. ' LFR ' .$lfr_log. ' ' .$kills['normal']. ' N ' .$n_log. '">
			<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($lfr_log); unset($n_log); unset($hc_log); unset($m_log);
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
	$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_2` WHERE `id` = '" .$i. "'"));	
	
	if($kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'black';
	}
	
	if($kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($kills['lfr_log'] != '') {
		$lfr_log = '(' .$kills['lfr_parse']. '%)';
	}
	
	if($kills['normal_log'] != '') {
		$n_log = '(' .$kills['normal_parse']. '%)';
	}
	
	if($kills['heroic_log'] != '') {
		if($kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['heroic_parse'] >= '75' && $kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['heroic_parse'] < '75' && $kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['heroic_parse'] < '50' && $kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['heroic_parse']. '%)</a>';
	}
	
	if($kills['mythic_log'] != '') {
		
		if($kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['mythic_parse'] >= '75' && $kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['mythic_parse'] < '75' && $kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['mythic_parse'] < '50' && $kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['mythic_parse']. '%)</a>';
	}
	
	echo '
	<td>
		<span title="' .$kills['lfr']. ' LFR ' .$lfr_log. ' ' .$kills['normal']. ' N ' .$n_log. '">
			<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($lfr_log); unset($n_log); unset($hc_log); unset($m_log);
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
	$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_3` WHERE `id` = '" .$i. "'"));
			
	if($kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'black';
	}
	
	if($kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($kills['lfr_log'] != '') {
		$lfr_log = '(' .$kills['lfr_parse']. '%)';
	}
	
	if($kills['normal_log'] != '') {
		$n_log = '(' .$kills['normal_parse']. '%)';
	}
	
	if($kills['heroic_log'] != '') {
		if($kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['heroic_parse'] >= '75' && $kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['heroic_parse'] < '75' && $kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['heroic_parse'] < '50' && $kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['heroic_parse']. '%)</a>';
	}
	
	if($kills['mythic_log'] != '') {
		
		if($kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['mythic_parse'] >= '75' && $kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['mythic_parse'] < '75' && $kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['mythic_parse'] < '50' && $kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['mythic_parse']. '%)</a>';
	}
		
	echo '
	<td>
		<span title="' .$kills['lfr']. ' LFR ' .$lfr_log. ' ' .$kills['normal']. ' N ' .$n_log. '">
			<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($lfr_log); unset($n_log); unset($hc_log); unset($m_log);
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
	$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_raid_4` WHERE `id` = '" .$i. "'"));
			
	if($kills['heroic'] > '0') {
		$color_hc = 'yellowgreen';
	}
	else {
		$color_hc = 'black';
	}
	
	if($kills['mythic'] > '0') {
		$color_m = 'yellowgreen';
	}
	else {
		$color_m = 'black';
	}
	
	if($kills['lfr_log'] != '') {
		$lfr_log = '(' .$kills['lfr_parse']. '%)';
	}
	
	if($kills['normal_log'] != '') {
		$n_log = '(' .$kills['normal_parse']. '%)';
	}
	
	if($kills['heroic_log'] != '') {
		if($kills['heroic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['heroic_parse'] >= '75' && $kills['heroic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['heroic_parse'] < '75' && $kills['heroic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['heroic_parse'] < '50' && $kills['heroic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['heroic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$hc_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['heroic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['heroic_parse']. '%)</a>';
	}
	
	if($kills['mythic_log'] != '') {
		
		if($kills['mythic_parse'] >= '95') {
			// 90+ parse color
			$color = 'rgb(255, 128, 0)';
		}
		elseif($kills['mythic_parse'] >= '75' && $kills['mythic_parse'] < '95') {
			// 75 to 90 parse color
			$color = 'rgb(204, 143, 246)';
		}
		elseif($kills['mythic_parse'] < '75' && $kills['mythic_parse'] >= '50') {
			// 50 to 75 parse color
			$color = 'rgb(118, 178, 255)';
		}
		elseif($kills['mythic_parse'] < '50' && $kills['mythic_parse'] >= '25') {
			// 25 to 50 parse color
			$color = 'rgb(30, 255, 0)';
		}
		elseif($kills['mythic_parse'] < '25') {
			// lower than 30 parses
			$color = '#ababab';
		}
		
		$m_log = '<a href="https://www.warcraftlogs.com/reports/' .$kills['mythic_log']. '" style="color: ' .$color. ';" title="log link">(' .$kills['mythic_parse']. '%)</a>';
	}
		
	echo '
	<td>
		<span title="' .$kills['lfr']. ' LFR ' .$lfr_log. ' ' .$kills['normal']. ' N ' .$n_log. '">
			<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '
			<span style="color: ' .$color_m. ';">' .$kills['mythic']. ' M</span> ' .$m_log. '
		</span>
	</td>';
	
	unset($lfr_log); unset($n_log); unset($hc_log); unset($m_log);
}	
		
echo '
</tr>
</tbody>
</table>
</div>';

?>