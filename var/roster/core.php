<?php

if($_SESSION['tracked'] >= '20') {
	$overflow = '';
}
else {
	$overflow = 'overflow: hidden;';
}
		
echo '<div style="width: 100%; height: 60%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
' .$error. '
<span style="color: orange; text-align: center; font-size: 20px;">Current Roster</span>
<div style="overflow-y: scroll; max-height: 742px; ' .$overflow. '">
<table style="margin: 0 auto; margin-top: 15px;">
<thead>
<tr>
<th></th>
<th>Roles</th>
<th>Spec</th>
<th>Talents</th>
<th>Itemlevel</th>
<th>Legendaries</th>
<th><span title="Artifact Power">AP</span></th>
<th><span title="Artifact Knowledge">AK</span></th>
<th><span title="Artifact Level">AL</span></th>
<th>Mythics<br />(M+ Achv)</th>
<th>World Quests</th>
<th><span title="Emerald Nightmare">EN</span><br />
	<table>
		<tbody>
			<tr>
				<td>HC</td>
				<td>M</td>
			</tr>
		</tbody>
	</table>
</th>
<th><span title="Trial of Valor">ToV</span><br />
	<table>
		<tbody>
			<tr>
				<td>HC</td>
				<td>M</td>
			</tr>
		</tbody>
	</table>
</th>
<th><span title="The Nighthold">NH</span><br />
	<table>
		<tbody>
			<tr>
				<td>HC</td>
				<td>M</td>
			</tr>
		</tbody>
	</table>
</th>
<th><span title="Tomb of Sargeras">ToS</span><br />
	<table>
		<tbody>
			<tr>
				<td>HC</td>
				<td>M</td>
			</tr>
		</tbody>
	</table>
</th>
<th>Bench</th>
<th>Inspect</th>
</tr>
</thead>
<tbody>';
		
$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '0' ORDER BY `name` ASC");
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
			
	if($fetch_general_data['alvl'] <= '27') {
		$alvl_color = 'red';
	}
	elseif($fetch_general_data['alvl'] > '27' && $fetch_general_data['alvl'] < '54') {
		$alvl_color = 'orange';
	}
	elseif($fetch_general_data['alvl'] == '54') {
		$alvl_color = 'greenyellow';
	}			
	
	$artifact_level = '<span style="color: ' .$alvl_color. ';">' .$fetch_general_data['alvl']. '</span>';
			
	if($fetch_general_data['ak'] <= '12') {
		$ak_color = 'red';
	}
	elseif($fetch_general_data['ak'] > '12' && $fetch_general_data['ak'] < '25') {
		$ak_color = 'orange';
	}
	elseif($fetch_general_data['ak'] == '25') {
		$ak_color = 'greenyellow';
	}
	
	$artifact_knowledge = '<span style="color: ' .$ak_color. ';">' .$fetch_general_data['ak']. '</span>';
			
	if($fetch_general_data['m_achv'] <= '2') {
		$mplus_color = 'red';
	}
	elseif($fetch_general_data['m_achv'] > '2' && $fetch_general_data['m_achv'] <= '5') {
		$mplus_color = 'orange';
	}
	elseif($fetch_general_data['m_achv'] > '5' && $fetch_general_data['m_achv'] <= '10') {
		$mplus_color = 'yellow';
	}
	elseif($fetch_general_data['m_achv'] == '15') {
		$mplus_color = 'greenyellow';
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
		$en_hc_color = 'greenyellow';
	}
	elseif($en_heroic_progress['en_hc'] > '0' && $en_heroic_progress['en_hc'] < '7') {
		$en_hc_color = 'orange';
	}
	elseif($en_heroic_progress['en_hc'] == '0') {
		$en_hc_color = 'red';
	}
	$en_hc = '<span style="color: ' .$en_hc_color. ';">' .$en_heroic_progress['en_hc']. '</span>';
	
	if($en_mythic_progress['en_m'] == '7') {
		$en_m_color = 'greenyellow';
	}
	elseif($en_mythic_progress['en_m'] > '0' && $en_mythic_progress['en_m'] < '7') {
		$en_m_color = 'orange';
	}
	elseif($en_mythic_progress['en_m'] == '0') {
		$en_m_color = 'red';
	}
	$en_m = '<span style="color: ' .$en_m_color. ';">' .$en_mythic_progress['en_m']. '</span>';
	
	// COLORIZATION OF INDIVIDUAL TRIAL OF VALOR PROGRESS
	if($tov_heroic_progress['tov_hc'] == '3') {
		$tov_hc_color = 'greenyellow';
	}
	elseif($tov_heroic_progress['tov_hc'] > '0' && $tov_heroic_progress['tov_hc'] < '3') {
		$tov_hc_color = 'orange';
	}
	elseif($tov_heroic_progress['tov_hc'] == '0') {
		$tov_hc_color = 'red';
	}
	$tov_hc = '<span style="color: ' .$tov_hc_color. ';">' .$tov_heroic_progress['tov_hc']. '</span>';
			
	if($tov_mythic_progress['tov_m'] == '3') {
		$tov_m_color = 'greenyellow';
	}
	elseif($tov_mythic_progress['tov_m'] > '0' && $tov_mythic_progress['tov_m'] < '3') {
		$tov_m_color = 'orange';
	}
	elseif($tov_mythic_progress['tov_m'] == '0') {
		$tov_m_color = 'red';
	}
	$tov_m = '<span style="color: ' .$tov_m_color. ';">' .$tov_mythic_progress['tov_m']. '</span>';
			
	// COLORIZATION OF INDIVIDUAL NIGHTHOLD PROGRESS
	if($nh_heroic_progress['nh_hc'] == '10') {
		$nh_hc_color = 'greenyellow';
	}
	elseif($nh_heroic_progress['nh_hc'] > '0' && $nh_heroic_progress['nh_hc'] < '10') {
		$nh_hc_color = 'orange';
	}
	elseif($nh_heroic_progress['nh_hc'] == '0') {
		$nh_hc_color = 'red';
	}
	$nh_hc = '<span style="color: ' .$nh_hc_color. ';">' .$nh_heroic_progress['nh_hc']. '</span>';
	
	if($nh_mythic_progress['nh_m'] == '10') {
		$nh_m_color = 'greenyellow';
	}
	elseif($nh_mythic_progress['nh_m'] > '0' && $nh_mythic_progress['nh_m'] < '10') {
		$nh_m_color = 'orange';
	}
	elseif($nh_mythic_progress['nh_m'] == '0') {
		$nh_m_color = 'red';
	}
	$nh_m = '<span style="color: ' .$nh_m_color. ';">' .$nh_mythic_progress['nh_m']. '</span>';
	
	// COLORIZATION OF INDIVIDUAL TOMB OF SARGERAS PROGRESS
	if($tos_heroic_progress['tos_hc'] == '9') {
		$tos_hc_color = 'greenyellow';
	}
	elseif($tos_heroic_progress['tos_hc'] > '0' && $tos_heroic_progress['tos_hc'] < '9') {
		$tos_hc_color = 'orange';
	}
	elseif($tos_heroic_progress['tos_hc'] == '0') {
		$tos_hc_color = 'red';
	}
	$tos_hc = '<span style="color: ' .$tos_hc_color. ';">' .$tos_heroic_progress['tos_hc']. '</span>';
	
	if($tos_mythic_progress['tos_m'] == '9') {
		$tos_m_color = 'greenyellow';
	}
	elseif($tos_mythic_progress['tos_m'] > '0' && $tos_mythic_progress['tos_m'] < '9') {
		$tos_m_color = 'orange';
	}
	elseif($tos_mythic_progress['tos_m'] == '0') {
		$tos_m_color = 'red';
	}
	$tos_m = '<span style="color: ' .$tos_m_color. ';">' .$tos_mythic_progress['tos_m']. '</span>';
		
	echo '<tr>
	<td style="background-color: ' .$class_color['color']. ';"><a href="?inspect=' .$id['id']. '">' .$guild_table['name']. '</a></td>
	<td>' .$role1. ' ' .$role2. '</td>
	<td>' .$spec['spec']. '</td>
	<td>
		<a href="http://eu.battle.net/wow/de/tool/talent-calculator#' .$guild_table['talents']. '">Calc</a>
	</td>
	<td>' .$fetch_general_data['ilvl_on']. ' (' .$fetch_general_data['ilvl_off']. ')</td>
	<td>' .$legendaries['amount']. '</td>
	<td>
		<span title="' .number_format($fetch_general_data['ap']). '">' .number_format($fetch_general_data['ap']/1000000). 'M</span>
	</td>
	<td>' .$artifact_knowledge. '</td>
	<td>' .$artifact_level. '</td>
	<td>' .$fetch_dungeon_data['mythic']. ' (' .$m_achievement. ')</td>
	<td>' .$fetch_general_data['wq']. '</td>
	<td>
		<table>
			<tbody>
				<tr>
					<td>' .$en_hc. '</td>
					<td>' .$en_m. '</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td>
		<table>
			<tbody>
				<tr>
					<td>' .$tov_hc. '</td>
					<td>' .$tov_m. '</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td>
		<table>
			<tbody>
				<tr>
					<td>' .$nh_hc. '</td>
					<td>' .$nh_m. '</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td>
		<table>
			<tbody>
				<tr>
					<td>' .$tos_hc. '</td>
					<td>' .$tos_m. '</td>
				</tr>
			</tbody>
		</table>
	</td>
	<td>
		<a href="?bench=' .$id['id']. '"><img src="img/bench.png" alt="404" width="21px" title="Bench" /></a>
	</td>
	<td>
		<a href="?inspect=' .$id['id']. '"><img src="img/inspect.png" alt="404" width="21px" title="Inspect" /></a>
	</td>
	</tr>';
}
		
echo '</tbody>
</table>
</div>
</div>';
		
// LEFT COLUMN BOTTOM
include('bench.php');
		
// RIGHT COLUMN BOTTOM

include('toplist.php');

// SIDEBAR
include('sidebar.php');

?>