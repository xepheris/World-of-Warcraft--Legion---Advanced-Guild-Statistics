<?php

echo '<div style="width: 5%; height: auto; padding-bottom: 15px; float: left;"></div>';

$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';
		
// IMPORT
if(isset($_GET['import'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">';
	
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
						echo '<span style="text-align: center; color: coral;">An error occured setting the role for ' .substr($var, '0', '-2'). '. Please try again.</span><br />';
					}
				}
				elseif($a_or_b == 'b') {
					$set_role_2 = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role2` = '" .$role. "' WHERE `name` = '" .substr($var, '0', '-2'). "';");
					if(!$set_role_2) {
						echo '<span style="text-align: center; color: coral;">An error occured setting the role for ' .substr($var, '0', '-2'). '. Please try again.</span><br />';
					}
				}
			}
		}
		
		echo '<span style="text-align: center; color: orange; font-size: 18px;">Maintenance scripts will run now to fetch all necessary information of selected characters.<br />
		Depending on the amount of characters you imported, this will take up to a few minutes, please stand by!<br />
		You will recieve a confirmation as soon as the import is finished.
		<br />
		<br />
		<div id="loading"><img src="img/load.gif" alt="404" onload="general_import()" /></div>
		<div id="loading2" onload="live_update()"></div>
		<div id="finished"></div>
		</span>';
			
	}
		
	if(!isset($_POST['c']) && !isset($_POST['roles'])) {		
					
		$table_start = mysqli_query($stream, "CREATE TABLE IF NOT EXISTS `" .$table_name. "` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `realm` smallint(4) NOT NULL, `class` tinyint(2) NOT NULL, `logout` int(10) NOT NULL, `updated` int(10) NOT NULL, `spec` tinyint(2) NOT NULL, `status` tinyint(1) NOT NULL, `role1` tinyint(1) NOT NULL, `role2` tinyint(1) NOT NULL, `talents` varchar(12) NOT NULL, `wlogs_id` int(9) NOT NULL, `eq` int(10) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;");			
			
		echo '<span style="color: orange; font-size: 18px;">Character Import, individually by Guild rank<br /><span style="font-size: 12px;">only showing characters that have not been imported yet</span></span>
		<br />
		<br />
		<form method="POST" style="text-align: center;">
		<select multiple name="c[]" style="width: 250px; height: 70vh;">';
			
		$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));
		
		$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .$_SESSION['realm']. "'"));
		
		$escaped_guild_name = str_replace(' ', '%20', $_SESSION['guild']);
		
		$url = 'https://' .$_SESSION['region']. '.api.battle.net/wow/guild/' .$actual_realm_name['short']. '/' .$escaped_guild_name. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';

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
		else {
			echo '</select>
			<p style="color: coral;">UNKNOWN ERROR - please contact the admin with your guild information!</p>';
		}
		
		echo '</select>
		<br />
		<button type="submit">Continue</button>
		</form>';
	}
	
	if(isset($_POST['c'])) {		
			
		echo '<style>
			tr:nth-child(even) {
				background-color: #90805f !important;
			}
		</style>
		<span style="color: orange; font-size: 16px; margin-top: 15px;">Attribute roles</span>
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
	
	echo '</div>
	<div style="width: 5%; float: left;">
	</div>';
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

// BENCH
elseif(isset($_GET['bench']) && is_numeric($_GET['bench'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';
	
	echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">benching...</span>';
		
	include('stream.php');
		
	$bench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '1' WHERE `id` = '" .$_GET['bench']. "'");
		
	if($bench) {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	}
	elseif(!$bench) {
		$error = '<span style="color: coral; text-align: center;">Could not <u>bench</u> player (ID ' .$_GET['bench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}
	
	echo '</div>';

}

// UNBENCH
elseif(isset($_GET['unbench']) && is_numeric($_GET['unbench'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';
		
	echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">unbenching...</span>';
		
	include('stream.php');
		
	$unbench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '0' WHERE `id` = '" .$_GET['unbench']. "'");
		
	if($unbench) {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	}
	elseif(!$unbench) {
		$error = '<span style="color: coral; text-align: center;">Could not unbench player (ID ' .$_GET['unbench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}
	
	echo '</div>';

}

// KICK
elseif(isset($_GET['kick']) && is_numeric($_GET['kick'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';
		
	echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">removing...</span>';
		
	include('stream.php');
		
	$kick = mysqli_query($stream, "DROP TABLE `" .$_SESSION['table']. "_" .$_GET['kick']. "_dungeons`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_equip`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_general`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_legendaries`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_1`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_2`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_3`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_4`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_weapons`;");
		
	$update = mysqli_query($stream, "DELETE FROM `" .$table_name. "` WHERE `id` = '" .$_GET['kick']. "'");
	$realm_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `name` = '" .$_SESSION['realm']. "'"));
		
	$update = mysqli_query($stream, "UPDATE `ovw_guilds` SET `tracked_chars` = `tracked_chars` -1 WHERE `guild_name` = '" .$_SESSION['guild']. "' AND `region` = '" .$_SESSION['region']. "' AND `realm` = '" .$realm_id['id']. "'");
		
	$_SESSION['tracked'] = $_SESSION['tracked']-1;
		
	echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	
	echo '</div>';
}

// MANUALLY CHANGE NAME VIA INSPECT MODULE

elseif(isset($_GET['change_name']) && is_numeric($_GET['change_name'])) {
	
	include('stream.php');
	
	include('inspect/change_name.php');
}

// MANUALLY REASSIGN ROLES VIA INSPECT MODULE

elseif(isset($_GET['change_role']) && is_numeric($_GET['change_role'])) {
	
	include('stream.php');
	
	include('inspect/change_role.php');
}

// MANUALLY ADD LEGENDARIES VIA INSPECT MODULE

elseif(isset($_GET['edit_legendaries']) && is_numeric($_GET['edit_legendaries'])) {
	
	include('stream.php');
	
	include('inspect/edit_legendaries.php');
}

// EQ EXPLANATION

elseif(isset($_GET['eq'])) {
	
	include('stream.php');
	
	include('eq/index.php');	
	
}

// WEEKLY STATISTICS

elseif(isset($_GET['weekly'])) {
	
	include('stream.php');
	
	include('weekly/.php');
	
}

// COMPARE

elseif(isset($_GET['source']) && is_numeric($_GET['source']) && isset($_GET['compare1']) && is_numeric($_GET['compare1'])) {
	
	include('stream.php');
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
	if(!isset($_GET['compare4']) && isset($_GET['compare3']) && is_numeric($_GET['compare3']) && isset($_GET['compare2']) && is_numeric($_GET['compare2'])) {
		include('compare/c3.php');
	}
	elseif(!isset($_GET['compare4']) && !isset($_GET['compare3']) && isset($_GET['compare2']) && is_numeric($_GET['compare2'])) {
		include('compare/c2.php');
	}
	elseif(!isset($_GET['compare4']) && !isset($_GET['compare3']) && !isset($_GET['compare2'])) {
		include('compare/c1.php');
	}
	
	echo '</div>';
}

// INSPECT

elseif(isset($_GET['inspect']) && is_numeric($_GET['inspect'])) {
	
	echo '
	<script type="text/javascript" src="js/update.js"></script>';
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
	if($_SESSION['tracked'] == '0') {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/?import" />';
	}
	elseif($_SESSION['tracked'] != '0') {
			
		// INSPECT SINGLE CHARACTER
		include('stream.php');
		
		// INSPECT HEAD
		
		include('inspect/head.php');
		
		// EQUIPMENT
		
		include('inspect/equip.php');		
				
		// DUNGEON PROGRESS
		
		include('inspect/dungeons.php');
		
		// REPUTATION
		
		include('inspect/reputation.php');
		
		// KNOWN LEGENDARIES
		
		include('inspect/legendaries.php');
				
		// RAIDPROGRESS
		
		include('inspect/raid.php');
		
		// PAST AP GRAPH
		
		include('inspect/past_ap.php');
		
		// PAST MYTHIC GRAPH
		
		include('inspect/past_mythic.php');
		
		// PAST WQ GRAPH
		
		include('inspect/past_wq.php');
		
		// PAST ITEMLEVEL GRAPH
		
		include('inspect/past_ilvl.php');
		
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
	
	echo '<script type="text/javascript">
	var roster = document.getElementById("roster").offsetHeight;
	var bench = document.getElementById("bench").offsetHeight;
	var sidebar = document.getElementById("sidebar");
	sidebar.style.height = roster+bench-15+"px";
	</script>';
	
}



?>