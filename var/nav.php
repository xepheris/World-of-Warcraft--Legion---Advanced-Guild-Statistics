<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; text-align: center; float: left; background-color: darkslategrey; box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);  -moz-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);">';

	// LOGGED OUT
	if(!isset($_SESSION['guild'])) {
		echo '<a href="http://artifactpower.info/dev/"><span style="color: orange; font-size: 26px;">Advanced Guild Statistics</span></a>
		<br />
		<br />
		<span class="nav">
		<a href="http://artifactpower.info/dev">Login & Register</a> |
		<a href="?resources">Resources & Inspirations</a> |
		<span style="color: white;" onclick="' .$contact. '">Contact</span> |
		<div class="dd">
			Links
			<div class="dd-c">
				<a href="http://reddit.com/r/competitivewow" style="color: black;">Competitive WoW subreddit</a>
				<br />
				<a href="http://check.artifactpower.info" style="color: black;">Advanced Armory Access</a>
				<br />
				<a href="http://youtube.com/c/Xepheris" style="color: black;">High M+ Vengeance Demon Hunter VODs</a>
				<br />
				<a href="https://github.com/xepheris/World-of-Warcraft--Legion---Advanced-Guild-Statistics" style="color: black;">Github repository</a>
			</div>
		</div>
		<br />
		<br />
		</span>';
	}
	// LOGGED IN
	elseif(isset($_SESSION['guild'])) {
		echo '<a href="http://artifactpower.info/dev/"><span style="color: orange; font-size: 26px; text-transform: uppercase;">' .$_SESSION['guild']. ' (' .$_SESSION['region']. '-' .$_SESSION['realm']. ')</span></a>
		<br />
		<br />
		<span class="nav">';
		
		// ROSTER SWAP		
		if(isset($_GET['import']) || isset($_GET['inspect']) || isset($_GET['compare']) || isset($_GET['source']) || isset($_GET['contact']) || isset($_GET['change_role']) || isset($_GET['change_name']) || isset($_GET['edit_legendaries'])) {
			$roster = '
			[ <a href="http://artifactpower.info/dev/">Roster</a>';
		}
		elseif(!isset($_GET['import']) && !isset($_GET['inspect']) && !isset($_GET['compare']) && !isset($_GET['source']) && !isset($_GET['contact']) && !isset($_GET['change_name']) && !isset($_GET['change_role']) && !isset($_GET['edit_legendaries'])) {
			$roster = '
			[ <a href="http://artifactpower.info/dev/" style="text-decoration: underline;">Roster</a>';
			$update = '<br /><br />[ <a class="global_update" onclick="global_update();">update all characters</a> ]';
		}
		
		echo $roster;
		
		// INSPECT SWAP
		if(isset($_GET['inspect']) || isset($_GET['change_role']) || isset($_GET['change_name']) || isset($_GET['edit_legendaries'])) {
			$inspect = '
			<a href="?inspect" style="text-decoration: underline;">Inspect</a>';
		}
		elseif(!isset($_GET['inspect'])) {
			$inspect = '
			<a href="?inspect">Inspect</a>';
		}
		
		echo $inspect;
		
		// COMPARE SWAP
		if(isset($_GET['compare']) || isset($_GET['source'])) {
			$compare = '
			<a href="?compare" style="text-decoration: underline;">Compare</a>';
		}
		elseif(!isset($_GET['compare']) && !isset($_GET['source'])) {
			$compare = '
			<a href="?compare">Compare</a>';
		}
		
		echo $compare;
		
		// SHARE SWAP
		if(isset($_GET['share'])) {
			$share = '
			<a href="?share" style="text-decoration: underline;">Share</a> ]';
		}
		elseif(!isset($_GET['share'])) {
			$share = '
			<a href="?share">Share</a> ]';
		}
		
		echo $share;
				
		// IMPORT SWAP		
		if(isset($_GET['import'])) {
			$import = '
			[ <a href="?import" style="text-decoration: underline;">Import</a> ]';
		}
		elseif(!isset($_GET['import'])) {
			$import = '
			[ <a href="?import">Import</a> ]';
		}
		
		echo $import;
		
		// EQ SWAP
		if(isset($_GET['eq'])) {
			$eq = '
			[ <a href="?eq" style="text-decoration: underline;">What is EQ?</a>';
		}
		elseif(!isset($_GET['eq'])) {
			$eq = '
			[ <a href="?eq">What is EQ?</a>';
		}
		
		echo $eq;
				
		// CONTACT SWAP
		if(isset($_GET['contact'])) {
			$contact = '
			<a href="?contact" style="text-decoration: underline;">Contact</a>';
		}
		elseif(!isset($_GET['contact'])) {
			$contact = '
			<a href="?contact">Contact</a>';
		}
		
		echo $contact;
		
		echo '
		<a href="?logout">Logout</a> ]
		' .$update. '
		</span>
		<br />
		<br />';
	}
echo '</div>';
	
?>