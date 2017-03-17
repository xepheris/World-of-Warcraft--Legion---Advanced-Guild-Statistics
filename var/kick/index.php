<?php

echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">
<span style="color: yellowgreen; text-align: center; font-size: 20px;">removing...</span>';
				
$kick = mysqli_query($stream, "DROP TABLE `" .$_SESSION['table']. "_" .$_GET['kick']. "_dungeons`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_equip`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_general`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_legendaries`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_1`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_2`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_3`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_raid_4`, `" .$_SESSION['table']. "_" .$_GET['kick']. "_weapons`;");
		
$update = mysqli_query($stream, "DELETE FROM `" .$table_name. "` WHERE `id` = '" .$_GET['kick']. "'");
$realm_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `name` = '" .$_SESSION['realm']. "'"));
		
$update = mysqli_query($stream, "UPDATE `ovw_guilds` SET `tracked_chars` = `tracked_chars` -1 WHERE `guild_name` = '" .$_SESSION['guild']. "' AND `region` = '" .$_SESSION['region']. "' AND `realm` = '" .$realm_id['id']. "'");
		
$_SESSION['tracked'] = $_SESSION['tracked']-1;
		
echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/" />';
	
echo '</div>';

?>