<?php

session_start();

$contact = "return confirm('If you wish to contact me, either use the contact form when logged in or write me a mail to: xepheris.dh.tank@gmail.com')";

// REGISTER PROCESSING
if(isset($_POST['gname']) && isset($_POST['region']) && isset($_POST['realm']) && isset($_POST['pw'])) {
	include('var/stream.php');
	
	include('var/register.php');
}

// LOGIN PROCESSING
if(isset($_POST['guild']) && isset($_POST['pw'])) {
	
	include('var/stream.php');
	
	include('var/login.php');
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