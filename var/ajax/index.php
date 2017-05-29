<?php

session_start();

$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

echo '<div style="width: 90%; height: auto; margin-left: 5%; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';

// GUEST = VIEW ONLY
if($_SESSION['guest'] != 'guest') {
	echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">benching...</span>';
		
	$bench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '1' WHERE `id` = '" .$_GET['bench']. "'");
	
	if($bench) {
		echo '<meta http-equiv="refresh" content="0;url=http://ags.gerritalex.de/" />';
	}
	elseif(!$bench) {
		$error = '<span style="color: coral; text-align: center;">Could not <u>bench</u> player (ID ' .$_GET['bench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}
}

elseif($_SESSION['guest'] == 'guest') {
	echo '<span style="color: coral;">Sorry, guests may not edit this setting.<br />
	<a href="?inspect=' .$_GET['bench']. '">Back</a></span>';
}

echo '</div>';

?>