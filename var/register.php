<?php

$realm_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `short` = '" .$_POST['realm']. "' AND `region` = '" .$_POST['region']. "'"));
		
$check = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_guilds` WHERE `guild_name` = '" .$_POST['gname']. "' AND `region` = '" .$_POST['region']. "' AND `realm` = '" .$check['id']. "'"));
	
if($check != '' && !empty($check)) {
	$duplicate_guild = '<br /><span style="color: coral; text-align: center;">The guild you tried to insert already exists! Please ask your guild about the password.<br />If your guild has been claimed by someone unknown, write me a <u onclick="' .$contact. '">mail</u> with proof that you are a spokesperson and I will reset the password.<br /></span>';
}
else {
	$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));
		
	$g = str_replace(' ', '%20', $_POST['gname']);
		
	$url = 'https://' .$_POST['region']. '.api.battle.net/wow/guild/' .$_POST['realm']. '/' .$g. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';
								
	// ENABLE SSL
	$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);  
		
	$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
		
	if($data != '') {
			
		$data = json_decode($data, true);
						
		if($data['name'] == $_POST['gname']) {
			
			$insert_new_guild = mysqli_query($stream, "INSERT INTO `ovw_guilds` (`guild_name`, `region`, `realm`, `shortlink`, `registered`, `password`, `last_login`, `tracked_chars`) VALUES ('" .$_POST['gname']. "', '" .$_POST['region']. "', '" .$realm_id['id']. "', '" .md5($_POST['gname'].$_POST['region'].$_POST['realm']). "', '" .time('now'). "', '" .md5($_POST['pw']). "', '0', '0')");
				
			if($insert_new_guild) {
				$new_guild_insert_success = '<br /><span style="color: yellowgreen; text-align: center;">Your guild has been successfully added - login via the form on the left!</span><br />';
			}
			elseif(!$insert_new_guild) {
				$new_guild_insert_fail = '<br /><span style="color: coral; text-align: center;">Could not add guild! Possible reasons:<br />- armory currently unavailable (<a href="http://' .$_POST['region']. '.battle.net/wow/en/guild/' .$_POST['realm']. '/' .$_POST['gname']. '/">click</a> to check)<br />- guild recently created<br /><br />Please try again at a later point.</span><br />';
			}
		}
		else {
			$new_guild_name_fail = '<br /><span style="color: coral; text-align: center;">According to the <a href="http://' .$_POST['region']. '.battle.net/wow/en/guild/' .$_POST['realm']. '/' .$_POST['gname']. '/">Armory</a>, the guild you wanted to insert does not exist.<br />Please keep in mind that the guild name is <u>case-sensitive</u>.<br />Alternatively, the Armory could be unavailable at this point.</span><br />';
		}
	}
	else {
		$crash = '<br /><span style="color: coral; text-align: center;">Something, somewhere went wrong. Please try again. If this keeps happening, please send me a <u onclick="' .$contact. '">mail</u>!</span><br />';
	}
}

?>