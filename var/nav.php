<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; text-align: center; float: left; background-color: darkslategrey; box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);  -moz-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);">
	<span style="color: orange; font-size: 26px;">Advanced Guild Statistics</span>
	<br />
	<br />';

	// LOGGED OUT
	if(!isset($_SESSION['guild'])) {
		echo '<span style="font-size: 18px; color: white;">
		<a href="">Login & Register</a> |
		<a href="">Demo</a> |
		<span style="color: white;" onclick="' .$contact. '">Contact</span> |
		<a href="https://github.com/xepheris/World-of-Warcraft--Legion---Advanced-Guild-Statistics">Github</a> |
		<div class="dd">
			Other Tools
			<div class="dd-c">
				<a href="http://check.artifactpower.info" style="color: black;">Advanced Armory Access</a>
				<br />
				<a href="http://youtube.com/c/Xepheris" style="color: black;">High M+ Vengeance Demon Hunter VODs</a>
			</div>
		</div>
		</span>';
	}
	// LOGGED IN
	elseif(isset($_SESSION['guild'])) {
		echo '<span style="font-size: 18px; color: white;"><a href="">Update All</a> |
		<a href="?import">Import</a> |
		<a href="">Weekly Statistics</a> |
		<a href="">Compare</a> |
		<a href="">Share</a> |
		<a href="">Settings</a> |
		<a href="">Contact</a> |
		<a href="?logout">Logout</a>
		</span>';
	}
echo '</div>';
	
?>