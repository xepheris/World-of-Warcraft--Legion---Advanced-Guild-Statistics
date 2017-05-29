<?php

echo '
<div id="bench" style="width: 100%; min-height: 30%; padding-top: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; min-width: 1563px;">
<span style="color: orange; text-align: center; font-size: 18px;">Bench</span>';
		
$benchcheck = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '1' ORDER BY `name` ASC"));
if($benchcheck['id'] != '') {
		
	echo '<table style="margin: 0 auto; margin-top: 15px; width: 100%; min-width: 1500px;">
	<thead>
	<tr>
		<th></th>
		<th>Updated (Logout)</th>
		<th></th>
		<th>Roles</th>
		<th>Spec</th>
		<th>Talents</th>
		<th>Legendaries</th>
		<th>Itemlevel</th>		
		<th><span title="Artifact Power">AP</span></th>
		<th><span title="Artifact Level">AL</span> <span title="Artifact Knowledge">(AK)</span></th>
		<th>Mythics<br />(M+ Achv)</th>
		<th>World Quests</th>
		<th><a href="?eq">EQ</a></th>
		<th><span title="Emerald Nightmare">EN</span><br />
			HC M
		</th>
		<th><span title="Trial of Valor">ToV</span><br />
			HC M
		</th>
		<th><span title="The Nighthold">NH</span><br />
			HC M
		</th>
		<th><span title="Tomb of Sargeras">ToS</span><br />
			HC M
		</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	</thead>
	<tbody>';
		
	$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '1' ORDER BY `name` ASC");
	while($id = mysqli_fetch_array($fetch_ids)) {
		$guild_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$id['id']. "'"));
		$fetch_general_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$id['id']. "_general`"));
		$fetch_dungeon_data = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`mythic`) AS `mythic` FROM `" .$_SESSION['table']. "_" .$id['id']. "_dungeons`"));
		$class_color = mysqli_fetch_array(mysqli_query($stream, "SELECT `class`, `color` FROM `ovw_classes` WHERE `id` = '" .$guild_table['class']. "'"));
		$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `ovw_weapons` WHERE `id` = '" .$guild_table['spec']. "'"));
			
		$legendaries = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `amount` FROM `" .$_SESSION['table']. "_" .$id['id']. "_legendaries`"));
			
		$en_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_1` WHERE `heroic` > '0'"));
		$en_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_1` WHERE `mythic` > '0'"));
			
		$tov_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_2` WHERE `heroic` > '0'"));
		$tov_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_2` WHERE `mythic` > '0'"));
			
		$nh_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_3` WHERE `heroic` > '0'"));
		$nh_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_3` WHERE `mythic` > '0'"));
			
		$tos_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_4` WHERE `heroic` > '0'"));
		$tos_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_4` WHERE `mythic` > '0'"));
		
		
		// ARTIFACT LEVEL COLORIZATION DEPENDING ON ARTIFACT KNOWLEDGE
		// PRE 7.2
		if($fetch_general_data['ak'] <= '25') {
			if($fetch_general_data['alvl'] <= '35') {
				$alvl_color = 'coral';
			}
			elseif($fetch_general_data['alvl'] > '35' && $fetch_general_data['alvl'] < '54') {
				$alvl_color = 'orange';
			}
			elseif($fetch_general_data['alvl'] == '54') {
				$alvl_color = 'yellowgreen';
			}			
		}
		// POST 7.2
		elseif($fetch_general_data['ak'] > '25') {
			if($fetch_general_data['alvl'] <= '35') {
				$alvl_color = 'coral';
			}
			elseif($fetch_general_data['alvl'] > '35' && $fetch_general_data['alvl'] < '52') {
				$alvl_color = 'orange';
			}
			elseif($fetch_general_data['alvl'] == '52') {
				$alvl_color = 'yellowgreen';
			}
		}
		
		$artifact_level = '<span style="color: ' .$alvl_color. ';">' .$fetch_general_data['alvl']. '</span>';
		
		// ARTIFACT KNOWLEDGE LEVEL PROGRESS DEPENDING ON VALUE
		// PRE 7.2
		if($fetch_general_data['ak'] <= '25') {
			if($fetch_general_data['ak'] <= '12') {
				$ak_color = 'coral';
			}
			elseif($fetch_general_data['ak'] > '12' && $fetch_general_data['ak'] < '25') {
				$ak_color = 'orange';
			}
			elseif($fetch_general_data['ak'] == '25') {
				$ak_color = 'yellowgreen';
			}
		}
		// POST 7.2
		elseif($fetch_general_data['ak'] > '25') {
			if($fetch_general_data['ak'] <= '26') {
				$ak_color = 'coral';
			}
			elseif($fetch_general_data['ak'] > '26' && $fetch_general_data['ak'] < '50') {
				$ak_color = 'orange';
			}
			elseif($fetch_general_data['ak'] == '50') {
				$ak_color = 'yellowgreen';
			}
		}
	
		$artifact_knowledge = '<span style="color: ' .$ak_color. ';">' .$fetch_general_data['ak']. '</span>';
			
		if($fetch_general_data['m_achv'] <= '2') {
			$mplus_color = 'coral';
		}
		elseif($fetch_general_data['m_achv'] > '2' && $fetch_general_data['m_achv'] <= '5') {
			$mplus_color = 'orange';
		}
		elseif($fetch_general_data['m_achv'] > '5' && $fetch_general_data['m_achv'] <= '10') {
			$mplus_color = 'yellow';
		}
		elseif($fetch_general_data['m_achv'] == '15') {
			$mplus_color = 'yellowgreen';
		}
	
		$m_achievement = '<span style="color: ' .$mplus_color. ';">' .$fetch_general_data['m_achv']. '</span>';
			
		switch ($guild_table['role1']) {
			case '0':
				$role1 = '<img src="img/dps.png" alt="404" width="21px" title="Primary: Melee DPS" />';
				break;
			case '1':
				$role1 = '<img src="img/rdps.png" alt="404" width="21px" title="Primary: Ranged DPS" />';
				break;
			case '2':
				$role1 = '<img src="img/tank.png" alt="404" width="21px" title="Primary: Tank" />';
				break;
			case '3':
				$role1 = '<img src="img/heal.png" alt="404" width="21px" title="Primary: Heal" />';
				break;					
		}
			
		switch ($guild_table['role2']) {
			case '0':
				$role2 = '<img src="img/dps.png" alt="404" width="21px" title="Secondary: Melee DPS" />';
				break;
			case '1':
				$role2 = '<img src="img/rdps.png" alt="404" width="21px" title="Secondary: Ranged DPS" />';
				break;
			case '2':
				$role2 = '<img src="img/tank.png" alt="404" width="21px" title="Secondary: Tank" />';
				break;
			case '3':
				$role2 = '<img src="img/heal.png" alt="404" width="21px" title="Secondary: Heal" />';
				break;
			case '4':
				$role2 = '';
				break;
		}
			
		// COLORIZATION OF INDIVIDUAL EMERALD NIGHTMARE PROGRESS			
		if($en_heroic_progress['en_hc'] == '7') {
			$en_hc_color = 'yellowgreen';
		}
		elseif($en_heroic_progress['en_hc'] > '0' && $en_heroic_progress['en_hc'] < '7') {
			$en_hc_color = 'orange';
		}
		elseif($en_heroic_progress['en_hc'] == '0') {
			$en_hc_color = 'coral';
		}
		$en_hc = '<span style="color: ' .$en_hc_color. ';">' .$en_heroic_progress['en_hc']. ' |</span>';
		
		if($en_mythic_progress['en_m'] == '7') {
			$en_m_color = 'yellowgreen';
		}
		elseif($en_mythic_progress['en_m'] > '0' && $en_mythic_progress['en_m'] < '7') {
			$en_m_color = 'orange';
		}
		elseif($en_mythic_progress['en_m'] == '0') {
			$en_m_color = 'coral';
		}
		$en_m = '<span style="color: ' .$en_m_color. ';">' .$en_mythic_progress['en_m']. '</span>';
	
		// COLORIZATION OF INDIVIDUAL TRIAL OF VALOR PROGRESS
		if($tov_heroic_progress['tov_hc'] == '3') {
			$tov_hc_color = 'yellowgreen';
		}
		elseif($tov_heroic_progress['tov_hc'] > '0' && $tov_heroic_progress['tov_hc'] < '3') {
			$tov_hc_color = 'orange';
		}
		elseif($tov_heroic_progress['tov_hc'] == '0') {
			$tov_hc_color = 'coral';
		}
		$tov_hc = '<span style="color: ' .$tov_hc_color. ';">' .$tov_heroic_progress['tov_hc']. ' |</span>';
			
		if($tov_mythic_progress['tov_m'] == '3') {
			$tov_m_color = 'yellowgreen';
		}
		elseif($tov_mythic_progress['tov_m'] > '0' && $tov_mythic_progress['tov_m'] < '3') {
			$tov_m_color = 'orange';
		}
		elseif($tov_mythic_progress['tov_m'] == '0') {
			$tov_m_color = 'coral';
		}
		$tov_m = '<span style="color: ' .$tov_m_color. ';">' .$tov_mythic_progress['tov_m']. '</span>';
			
		// COLORIZATION OF INDIVIDUAL NIGHTHOLD PROGRESS
		if($nh_heroic_progress['nh_hc'] == '10') {
			$nh_hc_color = 'yellowgreen';
		}
		elseif($nh_heroic_progress['nh_hc'] > '0' && $nh_heroic_progress['nh_hc'] < '10') {
			$nh_hc_color = 'orange';
		}
		elseif($nh_heroic_progress['nh_hc'] == '0') {
			$nh_hc_color = 'coral';
		}
		$nh_hc = '<span style="color: ' .$nh_hc_color. ';">' .$nh_heroic_progress['nh_hc']. ' |</span>';
	
		if($nh_mythic_progress['nh_m'] == '10') {
			$nh_m_color = 'yellowgreen';
		}
		elseif($nh_mythic_progress['nh_m'] > '0' && $nh_mythic_progress['nh_m'] < '10') {
			$nh_m_color = 'orange';
		}
		elseif($nh_mythic_progress['nh_m'] == '0') {
			$nh_m_color = 'coral';
		}
		$nh_m = '<span style="color: ' .$nh_m_color. ';">' .$nh_mythic_progress['nh_m']. ' </span>';
	
		// COLORIZATION OF INDIVIDUAL TOMB OF SARGERAS PROGRESS
		if($tos_heroic_progress['tos_hc'] == '9') {
			$tos_hc_color = 'yellowgreen';
		}
		elseif($tos_heroic_progress['tos_hc'] > '0' && $tos_heroic_progress['tos_hc'] < '9') {
			$tos_hc_color = 'orange';
		}
		elseif($tos_heroic_progress['tos_hc'] == '0') {
			$tos_hc_color = 'coral';
		}
		$tos_hc = '<span style="color: ' .$tos_hc_color. ';">' .$tos_heroic_progress['tos_hc']. ' |</span>';
		
		if($tos_mythic_progress['tos_m'] == '9') {
			$tos_m_color = 'yellowgreen';
		}
		elseif($tos_mythic_progress['tos_m'] > '0' && $tos_mythic_progress['tos_m'] < '9') {
			$tos_m_color = 'orange';
		}
		elseif($tos_mythic_progress['tos_m'] == '0') {
			$tos_m_color = 'coral';
		}
		$tos_m = '<span style="color: ' .$tos_m_color. ';">' .$tos_mythic_progress['tos_m']. ' </span>';
	
		// UPDATED TIMER CONVERTER
		if(time('now')-$guild_table['updated'] <= '86400') {
			$last_update = '<span style="color: yellowgreen;">' .date('d.m.y - H:i:s', $guild_table['updated']). '</span>';
		}
		else {
			$last_udpate = '<span style="color: coral;">' .date('d.m.y - H:i:s', $guild_table['updated']). '</span>';
		}
		
		// LOGGED OUT TIMER CONVERTER
		if(time('now')-$guild_table['logout'] <= '86400') {
			$last_logout = '' .round(((time('now')-$guild_table['logout'])/3600), 2). ' hrs ago';
		}
		elseif(time('now')-$guild_table['logout'] > '86400') {
			if($guild_table['logout'] >= '86400' && $guild_table['logout'] < '172800') {
				$last_logout = '' .round(((time('now')-$guild_table['logout'])/86400), 2). ' day ago';
			}
			elseif($guild_table['logout'] >= '172800') {
				$last_logout = '' .round(((time('now')-$guild_table['logout'])/86400), 2). ' days ago';
			}
		}
		
		// AP READABILITY CONVERTER
		if(strlen($fetch_general_data['ap']) <= '3') {
			$ap = number_format($fetch_general_data['ap']);
		}
		elseif(strlen($fetch_general_data['ap']) > '3' && strlen($fetch_general_data['ap']) < '7') {
			$ap = '' .number_format($fetch_general_data['ap']/1000). ' K';
		}
		elseif(strlen($fetch_general_data['ap']) >= '7' && strlen($fetch_general_data['ap']) < '10') {
			$ap = '' .number_format($fetch_general_data['ap']/1000000). ' M';
		}
		elseif(strlen($fetch_general_data['ap']) >= '10' && strlen($fetch_general_data['ap']) < '14') {
			$ap = '' .number_format($fetch_general_data['ap']/1000000000). ' B';
		}
		elseif(strlen($fetch_general_data['ap']) >= '14' && strlen($fetch_general_data['ap']) < '18') {
			$ap = '' .number_format($fetch_general_data['ap']/1000000000000). ' T';
		}
		
		// UPDATED TIMER CONVERTER
		if(time('now')-$guild_table['updated'] <= '86400') {
			$last_update = '<span style="color: yellowgreen;">' .round(((time('now')-$guild_table['updated'])/3600), 2). ' hrs ago</span>';
		}
		elseif(time('now')-$guild_table['updated'] > '86400') {
			$last_update = '<span style="color: coral;">' .round(((time('now')-$guild_table['updated'])/3600), 2). ' hrs ago</span>';
		}
		
		// OLD VALUE CHECK
		$check = mysqli_fetch_array(mysqli_query($stream, "SELECT `ap` FROM `" .$_SESSION['table']. "_" .$id['id']. "_past` ORDER BY `ap` DESC LIMIT 1"));
	
		if($check['ap'] != '') {
			$incr = round((($fetch_general_data['ap']/$check['ap'])-1)*100, 2);
			if($incr != '0') {
				$incr = '<sup style="color: silver; font-size: 12px;" title="' .number_format($check['ap']). '">+' .$incr. '%</sup>';
			}
			else {
				$incr = '';
			}
		}	
		
		echo '
		<tr class="' .$id['id']. '">
			<td class="name' .$id['id']. '" style="background-color: ' .$class_color['color']. ';"><a href="?inspect=' .$id['id']. '" title="Inspect ' .$guild_table['name']. '">' .$guild_table['name']. '</a></td>
			<td>' .$last_update. ' (' .$last_logout. ')</td>
			<td><img src="img/update.png" alt="404" title="Update ' .$guild_table['name']. '" style="width: 21px;" onclick="update(this.id);" id="' .$id['id']. '" class="still' .$id['id']. '" /></td>
			<td><a href="?change_role=' .$id['id']. '">' .$role1. ' ' .$role2. '</a></td>
			<td>' .$spec['spec']. '</td>
			<td><a href="http://eu.battle.net/wow/en/tool/talent-calculator#' .$guild_table['talents']. '" title="WoW Talent Calculator">Calc</a></td>
			<td><a href="?edit_legendaries=' .$id['id']. '">' .$legendaries['amount']. '</a></td>
			<td>' .$fetch_general_data['ilvl_on']. ' (' .$fetch_general_data['ilvl_off']. ')</td>			
			<td><span title="' .number_format($fetch_general_data['ap']). '">' .$ap. '</span> ' .$incr. '</td>
			<td>' .$artifact_level. ' (' .$artifact_knowledge. ')</td>
			<td>' .$fetch_dungeon_data['mythic']. ' (' .$m_achievement. ')</td>
			<td>' .$fetch_general_data['wq']. '</td>
			<td>' .$guild_table['eq']. '</td>
			<td>' .$en_hc. '  ' .$en_m. '</td>
			<td>' .$tov_hc. ' ' .$tov_m. '</td>
			<td >' .$nh_hc. ' ' .$nh_m. '</td>
			<td>' .$tos_hc. ' ' .$tos_m. '</td>
			<td><a href="?inspect=' .$id['id']. '"><img src="img/inspect.png" alt="404" title="Inspect ' .$guild_table['name']. '" style="width: 21px;" /></a></td>
			<td><img src="img/unbench.png" alt="404" title="Unbench ' .$guild_table['name']. '" style="width: 21px;" id="' .$id['id']. '" onclick="unbench(this.id);" /></td>
			<td><img src="img/kick.png" alt="404" title="Kick ' .$guild_table['name']. '" style="width: 21px;" id="' .$id['id']. '" onclick="kick(this.id);" /></a></td>
		</tr>';
	}
		
	echo '</tbody>
	</table>';	
}
elseif($benchcheck['id'] == '') {
	echo '<br /><span style="text-align: center; color: yellowgreen;">Currently empty.</span><p></p>';
}
echo '
</div>
</div>';

?>