<?php

echo '<div style="width: 45%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-left: 10%;">
<span style="color: orange; text-align: center; font-size: 18px;">Toplist</span>
<div style="max-height: 105px; overflow-y: scroll;">';
		
if($_SESSION['tracked'] > '2') {
		
	echo '<table style="margin: 0 auto; margin-top: 15px;">
	<thead>
	<tr><th>Most M+</th><th>Most AP</th><th>some fancy stat</th><th>another fancy stat</th></tr>
	</thead>
	<tbody>';
			
	$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '1' ORDER BY `name` ASC");
	while($id = mysqli_fetch_array($fetch_ids)) {			
			
		$guild_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$id['id']. "'"));
		$fetch_general_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$id['id']. "_general`"));
		$fetch_dungeon_data = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`mythic`) AS `mythic` FROM `" .$_SESSION['table']. "_" .$id['id']. "_dungeons`"));
		$class_color = mysqli_fetch_array(mysqli_query($stream, "SELECT `class`, `color` FROM `ovw_classes` WHERE `id` = '" .$guild_table['class']. "'"));
		$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `ovw_weapons` WHERE `id` = '" .$guild_table['spec']. "'"));
			
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
			
		echo '<tr>
		<td style="background-color: ' .$class_color['color']. ';">' .$guild_table['name']. '</td>
		<td>' .$role1. ' ' .$role2. '</td>
		<td>' .$fetch_general_data['ilvl_on']. ' (' .$fetch_general_data['ilvl_off']. ')</td>
		<td><span title="' .number_format($fetch_general_data['ap']). '">' .number_format($fetch_general_data['ap']/1000000). 'M</span></td>
		<td>' .$artifact_knowledge. '</td>
		<td>' .$artifact_level. '</td>
		<td>' .$fetch_dungeon_data['mythic']. '</td>
		<td>' .$fetch_general_data['wq']. '</td>
		<td><a href="?unbench=' .$id['id']. '"><img src="img/unbench.png" alt="404" width="21px" title="Unbench" /></a></td>
		<td><a href="?inspect=' .$id['id']. '"><img src="img/inspect.png" alt="404" width="21px" title="Inspect" /></a></td>
		<td><a href="?kick=' .$id['id']. '"><img src="img/kick.png" alt="404" width=21px" title="Kick" /></a></td>
		</tr>';
	}
			
	echo '</tbody>
	</table>';
			
}
elseif($_SESSION['tracked'] <= '2') {
	echo '<span style="text-align: center; color: yellowgreen;">Amount of tracked characters currently too low to compare.</span>';
}
	
echo '</div>
</div>
</div>';

?>