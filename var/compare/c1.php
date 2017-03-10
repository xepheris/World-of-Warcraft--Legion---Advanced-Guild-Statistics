<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';

$general_char_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$_GET['source']. "'"));

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

echo '<a href="http://' .$_SESSION['region']. '.battle.net/wow/en/character/' .$_SESSION['realm']. '/' .$general_char_data['name']. '/simple" title="WoW Armory link">' .$general_char_data['name']. '</a> - 
		<a href="http://eu.battle.net/wow/de/tool/talent-calculator#' .$general_char_data['talents']. '" title="WoW Talent Calculator link">' .$spec['spec']. '</a>
		<span style="color: ' .$class_color['colorhex']. ';">' .$class_color['class']. '</span>
		<br />
		<a href="" style="text-transform: uppercase; color: orange;" title="AGS External View">AGS External View</a> | 
		<a href="http://www.wowprogress.com/character/' .$_SESSION['region']. '/' .$_SESSION['realm']. '/' .$general_char_data['name']. '" style="font-family:verdana,arial,sans-serif;" title="WoWProgress profile link">WoWProgress</a> | 
		' .$warcraftlogs_link. '
		<br />
		Last update: ' .$last_update. ' <img src="img/update.png" alt="404" title="Update ' .$general_char_data['name']. '" style="width: 16px;" id="' .$_GET['source']. '" onclick="update(this.id);" /><br />
		Last known logout: ' .date('d.m.y - H:i:s', $general_char_data['logout']). '<br />';

$general_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['source']. "_general`"));
$fetch_eq = mysqli_fetch_array(mysqli_query($stream, "SELECT `eq` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['source']. "'"));

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

echo '<span style="font-size: 20px;">' .$fetch_eq['eq']. ' <a href="?eq" title="What is EQ?">Effort Quota</a></span><br />
		' .number_format($general_table['ap']). ' AP collected | Artifact Level ' .$alvl. ' | Artifact Knowledge ' .$ak. '<br />
		' .$general_table['wq']. ' World Quests completed';


echo '</div>';









echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">';

$general_char_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$_GET['compare1']. "'"));

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

echo '<a href="http://' .$_SESSION['region']. '.battle.net/wow/en/character/' .$_SESSION['realm']. '/' .$general_char_data['name']. '/simple" title="WoW Armory link">' .$general_char_data['name']. '</a> - 
		<a href="http://eu.battle.net/wow/de/tool/talent-calculator#' .$general_char_data['talents']. '" title="WoW Talent Calculator link">' .$spec['spec']. '</a>
		<span style="color: ' .$class_color['colorhex']. ';">' .$class_color['class']. '</span>
		<br />
		<a href="" style="text-transform: uppercase; color: orange;" title="AGS External View">AGS External View</a> | 
		<a href="http://www.wowprogress.com/character/' .$_SESSION['region']. '/' .$_SESSION['realm']. '/' .$general_char_data['name']. '" style="font-family:verdana,arial,sans-serif;" title="WoWProgress profile link">WoWProgress</a> | 
		' .$warcraftlogs_link. '
		<br />
		Last update: ' .$last_update. ' <img src="img/update.png" alt="404" title="Update ' .$general_char_data['name']. '" style="width: 16px;" id="' .$_GET['compare1']. '" onclick="update(this.id);" /><br />
		Last known logout: ' .date('d.m.y - H:i:s', $general_char_data['logout']). '<br />';

$general_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['compare1']. "_general`"));
$fetch_eq = mysqli_fetch_array(mysqli_query($stream, "SELECT `eq` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['compare1']. "'"));

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

echo '<span style="font-size: 20px;">' .$fetch_eq['eq']. ' <a href="?eq" title="What is EQ?">Effort Quota</a></span><br />
		' .number_format($general_table['ap']). ' AP collected | Artifact Level ' .$alvl. ' | Artifact Knowledge ' .$ak. '<br />
		' .$general_table['wq']. ' World Quests completed';


echo '</div>';


?>