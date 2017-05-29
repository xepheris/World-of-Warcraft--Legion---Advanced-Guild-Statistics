<?php

echo '<script defer src="http://wow.zamimg.com/widgets/power.js"></script>
<script>
	var wowhead_tooltips = {
	iconizelinks: true,
	renamelinks: true,
		"hide": {
		"dropchance": true,
		"droppedby": true,
		"sellprice": true,
		"maxstack": true,
	}
}
</script>
<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">';

	
if(isset($_GET['rmv'])) {
		
	$remove_legendary = mysqli_query($stream, "DELETE FROM `" .$_SESSION['table']. "_" .$_GET['edit_legendaries']. "_legendaries` WHERE `item_id` = '" .$_GET['rmv']. "'");
		
	if($remove_legendary) {
		echo '<span style="color: yellowgreen; font-size: 18px; text-align: center;"><a href="http://wowhead.com/?item=' .$_GET['rmv']. '">' .$_GET['rmv']. '</a> has been sucecssfully removed.<br />
		<a href="?inspect=' .$_GET['edit_legendaries']. '">Profile</a> or <a href="?edit_legendaries=' .$_GET['edit_legendaries']. '">continue editing legendaries</a>.</span>';
	}
	else {
		echo '<span style="color: coral; text-align: center;">Could not remove legendary at this point - pleasea try again!<br />
		<a href="?edit_legendaries=' .$_GET['edit_legendaries']. '">Go back.</a></span>';
	}
}
	
if(isset($_POST['add_legendaries'])) {
			
	foreach($_POST['add_legendaries'] as $id) {
		$insert = mysqli_query($stream, "INSERT INTO `" .$_SESSION['table']. "_" .$_GET['edit_legendaries']. "_legendaries` (`item_id`) VALUES ('" .$id. "')");
	}
	
	echo '<span style="color: orange; text-align: center; font-size: 18px;">Legendaries have been successfully added! You will be redirected to your profile in 3 seconds.</span>
	<meta http-equiv="refresh" content="3;url=http://ags.gerritalex.de/?inspect=' .$_GET['edit_legendaries']. '" />';
}
	
if(!isset($_POST['add_legendaries']) && !isset($_GET['rmv'])) {
		
	echo '<style>
		tr:nth-child(even) {
			background-color: #90805f !important;
		}
	</style>
	
	<div style="width: 50%; float: left;">';
	
	// FETCH CLASS TO PRESELECT POTENTIALLY DROPPED LEGENDARIES	
	$class = mysqli_fetch_array(mysqli_query($stream, "SELECT `class` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['edit_legendaries']. "'"));
	
	// FETCH CURRENTLY KNOWN LEGENDARIES
	$current_legendaries = mysqli_query($stream, "SELECT `item_id` FROM `" .$_SESSION['table']. "_" .$_GET['edit_legendaries']. "_legendaries`");
	$current = array();
	
	while($legendary = mysqli_fetch_array($current_legendaries)) {
		array_push($current, $legendary['item_id']);
	}
			
	echo '<span style="text-align: center; color: orange; font-size: 18px;">Add other owned legendaries</span>
	<br />
	<br />
	<form action="" method="POST">
	<select multiple name="add_legendaries[]" style="width: auto; height: 30vh;">';	
	
	$legendaries = mysqli_query($stream, "SELECT * FROM `ovw_legendaries` WHERE `class` = '0' OR `class` LIKE '%" .$class['class']. "%' ORDER BY `name` ASC");
	$potential = array();
	
	while($potential_leg = mysqli_fetch_array($legendaries)) {
		array_push($potential, $potential_leg);
	}
	
	foreach($potential as $new_legendary) {			
		if(!in_array($new_legendary['item_id'], $current)) {
			echo '<option value="' .$new_legendary['item_id']. '">' .$new_legendary['name']. '</a></option>';
		}
	}
	echo '</select>
	<br />
	<button type="submit">Add</button>
	</form>
	</div>';
		
	echo '<div style="width: 50%; float: left;">		
	<span style="text-align: center; color: orange; font-size: 18px;">Remove currently owned legendaries</span>
	<br />
	<br />';
		
	$current_legendaries = mysqli_query($stream, "SELECT `item_id` FROM `" .$_SESSION['table']. "_" .$_GET['edit_legendaries']. "_legendaries`");
		
	echo '<table style="margin: 0 auto; width: 50%;">
	<thead>
	<tr><th>Legendary</th><th>Remove</th><tr>
	</thead>
	<tbody>';
	
	while($legendary = mysqli_fetch_array($current_legendaries)) {
		echo '<tr><td><a href="http://wowhead.com/?item=' .$legendary['item_id']. '">' .$legendary['item_id']. '</a></td><td><a href="?edit_legendaries=' .$_GET['edit_legendaries']. '&rmv=' .$legendary['item_id']. '"><img src="img/kick.png" width="21px" alt="remove legendary" title="remove legendary" /></a></td></tr>';
	}
		
	echo '</tbody>
	</table>
	</div>';
}
	
echo '</div>';

?>