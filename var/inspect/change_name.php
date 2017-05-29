<?php

echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-left: 5%;">';

// GUEST = VIEW ONLY
if($_SESSION['guest'] != 'guest') {
	
	if(isset($_POST['rename'])) {
	
		if(strlen($_POST['rename']) <= '16') {
			$update = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `name` = '" .$_POST['rename']. "' WHERE `id` = '" .$_GET['change_name']. "'");
			if($update) {
				echo '<span style="color: yellowgreen; text-align: center;">Character has been successfully renamed to <u>' .$_POST['rename']. '</u>.<br />
			<a href="?inspect=' .$_GET['change_name']. '">Profile</a></a> or <a href="?change_name=' .$_GET['change_name']. '">change name again</span>';
			}
			elseif(!$update) {
				echo '<span style="color: coral; text-align: center;">Could not rename character at this point, sorry. <a href="?change_name=' .$_GET['change_name']. '">Please try again!</a></span>';
			}
		}
		elseif(strlen($_POST['rename']) > '16') {
			echo '<span style="color: coral; text-align: center;">The name you entered (' .$_POST['rename']. ') is too long. Max length is 16 characters.<br />
		<a href="?change_name=' .$_GET['change_name']. '">Go back.</a></span>';
		}
	}
	
	if(!isset($_POST['rename'])) {
		
		// FETCH CURRENT ROLES
		$roles = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['change_name']. "'"));
	
			
		echo '<span style="text-align: center; color: orange; font-size: 18px;">Enter new name for <u>' .$roles['name']. '</u>
		<br />
		For future updates the character name must be correct, make sure that you included all special characters.</span>
		<br />
		<br />
		<form action="" method="POST">
		<input type="text" required name="rename" placeholder="enter new name" maxlength="16" />
		<br />
		<br />
		<button type="submit">Change name</button>
		</form>';
	}
}
elseif($_SESSION['guest'] == 'guest') {
	echo '<span style="color: coral;">Sorry, guests may not edit this setting.<br />
	<a href="?inspect=' .$_GET['change_name']. '">Back</a></span>';
}
	
echo '</div>';

?>