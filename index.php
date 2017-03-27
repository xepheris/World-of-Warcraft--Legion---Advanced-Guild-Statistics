<?php

session_start();

$contact = "return confirm('If you wish to contact me, either use the contact form when logged in or write me a mail to: xepheris.dh.tank [AT] gmail [DOT] com')";

include('var/stream.php');

// REGISTER PROCESSING
if(isset($_POST['gname']) && isset($_POST['region']) && isset($_POST['realm']) && isset($_POST['pw'])) {
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
				
				$fetch = mysqli_fetch_array(mysqli_query($stream, "SELECT `id`, `guild_name`, `realm`, `region`, `shortlink`, `tracked_chars` FROM `ovw_guilds` WHERE `guild_name` = '" .$_POST['gname']. "'"));
				$_SESSION['guild'] = $fetch['guild_name'];
				$_SESSION['region'] = $fetch['region'];
				$_SESSION['tracked'] = $fetch['tracked_chars'];
				$_SESSION['shortlink'] = $fetch['shortlink'];
				
				$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$fetch['realm']. "'"));
		
				$_SESSION['realm'] = $realm_name['name'];
		
				$_SESSION['table'] = $fetch['id'];
		
				// UPDATE LOGIN TIME
				$refresh_login = mysqli_query($stream, "UPDATE `ovw_guilds` SET `last_login` = '" .time('now'). "' WHERE `id` = '" .$_SESSION['table']. "'");
				
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
			$crash = '<br /><span style="color: coral; text-align: center;">According to the Armory, your guild <u>' .$_POST['gname']. ' (' .$_POST['region']. ' - ' .$_POST['realm']. ')</u> doesn\'t exist!</span><br />';
		}
	}
}

// LOGIN PROCESSING
if(isset($_POST['guild']) && isset($_POST['pw'])) {
	
	if($_POST['pw'] != '') {
		
		$check = mysqli_fetch_array(mysqli_query($stream, "SELECT `password` FROM `ovw_guilds` WHERE `id` = '" .$_POST['guild']. "'"));
	
		if(md5($_POST['pw']) == $check['password']) {
		
			$fetch = mysqli_fetch_array(mysqli_query($stream, "SELECT `guild_name`, `realm`, `region`, `shortlink`, `tracked_chars` FROM `ovw_guilds` WHERE `id` = '" .$_POST['guild']. "'"));
			$_SESSION['guild'] = $fetch['guild_name'];
			$_SESSION['region'] = $fetch['region'];
			$_SESSION['tracked'] = $fetch['tracked_chars'];
			$_SESSION['shortlink'] = $fetch['shortlink'];
		
			$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$fetch['realm']. "'"));
		
			$_SESSION['realm'] = $realm_name['name'];
		
			$_SESSION['table'] = $_POST['guild'];
		
			// UPDATE LOGIN TIME
			$refresh_login = mysqli_query($stream, "UPDATE `ovw_guilds` SET `last_login` = '" .time('now'). "' WHERE `id` = '" .$_SESSION['table']. "'");
		}
		elseif(md5($_POST['pw']) != $check['password']) {
			$login_wrong_pw = '<br /><span style="color: coral; text-align: center;">Sorry, password wrong!</span><br />';
		}
	}
	elseif($_POST['pw'] == '') {
		$fetch = mysqli_fetch_array(mysqli_query($stream, "SELECT `guild_name`, `realm`, `region`, `shortlink`, `tracked_chars` FROM `ovw_guilds` WHERE `id` = '" .$_POST['guild']. "'"));
		
		$_SESSION['guild'] = $fetch['guild_name'];
		$_SESSION['region'] = $fetch['region'];
		$_SESSION['tracked'] = $fetch['tracked_chars'];
		$_SESSION['shortlink'] = $fetch['shortlink'];
		
		$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$fetch['realm']. "'"));
		
		$_SESSION['realm'] = $realm_name['name'];
		
		$_SESSION['table'] = $_POST['guild'];
		
		$_SESSION['guest'] = 'guest';
	}
}

// SHARED LINK
if(isset($_GET['sl']) && strlen($_GET['sl']) == '32') {

	$fetch = mysqli_fetch_array(mysqli_query($stream, "SELECT `id`, `guild_name`, `realm`, `region`, `shortlink`, `tracked_chars` FROM `ovw_guilds` WHERE `shortlink` = '" .$_GET['sl']. "'"));
		
	$_SESSION['guild'] = $fetch['guild_name'];
	$_SESSION['region'] = $fetch['region'];
	$_SESSION['tracked'] = $fetch['tracked_chars'];
	$_SESSION['shortlink'] = $fetch['shortlink'];
		
	$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$fetch['realm']. "'"));
		
	$_SESSION['realm'] = $realm_name['name'];
	
	$_SESSION['table'] = $fetch['id'];
	
	$_SESSION['guest'] = 'guest';
}

echo '<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="" />
	<meta name="robots" content="index, nofollow" />
	<meta name="language" content="en" />
	<meta name="description" content="" />
	<meta name="keywords" lang="en" content="" />
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" href="css/style.css" />
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>';

	if(isset($_SESSION['guild'])) {
		echo '<title>AGS - ' .$_SESSION['guild']. '</title>';
	}
	elseif(!isset($_SESSION['guild'])) {
		echo '<title>Advanced Guild Statistics</title>
		<script type="text/javascript">
			function realms(str) {
				var r = $("#region").val();
				$.get("var/ajax/realms.php?region="+r, function(data) {
					$("#realms").html(data);
				});	
			}
		</script>';
	}

	echo '
</head>
<body style="margin: 0px; background-color: rgba(56, 66, 88, 0.85); font-family: R-Regular; color: black; font-size: 15px;">

	<div style="width: 100%; height: 100%; margin: 0 auto;">';
	
		if(isset($_SESSION['shortlink'])) {
			if(isset($_GET['sl'])) {
				unset($_GET['sl']);
			}
			foreach($_GET as $get => $value) {
				$current_share = $current_share.'&' .$get. '=' .$value. '';
			}
			$share = "return confirm('Share this link: http://artifactpower.info/dev/?sl=" .$_SESSION['shortlink'].$current_share. "')";
			$share = ' <span style="color: white;" onclick="' .$share. '">Share</span> ]';
		}
		
		include('var/nav.php');

		if(!isset($_SESSION['guild'])) {
			if(isset($_GET['resources'])) {
				include('var/resources.php');
			}
		elseif(!isset($_GET['resources'])) {
			
			include('var/lp.php');
		}
	}
	elseif(isset($_SESSION['guild'])) {
		
		include('var/module_organizer.php');
		
	}
	echo '
	</div>
</body>
</html>';

mysqli_close($stream);

?>