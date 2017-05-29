<?php

if(isset($_GET['region'])) {
	
	$regions = array('EU', 'US', 'TW', 'KR');
	if(in_array($_GET['region'], $regions)) {
		include('stream.php');
				
		$realms = mysqli_query($stream, "SELECT `short`, `name` FROM `ovw_realms` WHERE `region` = '" .$_GET['region']. "' ORDER BY `name` ASC");
		
		echo '<select required name="realm">
			<option selected disabled>select server</option>';
		
		while($realm = mysqli_fetch_array($realms)) {
			echo '<option value="' .$realm['short']. '">' .$realm['name']. '</option>';
		}
		
		echo '</select>';
	}
}

?>