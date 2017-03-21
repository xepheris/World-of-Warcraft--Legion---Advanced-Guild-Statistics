<style type="text/css">
	.selected {
		border: 3px solid orange;
	}
</style>


<script type="text/javascript">
function select(str) {
	var row = document.getElementsByClassName(str);
	
	if($(row).hasClass('selected')) {
	   $(row).removeClass('selected');
	}
	else {
		$(row).addClass('selected');
	}
}
</script>
<script type="text/javascript">
function global_update() {
	
	var text = document.getElementsByClassName("global_update");
	
	$(text).html('updating all current entries - please be patient!');
	$(text).css('color', 'orange');
	
	<?php
	$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` ORDER BY `name` ASC");
	
	while($id = mysqli_fetch_array($fetch_ids)) {
		
		echo '
		var row_' .$id['id']. ' = document.getElementsByClassName(' .$id['id']. ');
		
		for (var i = 0; i < row_' .$id['id']. '.length; i++) {
			row_' .$id['id']. '[0].style.transition = "opacity 1s ease-in-out";
			row_' .$id['id']. '[0].style.opacity = "0.2";
			row_' .$id['id']. '[0].style.filter = "alpha(opacity=20)";
			
			var still_' .$id['id']. ' = document.getElementsByClassName("still"+' .$id['id']. ');
			still_' .$id['id']. '[0].style.display = "none";
			
			var all_active_sublinks = row_' .$id['id']. '[i].getElementsByTagName("a");
			for (var k = 0; k < all_active_sublinks.length; k++) {
				all_active_sublinks[k].style.pointerEvents = "none";
			}
			
			var name_' .$id['id']. ' = document.getElementsByClassName("name"+' .$id['id']. ');
			
			$(name_' .$id['id']. ').html("<span class=\"white\">Updating...</span>");
			
			$.ajax({
				type: "GET",
				dataType: "html",
				url: "var/ajax/update.php",
					data: {
					character: +' .$id['id']. '
					},
				success: function (data) {
					$(name_' .$id['id']. ').html("<span class=\"white\">Updated! Refresh when all are done.</span>");
					row_' .$id['id']. '[0].style.transition = "opacity 1s ease-in-out";
					row_' .$id['id']. '[0].style.opacity = "1";
					row_' .$id['id']. '[0].style.filter = "alpha(opacity=100)";
				}
			});
		}';
	}
	echo '
	}
</script>';

// FETCH GLOBAL TOP ENTRIES
$all_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '0' ORDER BY `name` ASC");
$id_array = array();

while($id_fetcher = mysqli_fetch_array($all_ids)) {
	array_push($id_array, $id_fetcher['id']);
}

$itemlevel_array = array();
$alvl_array = array();
$wq_array = array();
$ap_array = array();
$itemlevel_off_array = array();
$ak_array = array();
$mythic_array = array();
$en_hc_array = array();
$en_m_array = array();
$tov_hc_array = array();
$tov_m_array = array();
$nh_hc_array = array();
$nh_m_array = array();
$tos_hc_array = array();
$tos_m_array = array();
$mythic_array = array();

foreach($id_array as $id) {
	$sql.= "
	SELECT `ilvl_on`, `ilvl_off`, `alvl`, `ap`, `wq` FROM `" .$_SESSION['table']. "_" .$id. "_general` UNION ALL ";
	
	$avg = mysqli_fetch_array(mysqli_query($stream, "SELECT `ilvl_on`, `ilvl_off`, `ap`, `wq`, `ak`, `alvl` FROM `" .$_SESSION['table']. "_" .$id. "_general`"));
	array_push($itemlevel_array, $avg['ilvl_on']);
	array_push($itemlevel_off_array, $avg['ilvl_off']);
	array_push($ap_array, $avg['ap']);
	array_push($wq_array, $avg['wq']);
	array_push($alvl_array, $avg['alvl']);
	array_push($ak_array, $avg['ak']);
	$avg_dungeon = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`mythic`) AS `mythics` FROM `" .$_SESSION['table']. "_" .$id. "_dungeons`"));
	array_push($mythic_array, $avg_dungeon['mythics']);
	$avg_raid1_hc = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_hc` FROM `" .$_SESSION['table']. "_" .$id. "_raid_1` WHERE `heroic` > '0'"));
	$avg_raid1_m = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_m` FROM `" .$_SESSION['table']. "_" .$id. "_raid_1` WHERE `mythic` > '0'"));
	$avg_raid2_hc = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_hc` FROM `" .$_SESSION['table']. "_" .$id. "_raid_2` WHERE `heroic` > '0'"));
	$avg_raid2_m = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_m` FROM `" .$_SESSION['table']. "_" .$id. "_raid_2` WHERE `mythic` > '0'"));
	$avg_raid3_hc = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_hc` FROM `" .$_SESSION['table']. "_" .$id. "_raid_3` WHERE `heroic` > '0'"));
	$avg_raid3_m = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_m` FROM `" .$_SESSION['table']. "_" .$id. "_raid_3` WHERE `mythic` > '0'"));
	$avg_raid4_hc = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_hc` FROM `" .$_SESSION['table']. "_" .$id. "_raid_4` WHERE `heroic` > '0'"));
	$avg_raid4_m = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_m` FROM `" .$_SESSION['table']. "_" .$id. "_raid_5` WHERE `mythic` > '0'"));
	array_push($en_hc_array, $avg_raid1_hc['en_hc']);
	array_push($en_m_array, $avg_raid1_m['en_m']);
	array_push($tov_hc_array, $avg_raid2_hc['tov_hc']);
	array_push($tov_m_array, $avg_raid2_m['tov_m']);
	array_push($nh_hc_array, $avg_raid3_hc['nh_hc']);
	array_push($nh_m_array, $avg_raid3_m['nh_m']);
	array_push($tos_hc_array, $avg_raid4_hc['tos_hc']);
	array_push($tos_m_array, $avg_raid4_m['tos_m']);	
	$mythic_cap = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`mythic`) AS `mythic` FROM `" .$_SESSION['table']. "_" .$id. "_dungeons"));
	array_push($mythic_array, $mythic_cap['mythic']);
}

$sql = substr($sql, '0', '-11');

$cap_array = array('`ilvl_on`', '`ilvl_off`', '`alvl`', '`ap`', '`wq`', '`eq`');

foreach($cap_array as $cap) {
	if($cap == '`ilvl_on`') {
		$add = " ORDER BY " .$cap. " DESC LIMIT 1";
		$query = $sql.$add;
		$query = mysqli_fetch_array(mysqli_query($stream, $query));
		$itemlevel_equipped_cap = $query['ilvl_on'];
	}
	elseif($cap == '`ilvl_off`') {
		$add = " ORDER BY " .$cap. " DESC LIMIT 1";
		$query = $sql.$add;
		$query = mysqli_fetch_array(mysqli_query($stream, $query));
		$itemlevel_bags_cap = $query['ilvl_off'];		
	}	
	elseif($cap == '`alvl`') {
		$add = " ORDER BY " .$cap. " DESC LIMIT 1";
		$query = $sql.$add;
		$query = mysqli_fetch_array(mysqli_query($stream, $query));
		$alvl_cap = $query['alvl'];
	}	
	elseif($cap == '`ap`') {
		$add = " ORDER BY " .$cap. " DESC LIMIT 1";
		$query = $sql.$add;
		$query = mysqli_fetch_array(mysqli_query($stream, $query));
		$ap_cap = $query['ap'];
	}	
	elseif($cap == '`wq`') {
		$add = " ORDER BY " .$cap. " DESC LIMIT 1";
		$query = $sql.$add;
		$query = mysqli_fetch_array(mysqli_query($stream, $query));
		$wq_cap = $query['wq'];
	}
}

rsort($mythic_array);

$eq_avg = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`eq`) AS `eq_sum` FROM `" .$table_name. "` WHERE `status` = '0'"));
$eq_cap = mysqli_fetch_array(mysqli_query($stream, "SELECT `eq` AS `eq_cap` FROM `" .$table_name. "` WHERE `status` = '0' ORDER BY `eq` DESC LIMIT 1"));

// AP READABILITY CONVERTER
	if(strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) <= '3') {
		$ap_arraysum = number_format(array_sum($ap_array)/$_SESSION['tracked']);
	}
	elseif(strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) > '3' && strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) < '7') {
		$ap_arraysum = '' .number_format(array_sum($ap_array)/$_SESSION['tracked']/1000). ' K';
	}
	elseif(strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) > '6' && strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) < '10') {
		$ap_arraysum = '' .number_format(array_sum($ap_array)/$_SESSION['tracked']/1000000). ' M';
	}
	elseif(strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) > '10' && strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) < '14') {
		$ap_arraysum = '' .number_format(array_sum($ap_array)/$_SESSION['tracked']/1000000000). ' B';
	}
	elseif(strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) > '14' && strlen(round(array_sum($ap_array)/$_SESSION['tracked'], 0)) < '18') {
		$ap_arraysum = '' .number_format(array_sum($ap_array)/$_SESSION['tracked']/1000000000000). ' T';
	}

$active_chars = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) as `active` FROM `" .$table_name. "` WHERE `status` = '0'"));

echo '<div id="roster" style="width: 100%; height: 60%; padding-top: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); min-width: 1563px;">
' .$error. '
<span style="color: orange; text-align: center; font-size: 20px;">Current Roster</span>
<div>
<table style="margin: 0 auto; margin-top: 15px; width: 100%; min-width: 1500px;">
<thead>
<tr>
	<th></th>
	<th>Updated</th>
	<th></th>
	<th>Logout</th>
	<th>Roles</th>
	<th>Spec</th>
	<th>Talents</th>
	<th>Legendaries</th>
	<th>Itemlevel</th>	
	<th><span title="Artifact Power">AP</span></th>
	<th><span title="Artifact Level">AL</span> <span title="Artifact Knowledge">(AK)</span></th>
	<th>Mythics<br />(M+ Achv)</th>
	<th>World Quests</th>
	<th><a href="?eq" title="What is EQ?">EQ</th>
	<th><span title="Emerald Nightmare">EN</span><br />
		HC M
	</th>
	<th><span title="Trial of Valor">ToV</span><br />
		HC M
	</th>
	<th><span title="The Nighthold">NH</span><br />
		HC M
	</th>
	<th><span title="Tomb of Sargeras">ToS</span><br />
		HC M
	</th>
	<th></th>';
	
	// GUEST = VIEW ONLY
	if($_SESSION['guest'] != 'guest') {
		echo '<th></th>';
	}
	echo '
</tr>
</thead>
<tbody>
<tr style="border-bottom: 1px solid white;">
	<td><i>' .$active_chars['active']. ' characters</i></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><i>' .round(array_sum($itemlevel_array)/$active_chars['active'], 2). ' (' .round(array_sum($itemlevel_off_array)/$active_chars['active'], 2). ')</i></td>
	<td><i title="7.1 must have: ' .round((((array_sum($ap_array)/$active_chars['active'])/65310778)*100), 2). '% | 7.2 must have: ' .round((((array_sum($ap_array)/$active_chars['active'])/2228766330)*100), 2). '%">' .$ap_arraysum. '</i></td>
	<td><i>' .round(array_sum($alvl_array)/$active_chars['active'], 2). ' (' .round(array_sum($ak_array)/$active_chars['active'], 2). ')</i></td>
	<td><i>' .round(array_sum($mythic_array)/$active_chars['active'], 0). '</i></td>
	<td><i>' .round(array_sum($wq_array)/$active_chars['active'], 0). '</i></td>
	<td><i>' .round($eq_avg['eq_sum']/$active_chars['active'], 0). '</i></td>
	<td><i>' .round(array_sum($en_hc_array)/$active_chars['active'], 1). ' | ' .round(array_sum($en_m_array)/$active_chars['active'], 1). '</i></td>
	<td><i>' .round(array_sum($tov_hc_array)/$active_chars['active'], 1). ' | ' .round(array_sum($tov_m_array)/$active_chars['active'], 1). '</i></td>
	<td><i>' .round(array_sum($nh_hc_array)/$active_chars['active'], 1). ' | ' .round(array_sum($nh_m_array)/$active_chars['active'], 1). '</i></td>
	<td><i>' .round(array_sum($tos_hc_array)/$active_chars['active'], 1). ' | ' .round(array_sum($tos_m_array)/$active_chars['active'], 1). '</i></td>
	<td></td>
	<td></td>
</tr>';

// BUILD ROSTER TABLE
$fetch_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `status` = '0' ORDER BY `name` ASC");

while($id = mysqli_fetch_array($fetch_ids)) {
	
	// GUILD TABLE INFORMATION
	$guild_table = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$table_name. "` WHERE `id` = '" .$id['id']. "'"));
	// GENERAL DATA FROM INDIVIDUAL TABLE
	$fetch_general_data = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$id['id']. "_general`"));
	// DUNGEON DATA FROM INDIVIDUAL TABLE
	$fetch_dungeon_data = mysqli_fetch_array(mysqli_query($stream, "SELECT SUM(`mythic`) AS `mythic` FROM `" .$_SESSION['table']. "_" .$id['id']. "_dungeons`"));
	// CLASS COLOR
	$class_color = mysqli_fetch_array(mysqli_query($stream, "SELECT `class`, `color` FROM `ovw_classes` WHERE `id` = '" .$guild_table['class']. "'"));
	// SPEC CONVERTER
	$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `ovw_weapons` WHERE `id` = '" .$guild_table['spec']. "'"));
	// LEGENDARY AMOUNT
	$legendaries = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `amount` FROM `" .$_SESSION['table']. "_" .$id['id']. "_legendaries`"));
			
	// RAID PROGRESS
	$en_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_1` WHERE `heroic` > '0'"));
	$en_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `en_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_1` WHERE `mythic` > '0'"));
	$tov_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_2` WHERE `heroic` > '0'"));
	$tov_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tov_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_2` WHERE `mythic` > '0'"));
	$nh_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_3` WHERE `heroic` > '0'"));
	$nh_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `nh_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_3` WHERE `mythic` > '0'"));
	$tos_heroic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_hc` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_4` WHERE `heroic` > '0'"));
	$tos_mythic_progress = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tos_m` FROM `" .$_SESSION['table']. "_" .$id['id']. "_raid_4` WHERE `mythic` > '0'"));
		
	// ARTIFACT LEVEL COLORIZATION DEPENDING ON ARTIFACT KNOWLEDGE
	// PRE 7.2
	if($fetch_general_data['ak'] <= '25') {
		if($fetch_general_data['alvl'] <= '35') {
			$alvl_color = 'coral';
		}
		elseif($fetch_general_data['alvl'] > '35' && $fetch_general_data['alvl'] < '54') {
			$alvl_color = 'orange';
		}
		elseif($fetch_general_data['alvl'] == '54') {
			$alvl_color = 'yellowgreen';
		}			
	}
	// POST 7.2
	elseif($fetch_general_data['ak'] > '25') {
		if($fetch_general_data['alvl'] <= '35') {
			$alvl_color = 'coral';
		}
		elseif($fetch_general_data['alvl'] > '35' && $fetch_general_data['alvl'] < '52') {
			$alvl_color = 'orange';
		}
		elseif($fetch_general_data['alvl'] == '52') {
			$alvl_color = 'yellowgreen';
		}
	}
	
	$artifact_level = '<span style="color: ' .$alvl_color. ';">' .$fetch_general_data['alvl']. '</span>';
		
	// ARTIFACT KNOWLEDGE LEVEL PROGRESS DEPENDING ON VALUE
	// PRE 7.2
	if($fetch_general_data['ak'] <= '25') {
		if($fetch_general_data['ak'] <= '12') {
			$ak_color = 'coral';
		}
		elseif($fetch_general_data['ak'] > '12' && $fetch_general_data['ak'] < '25') {
			$ak_color = 'orange';
		}
		elseif($fetch_general_data['ak'] == '25') {
			$ak_color = 'yellowgreen';
		}
	}
	// POST 7.2
	elseif($fetch_general_data['ak'] > '25') {
		if($fetch_general_data['ak'] <= '26') {
			$ak_color = 'coral';
		}
		elseif($fetch_general_data['ak'] > '26' && $fetch_general_data['ak'] < '50') {
			$ak_color = 'orange';
		}
		elseif($fetch_general_data['ak'] == '50') {
			$ak_color = 'yellowgreen';
		}
	}
	
	$artifact_knowledge = '<span style="color: ' .$ak_color. ';">' .$fetch_general_data['ak']. '</span>';
			
	// MYTHIC PLUS ACHIEVEMENT THRESHOLD CONVERTER
	if($fetch_general_data['m_achv'] <= '2') {
		$mplus_color = 'coral';
	}
	elseif($fetch_general_data['m_achv'] > '2' && $fetch_general_data['m_achv'] <= '5') {
		$mplus_color = 'orange';
	}
	elseif($fetch_general_data['m_achv'] > '5' && $fetch_general_data['m_achv'] <= '10') {
		$mplus_color = 'yellow';
	}
	elseif($fetch_general_data['m_achv'] == '15') {
		$mplus_color = 'yellowgreen';
	}
	$m_achievement = '<span style="color: ' .$mplus_color. ';">(' .$fetch_general_data['m_achv']. ')</span>';

	// ROLE1 CONVERTER VISUAL
	switch ($guild_table['role1']) {
		case '0':
			$role1 = '<img src="img/dps.png" alt="404" width="21px" title="Primary: Melee DPS" />';
			break;
		case '1':
			$role1 = '<img src="img/rdps.png" alt="404" width="21px" title="Primary: Ranged DPS" />';
			break;
		case '2':
			$role1 = '<img src="img/tank.png" alt="404" width="21px" title="Primary: Tank" />';
			break;
		case '3':
			$role1 = '<img src="img/heal.png" alt="404" width="21px" title="Primary: Heal" />';
			break;					
	}

	// ROLE2 CONVERTER VISUAL
	switch ($guild_table['role2']) {
		case '0':
			$role2 = '<img src="img/dps.png" alt="404" width="21px" title="Secondary: Melee DPS" />';
			break;
		case '1':
			$role2 = '<img src="img/rdps.png" alt="404" width="21px" title="Secondary: Ranged DPS" />';
			break;
		case '2':
			$role2 = '<img src="img/tank.png" alt="404" width="21px" title="Secondary: Tank" />';
			break;
		case '3':
			$role2 = '<img src="img/heal.png" alt="404" width="21px" title="Secondary: Heal" />';
			break;
		case '4':
			$role2 = '';
			break;
	}
			
	// COLORIZATION OF INDIVIDUAL EMERALD NIGHTMARE PROGRESS			
	if($en_heroic_progress['en_hc'] == '7') {
		$en_hc_color = 'yellowgreen';
	}
	elseif($en_heroic_progress['en_hc'] > '0' && $en_heroic_progress['en_hc'] < '7') {
		$en_hc_color = 'orange';
	}
	elseif($en_heroic_progress['en_hc'] == '0') {
		$en_hc_color = 'coral';
	}
	$en_hc = '<span style="color: ' .$en_hc_color. ';">' .$en_heroic_progress['en_hc']. ' |</span>';
	
	if($en_mythic_progress['en_m'] == '7') {
		$en_m_color = 'yellowgreen';
	}
	elseif($en_mythic_progress['en_m'] > '0' && $en_mythic_progress['en_m'] < '7') {
		$en_m_color = 'orange';
	}
	elseif($en_mythic_progress['en_m'] == '0') {
		$en_m_color = 'coral';
	}
	$en_m = '<span style="color: ' .$en_m_color. ';">' .$en_mythic_progress['en_m']. '</span>';
	
	// COLORIZATION OF INDIVIDUAL TRIAL OF VALOR PROGRESS
	if($tov_heroic_progress['tov_hc'] == '3') {
		$tov_hc_color = 'yellowgreen';
	}
	elseif($tov_heroic_progress['tov_hc'] > '0' && $tov_heroic_progress['tov_hc'] < '3') {
		$tov_hc_color = 'orange';
	}
	elseif($tov_heroic_progress['tov_hc'] == '0') {
		$tov_hc_color = 'coral';
	}
	$tov_hc = '<span style="color: ' .$tov_hc_color. ';">' .$tov_heroic_progress['tov_hc']. ' |</span>';
			
	if($tov_mythic_progress['tov_m'] == '3') {
		$tov_m_color = 'yellowgreen';
	}
	elseif($tov_mythic_progress['tov_m'] > '0' && $tov_mythic_progress['tov_m'] < '3') {
		$tov_m_color = 'orange';
	}
	elseif($tov_mythic_progress['tov_m'] == '0') {
		$tov_m_color = 'coral';
	}
	$tov_m = '<span style="color: ' .$tov_m_color. ';">' .$tov_mythic_progress['tov_m']. '</span>';
			
	// COLORIZATION OF INDIVIDUAL NIGHTHOLD PROGRESS
	if($nh_heroic_progress['nh_hc'] == '10') {
		$nh_hc_color = 'yellowgreen';
	}
	elseif($nh_heroic_progress['nh_hc'] > '0' && $nh_heroic_progress['nh_hc'] < '10') {
		$nh_hc_color = 'orange';
	}
	elseif($nh_heroic_progress['nh_hc'] == '0') {
		$nh_hc_color = 'coral';
	}
	$nh_hc = '<span style="color: ' .$nh_hc_color. ';">' .$nh_heroic_progress['nh_hc']. ' |</span>';
	
	if($nh_mythic_progress['nh_m'] == '10') {
		$nh_m_color = 'yellowgreen';
	}
	elseif($nh_mythic_progress['nh_m'] > '0' && $nh_mythic_progress['nh_m'] < '10') {
		$nh_m_color = 'orange';
	}
	elseif($nh_mythic_progress['nh_m'] == '0') {
		$nh_m_color = 'coral';
	}
	$nh_m = '<span style="color: ' .$nh_m_color. ';">' .$nh_mythic_progress['nh_m']. '</span>';
	
	// COLORIZATION OF INDIVIDUAL TOMB OF SARGERAS PROGRESS
	if($tos_heroic_progress['tos_hc'] == '9') {
		$tos_hc_color = 'yellowgreen';
	}
	elseif($tos_heroic_progress['tos_hc'] > '0' && $tos_heroic_progress['tos_hc'] < '9') {
		$tos_hc_color = 'orange';
	}
	elseif($tos_heroic_progress['tos_hc'] == '0') {
		$tos_hc_color = 'coral';
	}
	$tos_hc = '<span style="color: ' .$tos_hc_color. ';">' .$tos_heroic_progress['tos_hc']. ' |</span>';
	
	if($tos_mythic_progress['tos_m'] == '9') {
		$tos_m_color = 'yellowgreen';
	}
	elseif($tos_mythic_progress['tos_m'] > '0' && $tos_mythic_progress['tos_m'] < '9') {
		$tos_m_color = 'orange';
	}
	elseif($tos_mythic_progress['tos_m'] == '0') {
		$tos_m_color = 'coral';
	}
	$tos_m = '<span style="color: ' .$tos_m_color. ';">' .$tos_mythic_progress['tos_m']. '</span>';
	
	
	// UPDATED TIMER CONVERTER
	if(time('now')-$guild_table['updated'] <= '86400') {
		$last_update = '<span style="color: yellowgreen;">' .round(((time('now')-$guild_table['updated'])/3600), 2). ' hrs ago</span>';
	}
	elseif(time('now')-$guild_table['updated'] > '86400') {
			$last_update = '<span style="color: coral;">' .round(((time('now')-$guild_table['updated'])/3600), 2). ' hrs ago</span>';
		}
	
	// LOGGED OUT TIMER CONVERTER
	if(time('now')-$guild_table['logout'] <= '86400') {
		$last_logout = '' .round(((time('now')-$guild_table['logout'])/3600), 2). ' hrs ago';
	}
	elseif(time('now')-$guild_table['logout'] > '86400') {
		if($guild_table['logout'] >= '86400' && $guild_table['logout'] < '172800') {
			$last_logout = '' .round(((time('now')-$guild_table['logout'])/86400), 2). ' day ago';
		}
		elseif($guild_table['logout'] >= '172800') {
			$last_logout = '' .round(((time('now')-$guild_table['logout'])/86400), 2). ' days ago';
		}
	}

	// AP READABILITY CONVERTER
	if(strlen($fetch_general_data['ap']) <= '3') {
		$ap = number_format($fetch_general_data['ap']);
	}
	elseif(strlen($fetch_general_data['ap']) > '3' && strlen($fetch_general_data['ap']) < '7') {
		$ap = '' .number_format($fetch_general_data['ap']/1000). ' K';
	}
	elseif(strlen($fetch_general_data['ap']) >= '7' && strlen($fetch_general_data['ap']) < '10') {
		$ap = '' .number_format($fetch_general_data['ap']/1000000). ' M';
	}
	elseif(strlen($fetch_general_data['ap']) >= '10' && strlen($fetch_general_data['ap']) < '14') {
		$ap = '' .number_format($fetch_general_data['ap']/1000000000). ' B';
	}
	elseif(strlen($fetch_general_data['ap']) >= '14' && strlen($fetch_general_data['ap']) < '18') {
		$ap = '' .number_format($fetch_general_data['ap']/1000000000000). ' T';
	}

	// TOPLIST CHECK
	if($fetch_general_data['ilvl_on'] == $itemlevel_equipped_cap) {
		$fetch_general_data['ilvl_on'] = '<span style="color: yellowgreen;">' .$fetch_general_data['ilvl_on']. '</span>';
	}
	if($fetch_general_data['ilvl_off'] == $itemlevel_bags_cap) {
		$fetch_general_data['ilvl_off'] = '<span style="color: yellowgreen;">(' .$fetch_general_data['ilvl_off']. ')</span>';
	}
	elseif($fetch_general_data['ilvl_off'] != $itemlevel_bags_cap) {
		$fetch_general_data['ilvl_off'] = '(' .$fetch_general_data['ilvl_off']. ')';
	}
	if($fetch_general_data['wq'] == $wq_cap) {
		$fetch_general_data['wq'] = '<span style="color: yellowgreen;">' .$fetch_general_data['wq']. '</span>';
	}
	if($fetch_general_data['ap'] == $ap_cap) {
		$ap = '<span style="color: yellowgreen;">' .$ap. '</span>';
	}
	if($guild_table['eq'] == $eq_cap['eq_cap']) {
		$eq = '<span style="color: yellowgreen;">' .$guild_table['eq']. '</span>';
	}
	elseif($fetch_general_data['eq'] != $eq_cap['eq_cap']) {
		$eq = $guild_table['eq'];
	}
	
	// MUST-HAVE-AP THRESHOLD CONVERTER
	if($guild_table['class'] == '11') {
			
		if($fetch_general_data['ak'] <= '25') {
			$threshold = '261243112';
		}
		elseif($fetch_general_data['ak'] > '25') {
			$threshold = '8915065320';
		}
		
		$ap_progress = '' .(round($fetch_general_data['ap']/$threshold, 4)*100). '%';
	}
	elseif($guild_table['class'] == '12') {
		
		if($fetch_general_data['ak'] <= '25') {
			$threshold = '130621556';
		}
		elseif($fetch_general_data['ak'] > '25') {
			$threshold = '522486224';
		}
		
		$ap_progress = '' .(round($fetch_general_data['ap']/$threshold, 4)*100). '%';
	}
	elseif($guild_table['class'] != '11' && $guild_table['class'] != '12') {
		
		if($fetch_general_data['ak'] <= '25') {
			$threshold = '195932334';
		}
		elseif($fetch_general_data['ak'] > '25') {
			$threshold = '783729336';
		}
		
		$ap_progress = '' .(round($fetch_general_data['ap']/$threshold, 4)*100). '%';
	}
	
	if($fetch_dungeon_data['mythic'] == $mythic_array['0']) {
		$mythics = '<span style="color: yellowgreen;">' .$fetch_dungeon_data['mythic']. '</span>';
	}
	elseif($fetch_dungeon_data['mythic'] != $mythic_array['0']) {
		$mythics = $fetch_dungeon_data['mythic'];
	}
	
	// ACTUAL TABLE ROW CONTENT
	echo '
	<tr class="' .$id['id']. '" onclick="select(this.className);">
		<td class="name' .$id['id']. '" style="background-color: ' .$class_color['color']. ';"><a style="min-width: 90px;" href="?inspect=' .$id['id']. '" title="Inspect ' .$guild_table['name']. '">' .$guild_table['name']. '</a></td>
		<td>' .$last_update. '</td>
		<td><img src="img/update.png" alt="404" title="Update ' .$guild_table['name']. '" style="width: 21px;" onclick="update(this.id);" id="' .$id['id']. '" class="still' .$id['id']. '" /></td>
		<td>' .$last_logout. '</td>
		<td><a href="?change_role=' .$id['id']. '">' .$role1. ' ' .$role2. '</a></td>
		<td>' .$spec['spec']. '</td>
		<td><a href="http://eu.battle.net/wow/en/tool/talent-calculator#' .$guild_table['talents']. '" title="WoW Talent Calculator">Calc</a></td>
		<td><a href="?edit_legendaries=' .$id['id']. '" title="Edit legendaries">' .$legendaries['amount']. '</a></td>
		<td>' .$fetch_general_data['ilvl_on']. ' ' .$fetch_general_data['ilvl_off']. '</td>		
		<td><span title="' .number_format($fetch_general_data['ap']). '">' .$ap. '</span> <span title="percentage of possible max. AP for this class">(' .$ap_progress. ')</span></td>
		<td>' .$artifact_level. ' (' .$artifact_knowledge. ')</td>
		<td style="min-width: 80px;">' .$mythics. ' ' .$m_achievement. '</td>
		<td>' .$fetch_general_data['wq']. '</td>
		<td>' .$eq. '</td>
		<td>' .$en_hc. '  ' .$en_m. '</td>
		<td>' .$tov_hc. ' ' .$tov_m. '</td>
		<td>' .$nh_hc. ' ' .$nh_m. '</td>
		<td>' .$tos_hc. ' ' .$tos_m. '</td>
		<td><a href="?inspect=' .$id['id']. '"><img src="img/inspect.png" alt="404" title="Inspect ' .$guild_table['name']. '" style="width: 21px;" /></a></td>';
		// GUEST = VIEW ONLY
		if($_SESSION['guest'] != 'guest') {
			echo '<td><a href="?bench=' .$id['id']. '"><img src="img/bench.png" alt="404" title="Bench ' .$guild_table['name']. '" style="width: 21px;" /></a></td>';
		}
		echo '
	</tr>';
}
		
echo '</tbody>
</table>
</div>
</div>';
		
// LEFT COLUMN BOTTOM
include('bench.php');

// SIDEBAR
include('sidebar.php');

?>