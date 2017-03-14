<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; text-align: center; float: left; background-color: darkslategrey; box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);  -moz-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 5px 10px rgba(0,0,0,0.5);">';

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
		<span style="font-size: 18px; color: white;">
		<div class="nav">';
		
		// IMPORT SWAP		
		if(isset($_GET['import'])) {
			$import = '
			<a href="?import">IMPORT</a>';
		}
		elseif(!isset($_GET['import'])) {
			$import = '
			<a href="?import">IMPORT</a>';
		}
		
		echo $import;
		
		// INSPECT SWAP
		if(isset($_GET['inspect'])) {
			$inspect = '
			<a href="?inspect">INSPECT</a>';
		}
		elseif(!isset($_GET['inspect'])) {
			$inspect = '
			<a href="?inspect">INSPECT</a>';
		}
		
		echo $inspect;
		
		// COMPARE SWAP
		if(isset($_GET['compare']) || isset($_GET['source'])) {
			$compare = '
			<a href="?compare">COMPARE</a>';
		}
		elseif(!isset($_GET['compare']) && !isset($_GET['source'])) {
			$compare = '
			<a href="?compare">COMPARE</a>';
		}
		
		echo $compare;
		
		// SHARE SWAP
		if(isset($_GET['share'])) {
			$share = '
			<a href="?share">SHARE</a>';
		}
		elseif(!isset($_GET['compare'])) {
			$share = '
			<a href="?share">SHARE</a>';
		}
		
		echo $share;
		
		// CONTACT SWAP
		if(isset($_GET['contact'])) {
			$contact = '
			<a href="?contact">CONTACT</a>';
		}
		elseif(!isset($_GET['compare'])) {
			$contact = '
			<a href="?contact">CONTACT</a>';
		}
		
		echo $contact;
		
		echo '
		<a href="?logout">LOGOUT</a>
		</div>
		</span>';
	}
echo '</div>';
	
?>