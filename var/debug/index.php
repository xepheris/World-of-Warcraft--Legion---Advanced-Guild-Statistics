<?php

echo '<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">';

if($_SESSION['guest'] != 'guest') {
	
	if($_GET['debug'] == '1') {	
		
		$fetch = mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `realm` = '0' AND `class` = '0' AND `logout` = '0' AND `updated` = '0'");
		
		$i = '0';
		while($erase = mysqli_fetch_array($fetch)) {
			
			$rmv = mysqli_query($stream, "DELETE FROM `" .$table_name. "` WHERE `id` = '" .$erase['id']. "'");
			$i++;
		}
		
		echo '<p style="color: yellowgreen;">' .$i. ' entries cleaned!</p>
		<a href="?import">Import</a> | <a href="http://ags.gerritalex.de">Roster</a>';
		
		
		
	}
	
	elseif ( $_GET[ 'debug' ] == '' ) {
		
		$count_faulty = mysqli_num_rows(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `realm` = '0' AND `class` = '0' AND `logout` = '0' AND `updated` = '0'"));

		echo '<p style="color: coral;">Debug menu</p>';

		echo '<span style="color: orange;">Erasing all unwanted data from your guild: ' .$count_faulty. 'x
		<br />
		<br />
		Unwanted means:
		<br />
		– characters that cannot be imported due to corrupted data server-side<br />
		– unfinished imports due to reset connection, closed tabs, crashed browsers etc.</span>
		<br />
		<br />
		<a href="?debug=1">Erase</a>';
		
	}

} elseif($_SESSION['guest'] == 'guest') {
	echo '<p style="color: coral;">Sorry, due to security reasons, guests may not edit this – please login.</p>';
}

echo '</div>';

?>