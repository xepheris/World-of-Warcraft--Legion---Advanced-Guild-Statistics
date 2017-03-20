<?php

$check = mysqli_fetch_array(mysqli_query($stream, "SELECT `password` FROM `ovw_guilds` WHERE `id` = '" .$_POST['guild']. "'"));
	
if(md5($_POST['pw']) == $check['password']) {
		
	$fetch = mysqli_fetch_array(mysqli_query($stream, "SELECT `guild_name`, `realm`, `region`, `shortlink`, `tracked_chars`, `last_login` FROM `ovw_guilds` WHERE `id` = '" .$_POST['guild']. "'"));
	$_SESSION['guild'] = $fetch['guild_name'];
	$_SESSION['region'] = $fetch['region'];
	$_SESSION['share'] = $fetch['shortlink'];
	$_SESSION['tracked'] = $fetch['tracked_chars'];
	$_SESSION['llogin'] = $fetch['last_login'];
		
	$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$fetch['realm']. "'"));
		
	$_SESSION['realm'] = $realm_name['name'];
		
	$_SESSION['table'] = $_POST['guild'];
		
	// UPDATE LOGIN TIME
	$refresh_login = mysqli_query($stream, "UPDATE `ovw_guilds` SET `last_login` = '" .time('now'). "' WHERE `id` = '" .$_SESSION['table']. "'");
}
elseif(md5($_POST['pw']) != $check['password']) {
	$login_wrong_pw = '<br /><span style="color: coral; text-align: center;">Sorry, password wrong!</span><br />';
}

?>