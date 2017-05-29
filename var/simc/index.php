<?php

if ( $_GET[ 'simc' ] == '' ) {
	echo '<div style="width: 100%; height: auto; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-bottom: 15px; opacity: 1.0 !important;">';

	$class_fetcher = mysqli_query( $stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`" );
	while ( $class = mysqli_fetch_array( $class_fetcher ) ) {
		echo '<div style="width: 33.33%; float: left; height: auto; background-color: ' . $class[ 'color' ] . '; min-height: 200px;">
	<br /><span style="color: white; text-transform: uppercase; font-size: 25px; opacity: 0.5;">' . $class[ 'class' ] . '</span><br />
	';

		$guild_class_fetcher = mysqli_query( $stream, "SELECT `id`, `name` FROM `" . $table_name . "` WHERE `class` = '" . $class[ 'id' ] . "' ORDER BY `name` ASC" );
		while ( $class_player = mysqli_fetch_array( $guild_class_fetcher ) ) {
			echo '<a style="text-transform: uppercase;" href="?simc=' . $class_player[ 'id' ] . '">[ ' . $class_player[ 'name' ] . ' ]</a>   ';
		}

		echo '</div>';
	}

	echo '
</div>';

} elseif ( $_GET[ 'simc' ] != '' && is_numeric( $_GET[ 'simc' ] ) ) {

	echo '
<script type="text/javascript">
function settings(str) {
	var settings = document.getElementsByClassName("settings");
	
	$(settings).fadeIn("slow");
	$(settings).css("display", "initial");
	
	
}
</script>
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
<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-left: 5%; min-width: 1180px;">';

	if ( isset( $_GET[ 'iterations' ] ) && is_numeric( $_GET[ 'iterations' ] ) && isset( $_GET[ 'role' ] ) && isset( $_GET[ 'fight' ] ) && is_numeric( $_GET[ 'length' ] ) && isset( $_GET[ 'length' ] ) ) {

		$realm_id = mysqli_fetch_array( mysqli_query( $stream, "SELECT `realm` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'simc' ] . "'" ) );
		$actual_realm_name = mysqli_fetch_array( mysqli_query( $stream, "SELECT `short` FROM `ovw_realms` WHERE `id` = '" . $realm_id[ 'realm' ] . "'" ) );

		$general = mysqli_fetch_array( mysqli_query( $stream, "SELECT `name` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'simc' ] . "'" ) );

		echo '<p style="color: orange; font-size: 18px;">SimulationCraft string</p>
	<textarea style="width: 750px; height: 500px;" onclick="this.focus();this.select()" readonly="readonly">armory=' . $_SESSION[ 'region' ] . ',' . $actual_realm_name[ 'short' ] . ',' . $general[ 'name' ] . '
name="' . $general[ 'name' ] . '"
role="' . $_GET[ 'role' ] . '"';
		if ( isset( $_GET[ 'skill' ] ) ) {
			echo '
skill=' . $_GET[ 'skill' ] . '';
		}
		if ( isset( $_GET[ 'race' ] ) ) {
			echo '
race=' . $_GET[ 'race' ] . '';
		}
		if ( isset( $_GET[ 'talents' ] ) && is_numeric( $_GET[ 'talents' ] ) && strlen( $_GET[ 'talents' ] ) == '7' ) {
			echo '
talents=' . $_GET[ 'talents' ] . '';
		}
		if ( isset( $_GET[ 't19_2' ] ) ) {
			if ( $_GET[ 't19_2' ] == '0' ) {
				echo '
disable_2_set="19"';
			} elseif ( $_GET[ 't19_2' ] == '1' ) {
				echo '
enable_2_set="19"';
			}
		}
		if ( isset( $_GET[ 't19_4' ] ) ) {
			if ( $_GET[ 't19_4' ] == '0' ) {
				echo '
disable_4_set="19"';
			} elseif ( $_GET[ 't19_4' ] == '1' ) {
				echo '
enable_4_set="19"';
			}
		}
		if ( isset( $_GET[ 't20_2' ] ) ) {
			if ( $_GET[ 't20_2' ] == '0' ) {
				echo '
disable_2_set="20"';
			} elseif ( $_GET[ 't20_2' ] == '1' ) {
				echo '
enable_2_set="20"';
			}
		}
		if ( isset( $_GET[ 't20_4' ] ) ) {
			if ( $_GET[ 't20_4' ] == '0' ) {
				echo '
disable_4_set="20"';
			} elseif ( $_GET[ 't20_4' ] == '1' ) {
				echo '
enable_4_set="20"';
			}
		}
		if ( isset( $_GET[ 't21_2' ] ) ) {
			if ( $_GET[ 't21_2' ] == '0' ) {
				echo '
disable_2_set="21"';
			} elseif ( $_GET[ 't21_2' ] == '1' ) {
				echo '
enable_2_set="21"';
			}
		}
		if ( isset( $_GET[ 't21_4' ] ) ) {
			if ( $_GET[ 't21_4' ] == '0' ) {
				echo '
disable_4_set="21"';
			} elseif ( $_GET[ 't21_4' ] == '1' ) {
				echo '
enable_4_set="21"';
			}
			$slots = array( 'Head' => 'head', 'Neck' => 'neck', 'Shoulders' => 'shoulders', 'Back' => 'back', 'Chest' => 'chest', 'Wrists' => 'wrists', 'Gloves' => 'gloves', 'Waist' => 'waist', 'Legs' => 'waist', 'Feet' => 'feet', 'Finger1' => 'finger1', 'Finger2' => 'finger2', 'Trinket1' => 'trinket1', 'Trinket2' => 'trinket2' );
			foreach ( $slots as $slot => $translated ) {
				if ( !empty( $_GET[ '' . $slot . '_name' ] ) && !empty( $_GET[ '' . $slot . '_id' ] ) && !empty( $_GET[ '' . $slot . '_ilevel' ] ) && !empty( $_GET[ '' . $slot . '_bonus' ] ) ) {

					$ {
						'bonus_' . $slot . ''
					} = ',bonus_id=' . str_replace( ':', '/', $_GET[ '' . $slot . '_bonus' ] ) . '';

					$_GET[ '' . $slot . '_name' ] = '' . str_replace( ' ', '_', $_GET[ '' . $slot . '_name' ] ) . '';
					$_GET[ '' . $slot . '_name' ] = '' . str_replace( ',', '', $_GET[ '' . $slot . '_name' ] ) . '';

					if ( !empty( $_GET[ '' . $slot . '_gem' ] ) ) {

						if ( $_GET[ '' . $slot . '_gem' ] == '130215' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=100crit';
						} elseif ( $_GET[ '' . $slot . '_gem' ] == '130216' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=100haste';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130217' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=100vers';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130218' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=100mastery';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130219' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=150crit';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130220' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=150haste';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130221' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=150vers';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130222' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=150mastery';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130246' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=200str';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130247' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=200agi';
						}
						elseif ( $_GET[ '' . $slot . '_gem' ] == '130248' ) {
							$ {
								'gem_' . $slot . ''
							} = ',gems=200int';
						}
					}

					if ( !empty( $_GET[ '' . $slot . '_enchant' ] ) ) {

						if ( $_GET[ '' . $slot . '_enchant' ] == '128537' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150crit';
						} elseif ( $_GET[ '' . $slot . '_enchant' ] == '128538' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150haste';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128539' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150mastery';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128540' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150vers';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128541' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200crit';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128542' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200haste';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128543' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200mastery';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128544' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200vers';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128545' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150str';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128546' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150agi';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128547' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=150int';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128548' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200str';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128549' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200agi';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128550' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=200int';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '141909' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=600mastery';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128551' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_claw';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128552' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_distant_army';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '128553' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_hidden_satyr';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '141908' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_heavy_hide';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '141910' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_ancient_priestess';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '144304' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_master';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '144305' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_versatile';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '144306' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_quick';
						}
						elseif ( $_GET[ '' . $slot . '_enchant' ] == '144307' ) {
							$ {
								'enchant_' . $slot . ''
							} = ',enchant=mark_of_the_deadly';
						}
					}

					echo '
' . $translated . '=' . $_GET[ '' . $slot . '_name' ] . ',id=' . $_GET[ '' . $slot . '_id' ] . ',ilevel=' . $_GET[ '' . $slot . '_ilevel' ] . '' . $ {
						'bonus_' . $slot . ''
					} . '' . $ {
						'gem_' . $slot . ''
					} . '' . $ {
						'enchant_' . $slot . ''
					} . '';
				}
			}
		}
		echo '

# SimC Configs
iterations="' . $_GET[ 'iterations' ] . '"
fight_style="' . $_GET[ 'fight' ] . '"
max_time="' . $_GET[ 'length' ] . '"
calculate_scale_factors="1"
scale_only="stamina,strength,intellect,agility,crit,mastery,vers,haste"
fixed_time="1"</textarea>
<p style="color: orange; font-size: 18px;">Run it now on <a href="https://raidbots.com/simbot/advanced" title="Raidbots - online simcrafting">Raidbots.com</a> or within the <a href="http://simulationcraft.org/download.html" title="SimulationCraft download">program</a>.<br />
<a href="?inspect=' . $_GET[ 'simc' ] . '">Back to your profile</a></p>';

	}

	if ( !isset( $_GET[ 'iterations' ] ) ) {
		$general = mysqli_fetch_array( mysqli_query( $stream, "SELECT `name`, `updated`, `role1` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'simc' ] . "'" ) );

		// AVERAGE PARSE FETCHER
		$performance_lfr = array();
		$performance_normal = array();
		$performance_heroic = array();
		$performance_mythic = array();

		for ( $i = '1'; $i <= '4'; $i++ ) {
			if ( $i == '1' ) {
				$raid = 'en';
			} elseif ( $i == '2' ) {
				$raid = 'tov';
			}
			elseif ( $i == '3' ) {
				$raid = 'nh';
			}
			elseif ( $i == '4' ) {
				$raid = 'tos';
			}

			$diff = array( 'lfr_parse', 'normal_parse', 'heroic_parse', 'mythic_parse' );

			foreach ( $diff as $mode ) {
				$counter = mysqli_num_rows( mysqli_query( $stream, "SELECT * FROM `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'simc' ] . "_raid_" . $i . "` WHERE `" . $mode . "` != '0'" ) );

				if ( $counter > '0' ) {
					$sum = mysqli_fetch_array( mysqli_query( $stream, "SELECT SUM(`" . $mode . "`) as `" . $mode . "` FROM `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'simc' ] . "_raid_" . $i . "` WHERE `" . $mode . "` != '0'" ) );
					$avg = $sum[ '' . $mode . '' ] / $counter;

					if ( $mode == 'lfr_parse' ) {
						array_push( $performance_lfr, $avg );
					} elseif ( $mode == 'normal_parse' ) {
						array_push( $performance_normal, $avg );
					}
					elseif ( $mode == 'heroic_parse' ) {
						array_push( $performance_heroic, $avg );
					}
					elseif ( $mode == 'mythic_parse' ) {
						array_push( $performance_mythic, $avg );
					}
				}
			}
		}

		// AVERAGE PERFORMANCE
		$performance_counter = count( $performance_lfr );
		$average_performance_lfr = round( array_sum( $performance_lfr ) / $performance_counter, 2 );

		$performance_counter = count( $performance_normal );
		$average_performance_normal = round( array_sum( $performance_normal ) / $performance_counter, 2 );

		$performance_counter = count( $performance_heroic );
		$average_performance_heroic = round( array_sum( $performance_heroic ) / $performance_counter, 2 );

		$performance_counter = count( $performance_mythic );
		$average_performance_mythic = round( array_sum( $performance_mythic ) / $performance_counter, 2 );

		if ( $average_performances_lfr != '0' && $average_performance_lfr != '0' && $average_performance_heroic != '0' && $average_performance_mythic != '0' ) {
			$average_performances = '(hist. avg. performance: LFR ' . $average_performance_lfr . '% | NORMAL ' . $average_performance_normal . '% | HEROIC ' . $average_performance_lfr . '% | MYTHIC ' . $average_performance_mythic . '%)';
		} else {
			$average_performances = '(WCLogs ID missing/reports hidden)';
		}


		if ( $general[ 'role1' ] == '0' || $general[ 'role1' ] == '1' ) {
			$role = '<option value="dps" selected>DPS</option>
		<option value="heal">Heal</option>
		<option value="tank">Tank</option>';
		} elseif ( $general[ 'role1' ] == '2' ) {
			$role = '<option value="dps">DPS</option>
		<option value="heal">Heal</option>
		<option value="tank" selected>Tank</option>';
		}
		elseif ( $general[ 'role1' ] == '3' ) {
			$role = '<option value="dps">DPS</option>
		<option value="heal" selected>Heal</option>
		<option value="tank">Tank</option>';
		}

		$time_diff = time( 'now' ) - $general[ 'updated' ];

		if ( $time_diff >= '3600' ) {
			$warning = '<p style="font-size: 18px; color: coral;">Warning: this character\'s data is older than one hour. If you wish to update before, click the icon:
		<img src="img/update.png" alt="404" style="width: 21px;" onclick="update(this.id);" id="' . $_GET[ 'simc' ] . '" class="still' . $_GET[ 'simc' ] . '" /><span  class="name' . $_GET[ 'simc' ] . '"></span></p>';
		}

		$realm_id = mysqli_fetch_array( mysqli_query( $stream, "SELECT `realm` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'simc' ] . "'" ) );
		$actual_realm_name = mysqli_fetch_array( mysqli_query( $stream, "SELECT `short` FROM `ovw_realms` WHERE `id` = '" . $realm_id[ 'realm' ] . "'" ) );

		echo '<p style="color: orange; font-size: 18px;">Generating SimulationCraft string for ' . $general[ 'name' ] . '</p>
	' . $warning . '
	<p style="color: orange; font-size: 18px;">Quick pre-sim without modifier on <a href="https://raidbots.com/simbot/stats?region=' . strtolower($_SESSION[ 'region' ]) . '&realm=' . strtolower($actual_realm_name[ 'short' ]) . '&name=' . strtolower($general[ 'name' ]) . '" title="Raidbots.com - online simcrafting">Raidbots.com</a>.</p>
	<form method="GET" action="">
	<input type="text" hidden value="' . $_GET[ 'simc' ] . '" name="simc" />
	<table style="margin: 0 auto; text-align: left;">
	<thead>
	</thead>
	<tbody>
	<tr><td colspan="2" style="color: orange; text-align: center;">Necessary settings</td></tr>
	<tr>
		<td>Iterations</td>
		<td><select name="iterations" required>
			<option value="1000" title="Primarily for quick tests, generally not reliable results">1.000</option>
			<option value="10000" title="Default, produces good results for nearly everyone" selected>10.000</option>
			<option value="15000" title="Rarely needed, only use if you know you need it">15.000</option>
			<option value="20000" title="Rarely needed, only use if you know you need it">20.000</option>
			<option value="25000" title="Rarely needed, only use if you know you need it">25.000</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>Fight style</td>
		<td><select name="fight" required>
				<option value="Patchwerk" title="No movement, single target">Patchwerk</option>
				<option value="HecticAddCleave" title="Regular add spawns, frequent movement. Similar to T15 Horridon">Hectic Add Cleave</option>
				<option value="LightMovement" title="Infrequent movement - Move 50 yards every 85 seconds">Light Movement</option>
				<option value="HeavyMovement" title="Frequent movement - Move 25 yards every 10 seconds">Heavy Movement</option>
				<option value="HelterSkelter" title="Movement every 30 seconds, interrupts every 30 seconds, boss invulnerable every 120 seconds">Helter Skelter</option>
				<option value="Ultraxion">Ultraxion</option>
				<option value="Beastlord">Beastlord</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Fight length</td>
		<td><select name="length" required>
			<option value="180">3 minutes</option>
			<option value="300" selected>5 minutes</option>
			<option value="420">7 minutes</option>
			<option value="600">10 minutes</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>Role</td>
		<td><select name="role" required>
			' . $role . '
		</select>
		</td>
	</tr>
	
	<tr>
		<td colspan="2" style="color: orange; text-align: center;"><span id="settings" onclick="settings()" style="color: white;">Show advanced settings</span></td>
	</tr>
	
	</tbody>
	</table>
	
	<br />
	
	<table style="margin: 0 auto; text-align: left; display: none;" class="settings">
	<thead>
	</thead>
	<tbody>		
	
	<tr>
		<td>Swap talents</td>
		<td><input maxlength="7" name="talents" placeholder="e.g. 313122" /></td>
	</tr>
	
	<tr>
		<td><a href="https://github.com/simulationcraft/simc/wiki/Characters#basics" title="SimCraft wiki">Simulate another race</a></td>
		<td><select name="race">
			<option disabled selected></option>
			<option value="blood_elf">Blood Elf</option>
			<option value="draenei">Draenei</option>
			<option value="dwarf">Dwarf</option>
			<option value="gnome">Gnome</option>
			<option value="goblin">Goblin</option>
			<option value="human">Human</option>
			<option value="night_elf">Night Elf</option>
			<option value="orc">Orc</option>
			<option value="tauren">Tauren</option>
			<option value="troll">Troll</option>
			<option value="undead">Undead</option>
			<option value="worgen">Worgen</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td><a href="https://github.com/simulationcraft/simc/wiki/Characters#skill" title="SimCraft wiki">Skill level (default = 100%)</a></td>
		<td><select name="skill">
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
		</td>
	</tr>
	
	<tr><td colspan=2">' . $average_performances . '</td></tr>
	
	</tbody>
	</table>
	
	<br />
	<br />
	
	<table style="margin: 0 auto; text-align: left; display: none;" class="settings">
	<thead>
	</thead>
	<tbody>
	<tr><td colspan="2" style="color: orange; text-align: center;"><a href="https://github.com/simulationcraft/simc/wiki/Characters#disable-set-bonuses" title="SimCraft wiki">Specifically dis- or enable set bonuses</a> - always for current Armory spec!</td></tr>
	
	<tr>
		<td>T19 (Nighthold)</td>
		<td><select name="t19_2">
			<option selected></option>
			<option value="1">2p bonus enabled</option>
			<option value="0">2p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>T19 (Nighthold)</td>
		<td><select name="t19_4">
			<option selected></option>
			<option value="1">4p bonus enabled</option>
			<option value="0">4p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>T20 (Tomb of Sargeras)</td>
		<td><select name="t20_2">
			<option selected></option>
			<option value="1">2p bonus enabled</option>
			<option value="0">2p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>T20 (Tomb of Sargeras)</td>
		<td><select name="t20_4">
			<option selected></option>
			<option value="1">4p bonus enabled</option>
			<option value="0">4p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>T21 (Argus Raid TBA)</td>
		<td><select name="t21_2">
			<option selected></option>
			<option value="1">2p bonus enabled</option>
			<option value="0">2p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	<tr>
		<td>T21 (Argus Raid TBA)</td>
		<td><select name="t21_4">
			<option selected></option>
			<option value="1">4p bonus enabled</option>
			<option value="0">4p bonus disabled</option>
		</select>
		</td>
	</tr>
	
	</tbody>
	</table>
	
	<br />
	<br />
	
	<table style="margin: 0 auto; text-align: left; display: none;" class="settings">
	<thead>
	</thead>
	<tbody>
	<tr><td colspan="7" style="color: orange; text-align: center;">Replace gear manually</td></tr>
	<tr><td colspan="7" style="text-align: center;"><img src="img/simc.png" alt="404" title="SimC Wowhead Help1" /></td></tr>';

		$slots = array( 'Head', 'Neck', 'Shoulders', 'Back', 'Chest', 'Wrists', 'Gloves', 'Waist', 'Legs', 'Feet', 'Finger1', 'Finger2', 'Trinket1', 'Trinket2' );

		foreach ( $slots as $slot ) {
			echo '
		<tr>
			<td>' . $slot . '</td>
			<td><input type="text" placeholder="item name" name="' . $slot . '_name" /></td>
			<td><input type="text" placeholder="item id" name="' . $slot . '_id" maxlength="6" /></td>
			<td><input type="text" placeholder="itemlevel" name="' . $slot . '_ilevel" maxlength="3" /></td>
			<td><input type="text" placeholder="bonus ids from wowhead" name="' . $slot . '_bonus" /></td>
			<td>
				<select name="' . $slot . '_gem">
					<option selected disabled>select gem</option>';
			$gems = mysqli_query( $stream, "SELECT `gem_id`, `name` FROM `ovw_gems` ORDER BY `name` ASC" );
			while ( $gem = mysqli_fetch_array( $gems ) ) {
				echo '<option value="' . $gem[ 'gem_id' ] . '">' . $gem[ 'name' ] . '</option>';
			}

			echo '
				</select>
			</td>';
			// ALLOW ENCHANT POSSIBILITY ONLY ON ENCHANTABLE SLOT
			if ( $slot == 'Neck' || $slot == 'Back' || $slot == 'Finger1' || $slot == 'Finger2' ) {
				echo '
			<td>
				<select name="' . $slot . '_enchant">
				<option selected disabled>select enchant</option>';
				// SWAP AVAILABLE LIST ACCORDING TO SLOT
				if ( $slot == 'Neck' ) {
					$slot_number = '2';
				} elseif ( $slot == 'Back' ) {
					$slot_number = '4';
				}
				elseif ( $slot == 'Finger1' || $slot == 'Finger2' ) {
					$slot_number = '11';
				}

				$enchants = mysqli_query( $stream, "SELECT `wowhead_id`, `name` FROM `ovw_enchants`  WHERE `slot` = '" . $slot_number . "' ORDER BY `name` ASC" );
				while ( $enchant = mysqli_fetch_array( $enchants ) ) {
					echo '<option value="' . $enchant[ 'wowhead_id' ] . '">' . $enchant[ 'name' ] . '</option>';
				}
				echo '
				</select>
			</td>';
			}
			echo '
		</tr>';
		}

		echo '
	</tbody>
	</table>
	<br />
	<button type="submit">Create string</button>
	</form>';
	}

	echo '</div>';
}

?>