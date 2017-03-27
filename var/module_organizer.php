<?php

$table_name = '' .$_SESSION['table']. '_' .$_SESSION['guild']. '_' .$_SESSION['region']. '_' .$_SESSION['realm']. '';

// IMPORT
if(isset($_GET['import'])) {
	
	
	include('import/index.php');
	
}
// LOGOUT
elseif(isset($_GET['logout'])) {
	
	include('logout/index.php');

}

// CONTACT FORM
elseif(isset($_GET['contact'])) {
	
	echo '<div style="width: 90%; height: auto; margin-left: 5%; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">';
	
	include('contact/index.php');
	
	echo '</div>';

}

// MANUALLY CHANGE NAME VIA INSPECT MODULE

elseif(isset($_GET['change_name']) && is_numeric($_GET['change_name'])) {
	
	include('inspect/change_name.php');
}

// MANUALLY REASSIGN ROLES VIA INSPECT MODULE

elseif(isset($_GET['change_role']) && is_numeric($_GET['change_role'])) {
	
	include('inspect/change_role.php');
}

// MANUALLY ADD LEGENDARIES VIA INSPECT MODULE

elseif(isset($_GET['edit_legendaries']) && is_numeric($_GET['edit_legendaries'])) {
	
	include('inspect/edit_legendaries.php');
}

// EQ EXPLANATION

elseif(isset($_GET['eq'])) {
	
	include('eq/index.php');	
	
}

// COMPARE

elseif(isset($_GET['compare'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; margin-left: 5%; padding-top: 15px; float: left; text-align: center;">';
	
	include('compare/index.php');
	
	echo '</div>';
	
}

// SIMCRAFT

elseif(isset($_GET['simc'])) {
	
	echo '<div style="width: 90%; height: auto; padding-bottom: 15px; margin-left: 5%; padding-top: 15px; float: left; text-align: center;">';
	
	include('simc/index.php');
	
	echo '</div>';
	
}

elseif(isset($_GET['source']) && is_numeric($_GET['source']) && isset($_GET['compare1']) && is_numeric($_GET['compare1'])) {
	
	echo '<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
	if(!isset($_GET['compare4']) && isset($_GET['compare3']) && is_numeric($_GET['compare3']) && isset($_GET['compare2']) && is_numeric($_GET['compare2'])) {
		include('compare/c3.php');
	}
	elseif(!isset($_GET['compare4']) && !isset($_GET['compare3']) && isset($_GET['compare2']) && is_numeric($_GET['compare2'])) {
		include('compare/c2.php');
	}
	elseif(!isset($_GET['compare4']) && !isset($_GET['compare3']) && !isset($_GET['compare2'])) {
		include('compare/c1.php');
	}
	
	echo '</div>';
}

// INSPECT

elseif(isset($_GET['inspect'])) {
	
	if($_GET['inspect'] == '') {
		echo '<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
		
		include('inspect/index.php');
		
		echo '</div>';
	}	
	elseif(is_numeric($_GET['inspect'])) {
		
		echo '
		<script type="text/javascript" src="js/inspect_update.js"></script>';
	
		echo '<div style="width: 90%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';
	
		if($_SESSION['tracked'] == '0') {
			echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/?import" />';
		}
		elseif($_SESSION['tracked'] != '0') {
			
			// INSPECT SINGLE CHARACTER
		
			// HEADER
		
			include('inspect/head.php');
		
			// EQUIPMENT
		
			include('inspect/equip.php');		
				
			// DUNGEON PROGRESS
		
			include('inspect/dungeons.php');
		
			// REPUTATION
		
			include('inspect/reputation.php');
		
			// KNOWN LEGENDARIES
		
			include('inspect/legendaries.php');
				
			// RAIDPROGRESS
		
			include('inspect/raid.php');
		
			// PAST AP GRAPH
		
			include('inspect/past_ap.php');
		
			// PAST MYTHIC GRAPH
		
			include('inspect/past_mythic.php');
		
			// PAST WQ GRAPH
		
			include('inspect/past_wq.php');
		
			// PAST ITEMLEVEL GRAPH
		
			include('inspect/past_ilvl.php');
				
		}
	
		echo '</div>';
	}
}

// ROSTER OVERVIEW
else {
	
	echo '
	<div style="width: 80%; margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">';

	// AUTO REDIRECT
	if($_SESSION['tracked'] == '0') {
		echo '<meta http-equiv="refresh" content="0;url=http://artifactpower.info/dev/?import" />';
	}
	elseif($_SESSION['tracked'] != '0') {
		
		// ROSTER OVERVIEW
		include('roster/core.php');
	}
		
	echo '
	</div>
	<script type="text/javascript">
	var roster = document.getElementById("roster").offsetHeight;
	var bench = document.getElementById("bench").offsetHeight;
	var sidebar = document.getElementById("sidebar");
	sidebar.style.height = roster+bench-15+"px";
	</script>
	<script type="text/javascript" src="js/functions.js"></script>';
	
}

?>