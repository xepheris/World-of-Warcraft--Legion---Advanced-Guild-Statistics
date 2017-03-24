<?php

session_start();

include('stream.php');

$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';

$delete = mysqli_query($stream, "TRUNCATE `" .$table_name. "`");

$reset = mysqli_query($stream, "UPDATE `ovw_guilds` SET `tracked_chars` = '0' WHERE `guild_name` = '" .$_SESSION['guild']. "'");

if($delete && $reset) {
	$_SESSION['tracked'] = '0';
	echo 'cleaned';
}

?>