<?php

echo '<script defer src="http://wow.zamimg.com/widgets/power.js"></script>
<script>
		var wowhead_tooltips = {
		iconizelinks: true,
		renamelinks: true,
		droppedby: true,
			"hide": {
			"dropchance": true,
			"sellprice": true,
			"maxstack": true,
		}
	}
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';

foreach($_GET as $user_id) {
	
	if($user_id != $_GET['source']) {
		$margin_left = 'margin-left: 5%';
	}

	echo '<div id="' .$user_id. '" style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; ' .$margin_left. '">';

	$general_char_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$user_id. "'"));

	if($general_char_data['wlogs_id'] != '0') {
		$warcraftlogs_link = '<a href="https://www.warcraftlogs.com/rankings/character/' .$general_char_data['wlogs_id']. '/latest" title="WarcraftLogs profile link">
			<span style="font-family: Avenir, Arial, sans-serif; color: rgb(30,180,135); text-shadow: 2px 2px 10px black;">WARCRAFT</span>
			<span style="font-family: Avenir, Arial, sans-serif; color: rgb(230,230,230); text-shadow: 2px 2px 10px black;">LOGS</span>
			</a>';
	}
	elseif($general_char_data['wlogs_id'] == '0') {
		$warcraftlogs_link = '<a href="https://www.warcraftlogs.com/rankings/character/' .$general_char_data['wlogs_id']. '/latest" title="WarcraftLogs profile could not be found" style="pointer-events: none;">
			<span style="font-family: Avenir, Arial, sans-serif; color: grey; text-shadow: 2px 2px 10px black;">WARCRAFT</span>
			<span style="font-family: Avenir, Arial, sans-serif; color: grey; text-shadow: 2px 2px 10px black;">LOGS</span>
			</a>';
	}

	if(time('now')-$general_char_data['updated'] <= '86400') {
		$last_update = '<span style="color: yellowgreen;">' .date('d.m.y - H:i:s', $general_char_data['updated']). '</span>';
	}
	else {
		$last_update = '<span style="color: coral;">' .date('d.m.y - H:i:s', $general_char_data['updated']). '</span>';
	}
		
	if($general_char_data['name'] == '') {
		$error = '<span style="color: coral;">A character with this ID could not be found.<span>';
	}
		
	$class_color = mysqli_fetch_array(mysqli_query($stream, "SELECT `class`, `colorhex` FROM `ovw_classes` WHERE `id` = '" .$general_char_data['class']. "'"));	
	$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `ovw_weapons` WHERE `id` = '" .$general_char_data['spec']. "'"));

	echo '<span style="font-size: 20px;"><a href="http://' .$_SESSION['region']. '.battle.net/wow/en/character/' .$_SESSION['realm']. '/' .$general_char_data['name']. '/simple" title="WoW Armory link">' .$general_char_data['name']. '</a> - 
		<a href="http://eu.battle.net/wow/de/tool/talent-calculator#' .$general_char_data['talents']. '" title="WoW Talent Calculator link">' .$spec['spec']. '</a>
		<span style="color: ' .$class_color['colorhex']. ';">' .$class_color['class']. '</span>
		<br />
		<a href="" style="text-transform: uppercase; color: orange;" title="AGS External View">AGS External View</a> | 
		<a href="http://www.wowprogress.com/character/' .$_SESSION['region']. '/' .$_SESSION['realm']. '/' .$general_char_data['name']. '" style="font-family:verdana,arial,sans-serif;" title="WoWProgress profile link">WoWProgress</a> | 
		' .$warcraftlogs_link. '
		<br />
		Last update: ' .$last_update. ' <img src="img/update.png" alt="404" title="Update ' .$general_char_data['name']. '" style="width: 16px;" id="' .$user_id. '" onclick="update(this.id);" /><br />
		Last known logout: ' .date('d.m.y - H:i:s', $general_char_data['logout']). '</span><br />';

	$general_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_general`"));
	$fetch_eq = mysqli_fetch_array(mysqli_query($stream, "SELECT `eq` FROM `" .$table_name. "` WHERE `id` = '" .$user_id. "'"));

	// ALVL THRESHOLDS
	if($general_table['alvl'] == '54') {
		$alvl = '<span style="color: yellowgreen;">54</span>';
	}
	elseif($general_table['alvl'] < '54' && $general_table['alvl'] >= '35') {
		$alvl = '<span style="color: orange;">' .$general_table['alvl']. '</span>';
	}
	elseif($general_table['alvl'] < '35') {
		$alvl = '<span style="color: coral;">' .$general_table['alvl']. '</span>';
	}

	// AK THRESHOLDS
	if($general_table['ak'] == '25') {
		$ak = '<span style="color: yellowgreen;">25</span>';
	}
	elseif($general_table['ak'] < '25' && $general_table['ak'] >= '13') {
		$ak = '<span style="color: orange;">' .$general_table['ak']. '</span>';
	}
	elseif($general_table['ak'] < '12') {
		$ak = '<span style="color: coral;">' .$general_table['ak']. '</span>';
	}

	echo '<br /><span style="font-size: 20px;">' .$fetch_eq['eq']. ' <a href="?eq" title="What is EQ?">Effort Quota</a></span><br /><br />
		' .number_format($general_table['ap']). ' AP collected<br />
		Artifact Level ' .$alvl. '<br />
		Artifact Knowledge ' .$ak. '<br />
		' .$general_table['wq']. ' World Quests completed';

	$itemlevels = mysqli_fetch_array(mysqli_query($stream, "SELECT `ilvl_on`, `ilvl_off` FROM `" .$_SESSION['table']. "_" .$user_id. "_general`"));

	echo '<br />
	<br />
	<span style="color: orange; font-size: 20px;">Current Equipment (' .$itemlevels['ilvl_on']. '/' .$itemlevels['ilvl_off']. ')</span>
	<br />
	<table style="margin: 0 auto; text-align: left; margin-top: 15px; border-bottom: 1px solid white; width: 100%;">
	<tbody>';
		
	$slots = array('1' => 'Head', '2' => 'Neck', '3' => 'Shoulders', '4' => 'Back', '5' => 'Chest', '6' => 'Wrists', '7' => 'Hands', '8' => 'Waist', '9' => 'Legs', '10' => 'Feet', '11' => 'Finger1', '12' => 'Finger2', '13' => 'Trinket1', '14' => 'Trinket2');
	$weapon = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_weapons`"));
	
	foreach($slots as $id => $slot) {
		$item_info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_equip` WHERE `id` = '" .$id. "'"));
					
		// GEM CHECK
		if((strpos($item_info['bonus'], '1808') !== FALSE) && ($item_info['gem'] == '0')) {
			$gem = '<img src="img/mg.png" title="missing gem" alt="missing gem" />';
		}
		elseif($item_info['gem'] != '0') {
			$gem = '<a href="http://wowhead.com/?item=' .$item_info['gem']. '">' .$item_info['gem']. '</a>';
		}
			
		$enchantable = array('2', '3', '4', '11', '12');
			
		if(in_array($id, $enchantable) && $item_info['enchant'] == '0') {
			$enchant = '<img src="img/me.png" title="missing enchant" alt="missing gem" />';
		}
		elseif($item_info['enchant'] != '0') {
			$conversion = mysqli_fetch_array(mysqli_query($stream, "SELECT `wowhead_id` FROM `ovw_enchants` WHERE `enchant_id` = '" .$item_info['enchant']. "'"));
			$gem = '<a href="http://wowhead.com/?item=' .$conversion['wowhead_id']. '">' .$conversion['wowhead_id']. '</a>';
		}
			
		if($item_info['itemlevel'] == '940') {
			$rarity = '#ff8000';
		}
			
		// ENCHANT CHECK
	
		echo '<tr>
			<td>' .$slot. '</td>
			<td><a href="http://wowhead.com/?item=' .$item_info['itemid']. '&bonus=' .$item_info['bonus']. '" rel="ench=' .$item_info['enchant']. '&gems=' .$item_info['gem']. '">' .$item_info['itemid']. '</a></td>
			<td><span style="color: ' .$rarity. ';">' .$item_info['itemlevel']. '</span></td>
			<td>' .$enchant. ' ' .$gem. ' </td>
		</tr>';
			
		unset($rarity); unset($enchant); unset($gem);
	}		
				
	echo '</tbody>
	</table>
	<table style="margin: 0 auto; text-align: center; width: 100%;">
	<tbody>
	<tr>
		<td colspan="3"><a href="http://wowhead.com/?item=' .$weapon['item_id']. '&bonus=' .$weapon['bonus']. '" rel="gems=' .$weapon['r1']. ':' .$weapon['r2']. ':' .$weapon['r3']. '">' .$weapon['item_id']. '</a> (' .$weapon['itemlevel']. ')</td>
	</tr>
	<tr>';

	for($i = '1'; $i <= '3'; $i++) {
		if($weapon['r' .$i. ''] != '0') {
			echo '<td><a href="http://wowhead.com/?item=' .$weapon['r' .$i. '']. '" rel="bonus=' .$weapon['bonus_r' .$i. '']. '">' .$weapon['r' .$i. '']. '</a></td>';
		}
		elseif($weapon['r' .$i. ''] == '0') {
			echo '<td>no relic in slot ' .$i. '</td>';
		}
	}

	echo '</tr>		
	</tbody>
	</table>
	<br />
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
		$dungeon_stats = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_dungeons` WHERE `id` = '" .$i. "'"));
		echo '<td><span title="' .$dungeon_stats['normal']. ' N ' .$dungeon_stats['heroic']. ' HC">' .$dungeon_stats['mythic']. '</span></td>';
		array_push($sum, $dungeon_stats['mythic']);
	}

	echo '<td>' .array_sum($sum). '</td>
	</tr>
	</tbody>
	</table>';

	$m_plus_overview = mysqli_fetch_array(mysqli_query($stream, "SELECT `m2`, `m5`, `m10`, `m15` FROM `" .$_SESSION['table']. "_" .$user_id. "_general`"));

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

	echo '<script type="text/javascript">
	google.charts.load("current", {packages: ["corechart", "bar"]});
	google.charts.setOnLoadCallback(drawStacked);
	 
	function drawStacked() {
		var data = google.visualization.arrayToDataTable([
		["Genre", "M0", "M2-M5", "M5-M10", "M10-M15", "M15+", { role: "annotation" } ],
		["Mythics", ' .$m0. ',' .$m2_to_m5. ', ' .$m5_to_m10. ', ' .$m10_to_15. ', ' .$m_plus_overview['m15']. ', ""]
		]);
	
		var options = {
			width: 640,
			height: 137,
			legend: { position: "top", maxLines: 3 },
			bar: { groupWidth: "30%" },
			backgroundColor: "#84724E",
			isStacked: "percent",
		};
		
		var chart = new google.visualization.BarChart(document.getElementById("g' .$user_id. '"));
		chart.draw(data, options);
	}
	</script>
	
	<div id="g' .$user_id. '" style="width: 740px; height: 137px; margin: 0 auto;"></div>
	<br />
	<span style="color: orange; font-size: 20px;">Reputation</span>
	<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
	<thead>
	<tr>
		<th><span title="Court of Farondis">Azsuna</span></th>
		<th><span title="Legionfall">Broken Shore</span></th>	
		<th><span title="Highmountain Tribe">Highmountain</span></th>
		<th><span title="The Valarjar">Stormheim</span></th>	
		<th><span title="The Nightfallen">Suramar</span></th>
		<th><span title="Dreamweavers">Valsharah</span></th>
		<th><span title="The Wardens">Wardens</span></th>
	</tr>
	</thead>
	<tbody>
	<tr>';
		
	$reputation_array = array($general_table['rep_farondis'], $general_table['rep_legionfall'], $general_table['rep_highmountain'], $general_table['rep_valarjar'], $general_table['rep_nightfallen'], $general_table['rep_dreamweaver'], $general_table['rep_wardens']);
		
	foreach($reputation_array as $faction) {
		if($faction >= '42000') {
			$rep = '<span style="color: cyan;" title="' .$faction. '">Exalted</span>';
		}
		elseif($faction < '42000' && $faction >= '21000') {
			$rep = '<span style="color: #00ffcc;" title="' .$faction. '">Revered</span>';
		}
		elseif($faction < '21000' && $faction >= '9000') {
			$rep = '<span style="color: #00ff88;" title="' .$faction. '">Honored</span>';
		}
		elseif($faction < '9000' && $faction >= '3000') {
			$rep = '<span style="color: lime;" title="' .$faction. '">Friendly<span>';
		}
		elseif($faction < '3000') {
			$rep = '<span style="color: yellow;" title="' .$faction. '">Neutral or worse</span>';
		}
	
		echo '<td>' .$rep. '</td>';
	}
	
	echo '</tr>
	</tbody>
	</table>
	<br />
	<span style="color: orange; font-size: 20px;">Raidprogress & highest parse for difficulty if character could be found on Warcraftlogs</span>
	<br />
	<span style="color: black; font-size: 10px;">heroic & mythic - hover over numbers to see other difficulties<br />
	parse is directly from within the log, not the profile & rounded to 2 digits<br />
	the Warcraftlogs API is subject of change - bugs are possible</span>
	<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
	<thead>
		<tr>
			<th title="Nythendra">N</th>
			<th title="Elerethe Renferal">ER</th>
			<th title="Il\'gynoth">I</th>
			<th title="Ursoc">U</th>
			<th title="Dragons of Nightmare">DoN</th>
			<th title="Cenarius">C</th>
			<th title="Xavius">X</th>
		</tr>
	</thead>
	<tbody>
	<tr>';

	// EMERALD NIGHTMARE PROGRESS
		
	for($i = '1'; $i <= '7'; $i++) {
		$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_raid_1` WHERE `id` = '" .$i. "'"));
	
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
				<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '<br />
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
		<tr>
			<th title="Odyn">O</th>
			<th title="Guarm">G</th>
			<th title="Helya">H</th>
		</tr>
	</thead>
	<tbody>
	<tr>';

	// TRIAL OF VALOR PROGRESS
		
	for($i = '1'; $i <= '3'; $i++) {
		$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_raid_2` WHERE `id` = '" .$i. "'"));	
	
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
				<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '<br />
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
		<tr>
			<th title="Skorpyron">S</th>
			<th title="Chronomatic Anomaly">CA</th>
			<th title="Trilliax">Tr</th>
			<th title="Spellblade Aluriel">SA</th>
			<th title="Star Augur Etraeus">SAE</th>
			<th title="High Botanist Tel\'arn">HBT</th>
			<th title="Krosus">K</th>
			<th title="Tichondrius">Ti</th>
			<th title="Elisande">E</th>
			<th title="Gul\'dan">G</th>
		</tr>
	</thead>
	<tbody>
	<tr>';

	// NIGHTHOLD PROGRESS
		
	for($i = '1'; $i <= '10'; $i++) {
		$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_raid_3` WHERE `id` = '" .$i. "'"));
			
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
				<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '<br />
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
		<tr>
			<th title="Goroth">G</th>
			<th title="Demonic Inquisition">DI</th>
			<th title="Harjatan the Bludger">HtB</th>
			<th title="Mistress Sassz\'ine">MS</th>
			<th title="Sisters of the Moon">SotM</th>
			<th title="The Desolate Host">TDH</th>
			<th title="Maiden of Vigilance">MoV</th>
			<th title="Fallen Avatar">FA</th>
			<th title="Kil\'jaeden">K</th>
		</tr>
	</thead>
	<tbody>
	<tr>';

	// TOMB OF SARGERAS PROGRESS
		
	for($i = '1'; $i <= '9'; $i++) {
		$kills = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$user_id. "_raid_4` WHERE `id` = '" .$i. "'"));
			
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
				<span style="color: ' .$color_hc. ';">' .$kills['heroic']. ' HC</span> ' .$hc_log. '<br />
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

}


?>