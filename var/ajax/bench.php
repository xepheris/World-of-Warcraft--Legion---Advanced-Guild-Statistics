<?php

session_start();

include( 'stream.php' );

$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

// GUEST = VIEW ONLY
if($_SESSION['guest'] != 'guest') {
		
	$bench = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `status` = '1' WHERE `id` = '" .$_GET['bench']. "'");
	
}

?>