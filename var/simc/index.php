<?php

echo '
<script type="text/javascript">
function update(str) {
	
	var still = document.getElementsByClassName("still"+str);
	still[0].style.display = "none";
		
	var name = document.getElementsByClassName("name"+str);
	
	$(name).html(\'<span style="color: white;">Updating...</span>\');

	$.ajax({
		type: "GET",
		dataType: "html",
		url: "var/ajax/update.php",
		data: {
			character: +str
		},
		success: function (data) {
			location.reload();
		}
	});
};

</script>
<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-left: 5%;">';

if(isset($_GET['iterations']) && is_numeric($_GET['iterations']) && isset($_GET['role']) && isset($_GET['fight']) && is_numeric($_GET['length']) && isset($_GET['length'])) {
	$general = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['simc']. "'"));
	
	echo '<p style="color: orange; font-size: 18px;">SimulationCraft string</p>
	<textarea style="width: 750px; height: 500px;">armory=' .$_SESSION['region']. ',' .$_SESSION['realm']. ',' .$general['name']. '
name="' .$general['name']. '"
role="' .$_GET['role']. '"';
	if(isset($_GET['skill'])) {
		echo '
skill=' .$_GET['skill']. '';
	}
echo '

# SimC Configs
iterations="' .$_GET['iterations']. '"
fight_style="' .$_GET['fight']. '"
max_time="' .$_GET['length']. '"
calculate_scale_factors="1"
scale_only="stamina,strength,intellect,agility,crit,mastery,vers,haste"
fixed_time="1"</textarea>
<p style="color: orange; font-size: 18px;">Can be directly used online on <a href="https://raidbots.com/simbot/advanced" title="Raidbots - online simcrafting">Raidbots.com</a> or within the <a href="http://simulationcraft.org/download.html" title="SimulationCraft download">program</a>.</p>';
	
}
	
if(!isset($_GET['iterations'])) {
	$general = mysqli_fetch_array(mysqli_query($stream, "SELECT `name`, `updated`, `role1` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['simc']. "'"));
	
	// AVERAGE PARSE FETCHER
	$performance_lfr = array();
	$performance_normal = array();
	$performance_heroic = array();
	$performance_mythic = array();
	
	for($i = '1'; $i <= '4'; $i++) {
		if($i == '1') {
			$raid = 'en';
		}
		elseif($i == '2') {
			$raid = 'tov';
		}
		elseif($i == '3') {
			$raid = 'nh';
		}
		elseif($i == '4') {
			$raid = 'tos';
		}
		
		$diff = array('lfr_parse', 'normal_parse', 'heroic_parse', 'mythic_parse');
		
		foreach($diff as $mode) {
			$counter = mysqli_num_rows(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['simc']. "_raid_" .$i. "` WHERE `" .$mode. "` != '0'"));
			
			if($counter > '0') {
				$sum = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`" .$mode. "`) as `" .$mode. "` FROM `" .$_SESSION['table']. "_" .$_GET['simc']. "_raid_" .$i. "` WHERE `" .$mode. "` != '0'"));
				$avg = $sum['' .$mode. '']/$counter;
				
				if($mode == 'lfr_parse') {
					array_push($performance_lfr, $avg);
				}
				elseif($mode == 'normal_parse') {
					array_push($performance_normal, $avg);
				}
				elseif($mode == 'heroic_parse') {
					array_push($performance_heroic, $avg);
				}
				elseif($mode == 'mythic_parse') {
					array_push($performance_mythic, $avg);
				}
			}
		}
	}
	
	// AVERAGE PERFORMANCE
	$performance_counter = count($performance_lfr);
	$average_performance_lfr = round(array_sum($performance_lfr)/$performance_counter, 2);
	
	$performance_counter = count($performance_normal);	
	$average_performance_normal = round(array_sum($performance_normal)/$performance_counter, 2);
	
	$performance_counter = count($performance_heroic);
	$average_performance_heroic = round(array_sum($performance_heroic)/$performance_counter, 2);
	
	$performance_counter = count($performance_mythic);
	$average_performance_mythic = round(array_sum($performance_mythic)/$performance_counter, 2);
	
	if($average_performances_lfr != '0' && $average_performance_lfr != '0' && $average_performance_heroic != '0' && $average_performance_mythic != '0') {		
		$average_performances = '(your avg performance: LFR ' .$average_performance_lfr. '% | NORMAL ' .$average_performance_normal. '% | HEROIC ' .$average_performance_lfr. '% | MYTHIC ' .$average_performance_mythic. '%)';
	}
	else {
		$average_performances = '(cannot display your average performance as you don\'t seem to have warcraftlogs ID or your logs are hidden)';
	}
	
	
	if($general['role1'] == '0' || $general['role1'] == '1') {
		$role = '<option value="dps" selected>DPS</option>
		<option value="heal">Heal</option>
		<option value="tank">Tank</option>';
	}
	elseif($general['role1'] == '2') {
		$role = '<option value="dps">DPS</option>
		<option value="heal">Heal</option>
		<option value="tank" selected>Tank</option>';
	}
	elseif($general['role1'] == '3') {
		$role = '<option value="dps">DPS</option>
		<option value="heal" selected>Heal</option>
		<option value="tank">Tank</option>';
	}
	
	$time_diff = time('now')-$general['updated'];
	
	if($time_diff >= '3600') {
		$warning = '<p style="font-size: 18px; color: coral;">Warning: this character\'s data is older than one hour. If you wish to update before, click the icon:
		<img src="img/update.png" alt="404" style="width: 21px;" onclick="update(this.id);" id="' .$_GET['simc']. '" class="still' .$_GET['simc']. '" /><span  class="name' .$_GET['simc']. '"></span></p>';
	}
	
	echo '<p style="color: orange; font-size: 18px;">Generating SimulationCraft string for ' .$general['name']. '</p>
	' .$warning. '
	<form method="GET" action="">
	<input type="text" hidden value="' .$_GET['simc']. '" name="simc" />
	<p>Iterations
	<select name="iterations" required>
		<option value="1000" title="Primarily for quick tests, generally not reliable results">1.000</option>
		<option value="10000" title="Default, produces good results for nearly everyone" selected>10.000</option>
		<option value="15000" title="Rarely needed, only use if you know you need it">15.000</option>
		<option value="20000" title="Rarely needed, only use if you know you need it">20.000</option>
		<option value="25000" title="Rarely needed, only use if you know you need it">25.000</option>
	</select></p>
	
	<p>Fight style
	<select name="fight" required>
		<option value="Patchwerk" title="No movement, single target">Patchwerk</option>
		<option value="HecticAddCleave" title="Regular add spawns, frequent movement. Similar to T15 Horridon">Hectic Add Cleave</option>
		<option value="LightMovement" title="Infrequent movement - Move 50 yards every 85 seconds">Light Movement</option>
		<option value="HeavyMovement" title="Frequent movement - Move 25 yards every 10 seconds">Heavy Movement</option>
		<option value="HelterSkelter" title="Movement every 30 seconds, interrupts every 30 seconds, boss invulnerable every 120 seconds">Helter Skelter</option>
		<option value="Ultraxion">Ultraxion</option>
		<option value="Beastlord">Beastlord</option>
	</select></p>
	
	<p>Fight length
	<select name="length" required>
		<option value="180">3 minutes</option>
		<option value="300" selected>5 minutes</option>
		<option value="420">7 minutes</option>
		<option value="600">10 minutes</option>
	</select></p>
	
	<p>Role
	<select name="role" required>
		' .$role. '
	</select></p>
	
	<p><span style="color: orange;">Optional</span>
	<a href="https://github.com/simulationcraft/simc/wiki/Characters#skill" title="SimCraft wiki">Skill level</a>
	<select name="skill">
		<option disabled selected></option>
		<option value="0.5">50%</option>
		<option value="0.55">55%</option>
		<option value="0.6">60%</option>
		<option value="0.65">65%</option>
		<option value="0.7">70%</option>
		<option value="0.75">75%</option>
		<option value="0.8">80%</option>
		<option value="0.85">85%</option>
		<option value="0.9">90%</option>
		<option value="0.95">95%</option>
		<option value="1.0">100%</option>
	</select> ≈ average parse
	<br />
	' .$average_performances. '</p>
	<button type="submit">Create string</button>
	</form>';
	
	
}

echo '</div>';

?>