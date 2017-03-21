<?php

echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">';

// GUEST = VIEW ONLY
if($_SESSION['guest'] != 'guest') {
	echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">unbenching...</span>';
				
	$unbench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '0' WHERE `id` = '" .$_GET['unbench']. "'");
		
	if($unbench) {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	}
	elseif(!$unbench) {
		$error = '<span style="color: coral; text-align: center;">Could not unbench player (ID ' .$_GET['unbench']. ') at this moment. Please try again.<br />If the problem persists, please contact me via the contact form.</span>';
	}
}
elseif($_SESSION['guest'] == 'guest') {
	echo '<span style="color: coral;">Sorry, guests may not edit this setting.<br />
	<a href="?inspect=' .$_GET['unbench']. '">Back</a></span>';
}
	
echo '</div>';

?>