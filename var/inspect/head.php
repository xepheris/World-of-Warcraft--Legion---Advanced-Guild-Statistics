<?php

$general_char_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$_GET['inspect']. "'"));

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

switch ($general_char_data['role1']) {
	case '0':
		$role1 = 'Primary role (log role): <img src="img/dps.png" alt="404" title="Primary: Melee DPS" style="width: 16px; vertical-align: sub;" />';
		break;
	case '1':
		$role1 = 'Primary role (log role): <img src="img/rdps.png" alt="404" title="Primary: Ranged DPS" style="width: 16px; vertical-align: sub;" />';
		break;
	case '2':
		$role1 = 'Primary role (log role): <img src="img/tank.png" alt="404" title="Primary: Tank" style="width: 16px; vertical-align: sub;" />';
		break;
	case '3':
		$role1 = 'Primary role (log role): <img src="img/heal.png" alt="404" title="Primary: Heal" style="width: 16px; vertical-align: sub;" />';
		break;					
}
			
switch ($general_char_data['role2']) {
	case '0':
		$role2 = ' / Secondary role: <img src="img/dps.png" alt="404" title="Secondary: Melee DPS" style="width: 16px; vertical-align: sub;" />';
		break;
	case '1':
		$role2 = ' / Secondary role: <img src="img/rdps.png" alt="404" title="Secondary: Ranged DPS" style="width: 16px; vertical-align: sub;" />';
		break;
	case '2':
		$role2 = ' / Secondary role: <img src="img/tank.png" alt="404" title="Secondary: Tank" style="width: 16px; vertical-align: sub;" />';
		break;
	case '3':
		$role2 = ' / Secondary role: <img src="img/heal.png" alt="404" title="Secondary: Heal" style="width: 16px; vertical-align: sub;" />';
		break;
	case '4':
		$role2 = '';
		break;
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

// LOADING DIV
echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); display: none; margin-top: 15px; margin-bottom: 15px; opacity: 1.0 !important;" id="patience">
<span style="text-align: center; color: orange; text-transform: uppercase; font-size: 20px;" id="patience_text">Updating this character - please be patient, this will take a few seconds!<br /></span>
<div id="answer"></div>
</div>';


// REGULAR CONTENT

$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .$_SESSION['realm']. "'"));

echo '<div style="width: 100%; height: 60%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);" class="inspect">
' .$error. '

<div style="width: 100%; height: auto; float: left; border-bottom: 1px solid white; padding-bottom: 10px; font-size: 20px;">

	<div style="float: left; padding-left: 6px; color: orange;">
		<a href="http://' .$_SESSION['region']. '.battle.net/wow/en/character/' .$_SESSION['realm']. '/' .$general_char_data['name']. '/simple" title="WoW Armory link">' .$general_char_data['name']. '</a> - 
		<a href="http://eu.battle.net/wow/de/tool/talent-calculator#' .$general_char_data['talents']. '" title="WoW Talent Calculator link">' .$spec['spec']. '</a> 
		<span style="color: ' .$class_color['colorhex']. ';">' .$class_color['class']. '</span> <a href="?change_name=' .$_GET['inspect']. '" title="Change name of ' .$general_char_data['name']. '" style="font-size: 13px;">(change name)</a>
	</div>

	<div style="padding-right: 6px; text-align: right;">
		<a href="" style="text-transform: uppercase; font-size: 20px; color: orange;" title="AGS External View">AGS External View</a> | 
		<a href="http://www.wowprogress.com/character/' .$_SESSION['region']. '/' .$actual_realm_name['short']. '/' .$general_char_data['name']. '" style="font-family:verdana,arial,sans-serif;" title="WoWProgress profile link">WoWProgress</a> | 
		' .$warcraftlogs_link. '
	</div>
</div>

<div style="width: 100%; height: auto; float: left;">
	<div style="width: 50%; height: auto; float: left; padding-top: 10px; text-align: left;">
		<div style="padding-left: 6px;">		
		<span style="color: orange; text-align: center; font-size: 16px;">
		' .$role1. '' .$role2. ' <a href="?change_role=' .$_GET['inspect']. '" style="font-size: 13px;">(change roles)</a><br />
		Last update: ' .$last_update. ' <img src="img/update.png" alt="404" title="Update ' .$general_char_data['name']. '" style="width: 16px;" id="' .$_GET['inspect']. '" onclick="update(this.id);" /><br />
		Last known logout: ' .date('d.m.y - H:i:s', $general_char_data['logout']). '<br />
		Compare with... <form action="" method="get" style="display: inline;">
		<input hidden name="source" value="' .$_GET['inspect']. '" />
		<select required name="compare1">
		<option selected disabled>select</option>';
		
		echo '<optgroup label="Same spec">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' AND `spec` = '" .$general_char_data['spec']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Same class">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Everyone else">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` != '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}	
		
		echo '</optgroup>
		</select>
		
		
		<select name="compare2">
		<option selected disabled>select</option>';
		
		echo '<optgroup label="Same spec">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' AND `spec` = '" .$general_char_data['spec']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Same class">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Everyone else">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` != '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}	
		
		echo '</optgroup>
		</select>
		
		<select name="compare3">
		<option selected disabled>select</option>';
		
		echo '<optgroup label="Same spec">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' AND `spec` = '" .$general_char_data['spec']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Same class">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Everyone else">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` != '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}	
		
		echo '</optgroup>
		</select>
		
		<button type="submit">Compare</button>
		</form>
		</span>
	</div>
</div>';

$general_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_general`"));
$fetch_eq = mysqli_fetch_array(mysqli_query($stream, "SELECT `eq` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['inspect']. "'"));

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

// CHECK FOR PREVIOUS VALUES
$past_check = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap`, `wq` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_past` ORDER BY `timestamp` DESC LIMIT 1"));
if($past_check['ap'] != '' && $general_table['ap'] != $past_check['ap']) {
	$past_ap = '<span style="color: yellowgreen;" title="last update: ' .number_format($past_check['ap']). '">(+' .round(($general_table['ap']/$past_check['ap'])-1, 3). '%)</span>';
}
if($past_check['wq'] != '' && $general_table['wq'] != $past_check['wq']) {
	$past_wq = '<span style="color: yellowgreen;" title="last update: ' .$past_check['wq']. '">(+' .($general_table['wq']-$past_check['wq']). ')</span>';
}

echo '<div style="width: 50%; height: auto; text-align: right; float: left; color: orange; font-size: 16px; padding-top: 10px;">
	<div style="padding-right: 6px;">
		<span style="font-size: 20px;">' .$fetch_eq['eq']. ' <a href="?eq" title="What is EQ?">Effort Quota</a></span><br />
		' .number_format($general_table['ap']). ' ' .$past_ap. ' AP collected | Artifact Level ' .$alvl. ' | Artifact Knowledge ' .$ak. '<br />
		' .$general_table['wq']. ' ' .$past_wq. ' World Quests completed
	</div>
</div>
</div>
</div>';

?>