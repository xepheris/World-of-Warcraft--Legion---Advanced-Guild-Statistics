<?php

session_start();

include('stream.php');

$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';

$rows = mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "` WHERE `class` = '0'");

$update_this = array();
while($char = mysqli_fetch_array($rows)) {
	array_push($update_this, $char['name']);
}

$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));

$escaped_session_guild_name = str_replace(' ', '%20', $_SESSION['guild']);
$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .$_SESSION['realm']. "'"));

$url = 'https://' .$_SESSION['region']. '.api.battle.net/wow/guild/' .$actual_realm_name['short']. '/' .$escaped_session_guild_name. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';

$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);

$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));

if($data != '') {
	$data = json_decode($data, true);
	
	$i = '0';
	
	$guild_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_guilds` WHERE `guild_name` = '" .$_SESSION['guild']. "'"));
	
	foreach($update_this as $char) {
		foreach($data['members'] as $members) {
			if($members['character']['name'] == $char) {
				
				// FETCH GENERAL INFORMATION
				$class = $members['character']['class'];
				$realm = $members['character']['realm'];
				
				$realm = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `short` = '" .$actual_realm_name['short']. "'"));
				
				$realm = $realm['id'];
				
				$update = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `realm` = '" .$realm. "', `status` = '0', `class` = '" .$class. "'	WHERE `name` = '" .$char. "'");
				
				$fetch_char_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `name` = '" .$char. "'"));
				
				// CREATE INDIVIDUAL CHARACTER TABLES
				$legendary_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_legendaries` (`id` int(11) NOT NULL AUTO_INCREMENT, `item_id` int(6) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `item_id` (`item_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
								
				$weapon_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_weapons` (`id` int(11) NOT NULL AUTO_INCREMENT, `item_id` mediumint(6) NOT NULL, `itemlevel` mediumint(4) NOT NULL, `bonus` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r1` mediumint(6) NOT NULL, `bonus_r1` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r2` mediumint(6) NOT NULL, `bonus_r2` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r3` mediumint(6) NOT NULL, `bonus_r3` varchar(20) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=3";
				
				$dungeons_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_dungeons` (`id` int(11) NOT NULL AUTO_INCREMENT, `normal` mediumint(5) NOT NULL, `heroic` mediumint(5) NOT NULL, `mythic` mediumint(5) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
				
				$equip_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_equip` (`id` int(11) NOT NULL AUTO_INCREMENT, `itemid` mediumint(6) NOT NULL, `itemlevel` smallint(4) NOT NULL, `bonus` varchar(20) COLLATE latin1_german2_ci NOT NULL, `enchant` mediumint(6) NOT NULL, `gem` mediumint(6) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;";
				
				$general_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_general` (`id` int(11) NOT NULL AUTO_INCREMENT, `ilvl_on` mediumint(4) NOT NULL, `ilvl_off` mediumint(4) NOT NULL, `alvl` smallint(3) NOT NULL, `ak` smallint(2) NOT NULL, `ap` bigint(16) NOT NULL, `m2` smallint(4) NOT NULL, `m5` smallint(4) NOT NULL, `m10` smallint(4) NOT NULL, `m15` smallint(4) NOT NULL, `m_achv` smallint(2) NOT NULL, `wq` mediumint(5) NOT NULL, `rep_nightfallen` int(6) NOT NULL, `rep_valarjar` int(6) NOT NULL, `rep_wardens` int(6) NOT NULL, `rep_farondis` int(6) NOT NULL, `rep_highmountain` int(6) NOT NULL, `rep_dreamweaver` int(6) NOT NULL, `rep_legionfall` int(6) NOT NULL, UNIQUE KEY `id` (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;";
				
				$raid_1_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_1` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
								
				$raid_2_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_2` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
				
				$raid_3_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_3` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
				
				$raid_4_table = "CREATE TABLE IF NOT EXISTS `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";
				
				$en_boss = array("Nythendra", "Elerethe Renferal", "Il'gynoth", "Ursoc", "Dragons of Nightmare", "Cenarius", "Xavius");
				$tov_boss = array("Odyn", "Guarm", "Helya");
				$nh_boss = array("Skorpyron", "Chronomatic Anomaly", "Trilliax", "Spellblade Aluriel", "Star Augur Etraeus", "High Botanist Tel'arn", "Tichondrius", "Krosus", "Elisande", "Gul'dan");
				$tos_boss = array("Goroth", "Demonic Inquisition", "Harjatan the Bludger", "Mistress Sassz'ine", "Sisters of the Moon", "The Desolate Host", "Maiden of Vigilance", "Fallen Avatar", "Kil'jaeden");
				
				$sql_array = array($legendary_table, $weapon_table, $dungeons_table, $raid_1_table, $raid_2_table, $raid_3_table, $raid_4_table, $equip_table, $general_table);

				foreach($sql_array as $sql) {
					$execute = mysqli_query($stream, $sql);
				}
				
				$k = '1';
				foreach($en_boss as $boss) {
					$raid_1_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_1` (`id`, `name`) VALUES ('" .$k. "', '" .addslashes($boss). "')");
					$k++;
				}
				
				$k = '1';
				foreach($tov_boss as $boss) {
					$raid_2_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_2` (`id`, `name`) VALUES ('" .$k. "', '" .addslashes($boss). "')");
					$k++;
				}
				
				$k = '1';
				foreach($nh_boss as $boss) {
					$raid_3_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_3` (`id`, `name`) VALUES ('" .$k. "', '" .addslashes($boss). "')");
					$k++;
				}
								
				$k = '1';
				foreach($tos_boss as $boss) {
					$raid_4_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` (`id`, `name`) VALUES ('" .$k. "', '" .addslashes($boss). "')");
					$k++;
				}
				
				require_once('import.php');
				
				crawl($char);
								
				$i++;
			}
		}
	}
	
	$current_chars = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `current_chars` FROM `" .$table_name. "`"));
	
	$sync = mysqli_query($stream, "UPDATE `ovw_guilds` SET `tracked_chars` = '" .$current_chars['current_chars']. "' WHERE `guild_name` = '" .$_SESSION['guild']. "'");
	
	$_SESSION['tracked'] = $current_chars['current_chars'];
	
	if($i == '1') {
		echo '<span style="color: orange; text-align: center;">' .$i. ' character has been imported.<br /><a href="http://artifactpower.info/dev">Click to view your roster.</a></span>';
	}
	elseif($i > '1') {
		echo '<span style="color: orange; text-align: center;">' .$i. ' characters have been imported.<br /><a href="http://artifactpower.info/dev">Click to view your roster.</a></span>';
	}
}

?>