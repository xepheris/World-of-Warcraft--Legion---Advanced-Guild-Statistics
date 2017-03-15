<?php

session_start();
	
include('stream.php');
	
$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';

$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` ORDER BY `name` ASC");

while($id = mysqli_fetch_array($fetch_ids)) {
	
	$_GET['character'] = $id['id'];
	
	include('update.php');
	
}


?>