<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; text-align: center; float: left; background-color: darkslategrey; box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);  -moz-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);">';

	// LOGGED OUT
	if(!isset($_SESSION['guild'])) {
		echo '<a href="http://artifactpower.info/dev/"><span style="color: orange; font-size: 26px;">Advanced Guild Statistics</span></a>
		<br />
		<br />
		<span style="font-size: 18px; color: white;">
		<a href="">Login & Register</a> |
		<a href="">Demo</a> |
		<span style="color: white;" onclick="' .$contact. '">Contact</span> |
		<div class="dd">
			Links
			<div class="dd-c">
				<br />
				<a href="http://reddit.com/r/competitivewow">Competitive WoW subreddit</a>
				<br />
				<a href="http://check.artifactpower.info" style="color: black;">Advanced Armory Access</a>
				<br />
				<a href="http://youtube.com/c/Xepheris" style="color: black;">High M+ Vengeance Demon Hunter VODs</a>
				<br />
				<a href="https://github.com/xepheris/World-of-Warcraft--Legion---Advanced-Guild-Statistics" style="color: black;">Github repository</a>
			</div>
		</div>
		</span>';
	}
	// LOGGED IN
	elseif(isset($_SESSION['guild'])) {
		echo '<a href="http://artifactpower.info/dev/"><span style="color: orange; font-size: 26px; text-transform: uppercase;">' .$_SESSION['guild']. ' (' .$_SESSION['region']. '-' .$_SESSION['realm']. ')</span></a>
		<br />
		<br />
		<span style="font-size: 18px; color: white;">
		<ul>
		<li><a href="">Update</a> |</li>	
		<li><a href="?import">Import</a> |</li>
		<li><a href="?inspect">Inspect</a> |</li>
		<li><a href="?compare">Compare</a> |</li>
		<li><a href="?share">Share</a> |</li>
		<li><a href="?contact">Contact</a> |</li>
		<li><a href="?logout">Logout</a></li>
		</ul>
		</span>';
	}
echo '</div>';
	
?>