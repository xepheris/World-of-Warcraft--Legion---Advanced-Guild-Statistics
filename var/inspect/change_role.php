<?php

echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">';

	
if(isset($_POST['role1']) || isset($_POST['role2'])) {
	
	$roles = mysqli_fetch_array(mysqli_query($stream, "SELECT `role1`, `role2`, `name` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['change_role']. "'"));
	
	if(isset($_POST['role1']) && !isset($_POST['role2'])) {
		
		if($_POST['role1'] == $roles['role1']) {
			echo '<span style="color: orange; text-align: center;">Nothing was changed, as you assigned the same primary role as before.<br />
			<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
		}
		else {
			
			$upd = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role1` = '" .$_POST['role1']. "'  WHERE `id` = '" .$_GET['change_role']. "'");
			
			if($upd) {
				echo '<span style="color: yellowgreen; text-align: center;">New primary role has been successfully assigned.<br />
				Since you changed the primary = log role, you need to update the character data as well to see the updated parses, if they exist.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
			elseif(!$upd) {
				echo '<span style="color: coral; text-align: center;">Sorry, something went wrong - could not change roles. Please try again.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
		}
	}
	
	if(!isset($_POST['role1']) && isset($_POST['role2'])) {
		
		if($_POST['role2'] == $roles['role2']) {
			echo '<span style="color: orange; text-align: center;">Nothing was changed, as you assigned the same secondary role as before.<br />
			<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
		}
		else {
			$upd = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role2` = '" .$_POST['role2']. "'  WHERE `id` = '" .$_GET['change_role']. "'");
			
			if($upd) {
				echo '<span style="color: yellowgreen; text-align: center;">New secondary role has been successfully assigned.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
			elseif(!$upd) {
				echo '<span style="color: coral; text-align: center;">Sorry, something went wrong - could not change roles. Please try again.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
		}
	}
	
	if(isset($_POST['role1']) && isset($_POST['role2'])) {
		
		if(($_POST['role1'] == $roles['role1']) && ($_POST['role2'] == $roles['role2'])) {
			echo '<span style="color: orange; text-align: center;">Nothing was changed, as you assigned the same roles as before.<br />
			<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
		}
		else {
			$upd = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `role1` = '" .$_POST['role1']. "', `role2` = '" .$_POST['role2']. "'  WHERE `id` = '" .$_GET['change_role']. "'");
			
			if($upd) {
				echo '<span style="color: yellowgreen; text-align: center;">New roles have been successfully assigned.<br />
				Since you changed the primary = log role, you need to update the character data as well to see the updated parses, if they exist.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
			elseif(!$upd) {
				echo '<span style="color: coral; text-align: center;">Sorry, something went wrong - could not change roles. Please try again.<br />
				<a href="?inspect=' .$_GET['change_role']. '">Profile</a> or <a href="?change_role=' .$_GET['change_role']. '">continue editing roles</a>.</span>';
			}
		}
	}	
	
	
}
	
if(!isset($_POST['role1']) && !isset($_POST['role2'])) {
		
	// FETCH CURRENT ROLES
	$roles = mysqli_fetch_array(mysqli_query($stream, "SELECT `role1`, `role2`, `name` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['change_role']. "'"));
	
	switch ($roles['role1']) {
		case '0':
			$role1 = 'Current primary role (log role): Melee DPS <img src="img/dps.png" alt="404" title="Primary: Melee DPS" style="width: 16px;" />';
			break;
		case '1':
			$role1 = 'Current primary role (log role): Ranged DPS <img src="img/rdps.png" alt="404" title="Primary: Ranged DPS" style="width: 16px;" />';
			break;
		case '2':
			$role1 = 'Current primary role (log role): Tank <img src="img/tank.png" alt="404" title="Primary: Tank" style="width: 16px;" />';
			break;
		case '3':
			$role1 = 'Current primary role (log role): Heal <img src="img/heal.png" alt="404" title="Primary: Heal" style="width: 16px;" />';
			break;					
	}
			
	switch ($roles['role2']) {
		case '0':
			$role2 = '<br />Current secondary role: Melee DPS <img src="img/dps.png" alt="404" title="Secondary: Melee DPS" style="width: 16px;" />';
			break;
		case '1':
			$role2 = '<br />Current secondary role: Ranged DPS <img src="img/rdps.png" alt="404" title="Secondary: Ranged DPS" style="width: 16px;" />';
			break;
		case '2':
			$role2 = '<br />Current secondary role: Tank <img src="img/tank.png" alt="404" title="Secondary: Tank" style="width: 16px;" />';
			break;
		case '3':
			$role2 = '<br />Current secondary role: Heal <img src="img/heal.png" alt="404" title="Secondary: Heal" style="width: 16px;" />';
			break;
		case '4':
			$role2 = '';
			break;
	}
		
	echo '<span style="text-align: center; color: orange; font-size: 18px;">Select new roles for <u>' .$roles['name']. '</u>
	<br />
	<br />
	' .$role1. '' .$role2. '
	</span>
	<br />
	<br />
	<form action="" method="POST">
	<select name="role1">
		<option selected disabled>select primary role</option>
		<option value="0">Melee DPS</option>
		<option value="1">Ranged DPS</option>
		<option value="2">Tank</option>
		<option value="3">Heal</option>
	</select>
	<br />
	<select name="role2"">
		<option selected disabled>select secondary role</option>
		<option value="4">none</option>
		<option value="0">Melee DPS</option>
		<option value="1">Ranged DPS</option>
		<option value="2">Tank</option>
		<option value="3">Heal</option>
	</select>
	<br />
	<br />
	<button type="submit">Change role</button>
	</form>';
}
	
echo '</div>';

?>