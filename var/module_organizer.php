<?php

echo '<div style="width: 5%; height: auto; padding-bottom: 15px; float: left;"></div>';

$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';
		
// IMPORT
if(isset($_GET['import'])) {
	
	echo '<div style="width: 100%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
	include('stream.php');
	if($_SESSION['llogin'] == '0') {
		echo '<span style="color: orange; font-size: 18px;">Welcome! This appears to be your first visit. Please add characters before proceeding:</span><br />';
		$refresh_login = mysqli_query($stream, "UPDATE `ovw_guilds` SET `last_login` = '" .time('now'). "' WHERE `id` = '" .$_SESSION['table']. "'");
	}	
		
	if(isset($_POST['roles'])) {
			
		echo '<script type="text/javascript">
			function general_import() {
				$.ajax({
					type: "GET",
					dataType: "html",
					url: "var/ajax/general_import.php",
					success: function(data) {
						$( "#finished").html(data);
						document.getElementById("loading").style.display = "none";
					}
				});
			}
			</script>';
			
		foreach($_POST as $var => $role) {
			if($var != 'roles') {
				$a_or_b = substr($var, '-1');
			
				if($a_or_b == 'a') {
					$set_role_1 = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role1` = '" .$role. "' WHERE `name` = '" .substr($var, '0', '-2'). "';");
					if(!$set_role_1) {
						echo '<span style="text-align: center; color: red;">An error occured setting the role for ' .substr($var, '0', '-2'). '. Please try again.</span><br />';
					}
				}
				elseif($a_or_b == 'b') {
					$set_role_2 = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role2` = '" .$role. "' WHERE `name` = '" .substr($var, '0', '-2'). "';");
					if(!$set_role_2) {
						echo '<span style="text-align: center; color: red;">An error occured setting the role for ' .substr($var, '0', '-2'). '. Please try again.</span><br />';
					}
				}
			}
		}
		
		echo '<span style="text-align: center; color: orange; font-size: 18px;">Maintenance scripts will run now to fetch all necessary information of selected characters.<br />
		Depending on the amount of characters you imported, this will take up to a few minutes, please stand by!<br />
		The loading animation will disappear when completed.<br /><br />
		<div id="loading"><img src="img/load.gif" alt="404" onload="general_import()" /></div>
		<div id="loading2" onload="live_update()"></div>
		<div id="finished"></div>
		</span>';
			
	}
		
	if(!isset($_POST['c']) && !isset($_POST['roles'])) {		
					
		$table_start = mysqli_query($stream, "CREATE TABLE IF NOT EXISTS `" .$table_name. "` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `realm` smallint(4) NOT NULL, `class` tinyint(2) NOT NULL, `logout` int(10) NOT NULL, `updated` int(10) NOT NULL, `spec` tinyint(2) NOT NULL, `status` tinyint(1) NOT NULL, `role1` tinyint(1) NOT NULL, `role2` tinyint(1) NOT NULL, `talents` varchar(12) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;");			
			
		echo '<span style="color: orange; font-size: 18px;">Character Import, individually by Guild rank<br /><span style="font-size: 12px;">only showing characters that have not been imported yet</span></span>
		<br />
		<br />
		<form method="POST" style="text-align: center;">
		<select multiple name="c[]" style="width: 250px; height: 80vh;">';
			
		$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));
		
		$url = 'https://' .$_SESSION['region']. '.api.battle.net/wow/guild/' .$_SESSION['realm']. '/' .$_SESSION['guild']. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';
		$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);

		$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		if($data != '') {
			$data = json_decode($data, true);
			$chararray = array();
			
			for($rank = '0'; $rank <= '9'; $rank++) {
				${'' .$rank. 'array'} = array();
				foreach($data['members'] as $members) {
					if($members['rank'] == $rank) {
						if($members['character']['level'] == '110') {
							array_push(${'' .$rank. 'array'}, $members['character']['name']);
						}
					}
				}
	
				sort(${'' .$rank. 'array'});
		
				echo '<optgroup label="Guildrank ' .($rank+1). '">';
					
				$current_characters = array();
				$already_imported = mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "`");
					
				while($char = mysqli_fetch_array($already_imported)) {
					array_push($current_characters, $char['name']);
				}
	
				foreach(${'' .$rank. 'array'} as $char) {
					if(!in_array($char, $current_characters)) {
						echo '<option value="' .$char. '">' .$char. '</option>';
					}
				}
	
				echo '</optgroup>';
			}
		}
		
		echo '</select>
		<br />
		<button type="submit">Continue</button>
		</form>';
	}
	
	if(isset($_POST['c'])) {		
			
		echo '<span style="color: orange; font-size: 16px; margin-top: 15px;">Attribute roles</span>
		<br />
		<br />
		<form method="POST" style="text-align: center;">
		<table style="margin: 0 auto;">
		<tr><th style="color: white;">Primary role</th><th style="color: white;">Secondary Role</th></tr>';
		foreach($_POST['c'] as $character) {
			$insertion = mysqli_query($stream, "INSERT INTO `" .$table_name. "` (`name`) VALUES ('" .$character. "')");
				
			echo '<tr>
				<td>
					<select name="' .$character. '_a" required>
						<option selected disabled>' .$character. '</option>
						<option value="0">Melee DPS</option>
						<option value="1">Ranged DPS</option>
						<option value="2">Tank</option>
						<option value="3">Heal</option>
					</select>
				</td>
				<td>
					<select name="' .$character. '_b">
						<option value="4" selected>none</option>
						<option value="0">Melee DPS</option>
						<option value="1">Ranged DPS</option>
						<option value="2">Tank</option>
						<option value="3">Heal</option>
					</select>
				</td>
			</tr>';
		}
		echo '</table>
		<br />
		<button type="submit">Import</button>
		<input type="text" name="roles" value="" hidden />
		</form>';
	}
	
	echo '</div>';
}
// LOGOUT
elseif(isset($_GET['logout'])) {
	echo '<span style="color: yellowgreen; font-size: 20px; text-align: center; margin-top: 15px;">logging out...</span>';
	unset($_SESSION);
	session_unset();
	echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
}
// UPDATE
elseif(isset($_GET['update'])) {
	// UPDATE EVERYTHING
	if($_GET['update'] == 'all') {
		die();
	}
	// UPDATE SINGLE CHARACTER
	elseif(is_numeric($_GET['update'])) {
		die();
	}
}

// WEEKLY INFORMATION
elseif(isset($_GET['weekly'])) {
	die();
}
// COMPARE
elseif(isset($_GET['compare'])) {
	die();
}
// CONTACT FORM
elseif(isset($_GET['contact'])) {
	die();
}

elseif(isset($_GET['bench']) && is_numeric($_GET['bench'])) {
	
	echo '<span style="color: greenyellow; text-align: center; font-size: 20px;">benching...</span>';
		
	include('stream.php');
		
	$bench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '1' WHERE `id` = '" .$_GET['bench']. "'");
		
	if($bench) {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	}
	elseif(!$bench) {
		$error = '<span style="color: red; text-align: center;">Could not <u>bench</u> player (ID ' .$_GET['bench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}

}
elseif(isset($_GET['unbench']) && is_numeric($_GET['unbench'])) {
		
	echo '<span style="color: greenyellow; text-align: center; font-size: 20px;">unbenching...</span>';
		
	include('stream.php');
		
	$unbench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '0' WHERE `id` = '" .$_GET['unbench']. "'");
		
	if($unbench) {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	}
	elseif(!$unbench) {
		$error = '<span style="color: red; text-align: center;">Could not unbench player (ID ' .$_GET['unbench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}

}
elseif(isset($_GET['kick']) && is_numeric($_GET['kick'])) {
		
	echo '<span style="color: greenyellow; text-align: center; font-size: 20px;">removing...</span>';
		
	include('stream.php');
		
	$kick = mysqli_query($stream, "DROP TABLE `" .$_SESSION['table']. "_" .$_GET['kick']. "_dungeons`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_equip`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_general`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_legendaries`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_1`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_2`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_3`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_4`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_weapons`;");
		
	$update = mysqli_query($stream, "DELETE FROM `" .$table_name. "` WHERE `id` = '" .$_GET['kick']. "'");
	$realm_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `name` = '" .$_SESSION['realm']. "'"));
		
	$update = mysqli_query($stream, "UPDATE `ovw_guilds` SET `tracked_chars` = `tracked_chars` -1 WHERE `guild_name` = '" .$_SESSION['guild']. "' AND `region` = '" .$_SESSION['region']. "' AND `realm` = '" .$realm_id['id']. "'");
		
	$_SESSION['tracked'] = $_SESSION['tracked']-1;
		
	echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
}

elseif(isset($_GET['inspect']) && is_numeric($_GET['inspect'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
	if($_SESSION['tracked'] == '0') {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/?import" />';
	}
	elseif($_SESSION['tracked'] != '0') {
			
		// INSPECT SINGLE CHARACTER
		include('stream.php');
		
		$general_char_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$_GET['inspect']. "'"));
		
		if($general_char_data['name'] == '') {
			$error = '<span style="color: red;">A character with this ID could not be found.<span>';
		}
		
		$class_color = mysqli_fetch_array(mysqli_query($stream, "SELECT `class`, `colorhex` FROM `ovw_classes` WHERE `id` = '" .$general_char_data['class']. "'"));	
		$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `ovw_weapons` WHERE `id` = '" .$general_char_data['spec']. "'"));
		
		echo '<div style="width: 100%; height: 60%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
		' .$error. '
		<span style="color: orange; text-align: center; font-size: 20px;">' .$general_char_data['name']. ' - ' .$spec['spec']. ' <span style="color: ' .$class_color['colorhex']. ';">' .$class_color['class']. '</span></span>
		<br />
		<span style="color: orange; text-align: center; font-size: 16px;">Last update: ' .date('d.m.y - H:m.i', $general_char_data['updated']). ' - Last known logout: ' .date('d.m.y - H:m:i', $general_char_data['logout']). '<br />
		<a href="http://' .$_SESSION['region']. '.battle.net/wow/en/character/' .$_SESSION['realm']. '/' .$general_char_data['name']. '/simple">Armory</a> - <a href="http://www.wowprogress.com/character/' .$_SESSION['region']. '/' .$_SESSION['realm']. '/' .$general_char_data['name']. '">Wowprogress</a> - <a href="http://check.artifactpower.info/?c=' .$general_char_data['name']. '&r=' .$_SESSION['region']. '&s=' .$_SESSION['realm']. '">Adv Arm Acc</a> - <a href="https://www.warcraftlogs.com/search/?term=' .$general_char_data['name']. '">Warcraftlogs</a>
		<br />
		Compare with... <form action="" method="get" style="display: inline;"><select onchange="this.form.submit()" name="compare"><option selected disabled>select</option>';
		
		echo '<optgroup label="Same class">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$_GET['inspect']. 'and' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}		
		
		echo '</optgroup>
		<optgroup label="Same spec">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` = '" .$general_char_data['class']. "' AND `spec` = '" .$general_char_data['spec']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$_GET['inspect']. 'and' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}
		
		echo '</optgroup>
		<optgroup label="Everyone else">';
		
		$sql = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `id` != '" .$_GET['inspect']. "' AND `class` != '" .$general_char_data['class']. "' ORDER BY `name` ASC");
		while($compare_chars = mysqli_fetch_array($sql)) {
			echo '<option value="' .$_GET['inspect']. 'and' .$compare_chars['id']. '">' .$compare_chars['name']. '</option>';
		}	
		
		echo '</optgroup>
		</select>
		</form>
		</span>
		</div>
		
		<script defer src="http://wow.zamimg.com/widgets/power.js"></script>
		<script>
			var wowhead_tooltips = {
			iconizelinks: true,
			renamelinks: true,
				"hide": {
				"droppedby": true,
				"dropchance": true,
				"sellprice": true,
				"maxstack": true,
			}
		}
		</script>
		<style>
		tr:nth-child(even) {
			background-color: #90805f !important;
		}
		</style>
		
		<div style="width: 40%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
		<span style="color: orange; font-size: 20px;">Current Equipment</span>
		<table style="margin: 0 auto; text-align: left; margin-top: 15px; border-bottom: 1px solid white;">
		<tbody>';
		
		$slots = array('1' => 'Head', '2' => 'Neck', '3' => 'Shoulders', '4' => 'Back', '5' => 'Chest', '6' => 'Wrists', '7' => 'Hands', '8' => 'Waist', '9' => 'Legs', '10' => 'Feet', '11' => 'Finger1', '12' => 'Finger2', '13' => 'Trinket1', '14' => 'Trinket2');
		$weapon = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_weapons`"));
		
		foreach($slots as $id => $slot) {
			$item_info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_equip` WHERE `id` = '" .$id. "'"));
						
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
		<table style="margin: 0 auto; text-align: center;">
		<tbody>
		<tr>
			<td colspan="3"><a href="http://wowhead.com/?item=' .$weapon['item_id']. '&bonus=' .$weapon['bonus']. '" rel="gems=' .$weapon['r1']. ':' .$weapon['r2']. ':' .$weapon['r3']. '">' .$weapon['item_id']. '</a> (' .$weapon['itemlevel']. ')</td>
		</tr>
		<tr>
			<td><a href="http://wowhead.com/?item=' .$weapon['r1']. '">' .$weapon['r1']. '</a></td>
			<td><a href="http://wowhead.com/?item=' .$weapon['r2']. '">' .$weapon['r2']. '</a></td>
			<td><a href="http://wowhead.com/?item=' .$weapon['r3']. '">' .$weapon['r3']. '</a></td>
		</tr>		
		</tbody>
		</table>
		</div>';
	}
	
	echo '</div>';
}

// ROSTER OVERVIEW
else {
	echo '<div style="width: 80%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';

	// AUTO REDIRECT
	if($_SESSION['tracked'] == '0') {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/?import" />';
	}
	elseif($_SESSION['tracked'] != '0') {
		
		// ROSTER OVERVIEW
		include('stream.php');
		
		include('roster/core.php');
	}
	
	echo '</div>';
}



?>