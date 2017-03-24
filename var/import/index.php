<?php

echo '<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">';

// GUEST = VIEW ONLY
if($_SESSION['guest'] != 'guest') {
	
	if($_SESSION['llogin'] == '0') {
		$refresh_login = mysqli_query($stream, "UPDATE `ovw_guilds` SET `last_login` = '" .time('now'). "' WHERE `id` = '" .$_SESSION['table']. "'");
	}	
		
	if(isset($_POST['roles'])) {
		
		$amount = count($_POST);
		$amount = $amount-1;
		$amount = $amount/2;

		if($amount < '1') {
			$amount = '1';
		}
		// X-REALM SUPPORT SCRIPT
		echo '
		<script type="text/javascript">
		
		var xrealm = $.ajax({
			type : "POST",
			url: "var/ajax/xrealm.php",
			type: "json",
			success: function (data) {
				var returnedData = JSON.parse(\'data\');
				for (var x = 0; x < returnedData.length; x++) {
                	content = returnedData[x].id;
                	content += "<br>";
                	content += returnedData[x].realm_id;
                	content += "<br>";
                	$(".test").html(content)
            	}				
			}
			});
				
		$.when(xrealm)
 			.then(function() {
			
		';
	
		
		// BEGIN IMPORT
		
		foreach($import as $id => $realm_id) {
						
			echo '
			var cell_' .$id. ' = document.getElementsByClassName("' .$id. '");
			var row' .$id. ' = document.getElementsByClassName("row' .$id. '");
			
			$.ajax({
				type: "GET",
				dataType: "html",
				url: "var/ajax/general_import.php",
					data: {
					character: +' .$id. ',
					realm: +' .$realm_id. '
					},
				success: function (data) {
					$(cell_' .$id. ').html("<span style=\"color: yellowgreen;\">Imported!</span>");
					row' .$id. '[0].style.transition = "opacity 1s ease-in-out";
					row' .$id. '[0].style.opacity = "1";
					row' .$id. '[0].style.filter = "alpha(opacity=100)";
				},
				error: function (data) {
					$(cell_' .$id. ').html("<span style=\"color: coral;\">Error!</span>");
					row' .$id. '[0].style.transition = "opacity 1s ease-in-out";
					row' .$id. '[0].style.opacity = "1";
					row' .$id. '[0].style.filter = "alpha(opacity=100)";
				}
			});			
			';
		}
		echo '
		
		
		}
		)
		
		</script>
		<script type="text/javascript">
		function general_import() {
			$.ajax({
				type: "GET",
				dataType: "html",
				url: "var/ajax/general_import.php",
				success: function(data) {
					$("#finished").html(data);
				}
			});
		}
		</script>
		';
		
		foreach($_POST as $var => $role) {
			if($var != 'roles') {
				$a_or_b = substr($var, '-1');
			
				if($a_or_b == 'a') {
					$set_role_1 = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role1` = '" .$role. "' WHERE `name` = '" .substr($var, '0', '-2'). "';");
				}
				elseif($a_or_b == 'b') {
					$set_role_2 = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role2` = '" .$role. "' WHERE `name` = '" .substr($var, '0', '-2'). "';");
				}
			}
		}
		
		echo '
		<p style="text-align: center; color: orange; font-size: 18px;">Fetching demanded characters... estimated wait time: <u>' .($amount*16). 's</u><br />
		Important: do NOT close this window or navigate to somewhere else during import!</p>
		<table style="margin: 0 auto; text-align: center;" onload="global_import();">
		<thead>
		<tr><th>Character</th><th>Status</th></tr>
		</thead>
		<tbody>';
		$all_new_entries = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `logout` = '0'");
		while($row = mysqli_fetch_array($all_new_entries)) {
			echo '<tr style="opacity: 0.4;" class="row' .$row['id']. '"><td>' .$row['name']. '</td><td class="' .$row['id']. '"><img src="img/load.gif" alt="404" title="loading" /></td></tr>';
		}	
		echo '
		</tbody>
		</table>
		<br />
		<div class="test"></div>';
		
	}
		
	if(!isset($_POST['c']) && !isset($_POST['roles'])) {		
					
		$table_start = mysqli_query($stream, "CREATE TABLE IF NOT EXISTS `" .$table_name. "` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL, `realm` smallint(4) NOT NULL, `class` tinyint(2) NOT NULL, `logout` int(10) NOT NULL, `updated` int(10) NOT NULL, `spec` tinyint(2) NOT NULL, `status` tinyint(1) NOT NULL, `role1` tinyint(1) NOT NULL, `role2` tinyint(1) NOT NULL, `talents` varchar(12) NOT NULL, `wlogs_id` int(9) NOT NULL, `eq` int(10) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;");			
			
		echo '<span style="color: orange; font-size: 18px;">Character Import, individually by Guild rank<br /><span style="font-size: 12px;">only showing characters that have not been imported yet</span></span>
		<br />
		<br />
		<form method="POST" style="text-align: center;">
		<select multiple name="c[]" style="width: 250px; height: 70vh;">';
			
		$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));
		
		$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .addslashes($_SESSION['realm']). "' AND `region` = '" .$_SESSION['region']. "'"));
		
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
	
	echo '</div>';
}
elseif($_SESSION['guest'] == 'guest') {
	echo '<span style="color: coral;">Sorry, guests may not edit this setting.<br />
	<a href="?inspect">Back</a></span>';
}

echo '</div>';

?>