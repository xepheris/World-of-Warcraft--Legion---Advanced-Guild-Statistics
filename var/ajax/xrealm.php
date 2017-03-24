<?php
	
session_start();

include('stream.php');

header('Content-type: application/json');


$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

$rows = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `class` = '0'");
$update_this = array();

while($char_id = mysqli_fetch_array($rows)) {
	array_push($update_this, $char_id['id']);
}

$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));

$escaped_session_guild_name = str_replace(' ', '%20', $_SESSION['guild']);

$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .addslashes($_SESSION['realm']). "'"));

// FETCH GUILD MEMBER API
$url = 'https://' .$_SESSION['region']. '.api.battle.net/wow/guild/' .$actual_realm_name['short']. '/' .$escaped_session_guild_name. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';

$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);

$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));

$import = array();

if($data != '') {
	$data = json_decode($data, true);
		
	// PARSE GUILD MEMBERS WITH MEMBERS TO IMPORT		
	foreach($update_this as $char_id) {
		foreach($data['members'] as $members) {
			
			$name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "` WHERE `id` = '" .$char_id. "'"));
			$name = $name['name'];
						
			if($members['character']['name'] == $name) {
				$realm = $members['character']['realm'];
								
				$realm = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `name` = '" .addslashes($realm). "'"));
				
				$realm = $realm['id']; 
				
				// PUSH INTO ARRAY: ID -> REALM NUMBER
				$import[$char_id] = $realm;
								
			}
		}
	}
}

$last_entry = end($import);

echo '{';

foreach($import as $id => $realm) {
	if($realm != $last_entry) {
		echo '"id":"' .$id. '", "realm_id":"' .$realm. '", ';
	}
	elseif($realm == $last_entry) {
		echo '"id":"' .$id. '", "realm_id":"' .$realm. '"';
	}
}

echo '}';

?>