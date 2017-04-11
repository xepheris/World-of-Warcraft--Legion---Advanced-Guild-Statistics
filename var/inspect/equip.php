<?php

echo '<script defer src="http://wow.zamimg.com/widgets/power.js"></script>
<script>
		var wowhead_tooltips = {
		iconizelinks: true,
		renamelinks: true,
		droppedby: true,
			"hide": {
			"dropchance": true,
			"sellprice": true,
			"maxstack": true,
		}
	}
</script>';

$class = mysqli_fetch_array(mysqli_query($stream, "SELECT `class` FROM `" .$table_name. "` WHERE `id` = '" .$_GET['inspect']. "'"));
$class = $class['class'];

switch ($class) {
	case 1:
		$t19 = array(138351, 138354, 138357, 138360, 138363, 138374);
		$t20 = array(147187, 147188, 147189, 147190, 147191, 147192);
		break;
	case 2:
		$t19 = array(138350, 138353, 138356, 138359, 138362, 138369);
		$t20 = array(147157, 147158, 147159, 147160, 147161, 147162);
		break;
	case 3:
		$t19 = array(138339, 138340, 138342, 138344, 138347, 138368);
		$t20 = array(147139, 147140, 147141, 147142, 147143, 147144);
		break;
	case 4:
		$t19 = array(138326, 138329, 138332, 138335, 138338, 138371);
		$t20 = array(147169, 147170, 147171, 147172, 147173, 147174);
		break;
	case 5:
		$t19 = array(138310, 138313, 138316, 138319, 138322, 138370);
		$t20 = array(147163, 147164, 147165, 147166, 147167, 147168);
		break;
	case 6:
		$t19 = array(138349, 138352, 138355, 138358, 138361, 138364);
		$t20 = array(147121, 147122, 147123, 147124, 147125, 147126);
		break;
	case 7:
		$t19 = array(138341, 138343, 138345, 138346, 138348, 138372);
		$t20 = array(147175, 147176, 147177, 147178, 147179, 147180);
		break;
	case 8:
		$t19 = array(138309, 138312, 138315, 138318, 138321, 138365);
		$t20 = array(147145, 147146, 147147, 147148, 147149, 147150);
		break;
	case 9:
		$t19 = array(138311, 138314, 138317, 138320, 137323, 138373);
		$t20 = array(147181, 147182, 147183, 147184, 147185, 147186);
		break;
	case 10:
		$t19 = array(138325, 138328, 138331, 138334, 138337, 138367);
		$t20 = array(147151, 147152, 147153, 147154, 147155, 147156);
		break;
	case 11:
		$t19 = array(138324, 138327, 138330, 138333, 138336, 138366);
		$t20 = array(147133, 147134, 147135, 147136, 147137, 147138);
		break;
	case 12:
		$t19 = array(138375, 138376, 138377, 138378, 138379, 138380);
		$t20 = array(147127, 147128, 147129, 147130, 147131, 147132);
}

$t19_pcs = array();
$t20_pcs = array();

for($i = '1'; $i <= '14'; $i++) {
	$id = mysqli_fetch_array(mysqli_query($stream, "SELECT `itemid` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_equip` WHERE `id` = '" .$i. "'"));
	
	if(in_array($id['itemid'], $t19)) {
		array_push($t19_pcs, $id['itemid']);
	}
	if(in_array($id['itemid'], $t20)) {
		array_push($t20_pcs, $id['itemid']);
	}
}

if((count($t19_pcs) > 0) || (count($t20_pcs) > 0)) {
	$set = '' .$t19_pcs['0']. ':' .$t19_pcs['1']. ':' .$t19_pcs['2']. ':' .$t19_pcs['3']. ':' .$t19_pcs['4']. ':' .$t19_pcs['5']. ':' .$t20_pcs['0']. ':' .$t20_pcs['1']. ':' .$t20_pcs['2']. ':' .$t20_pcs['3']. ':' .$t20_pcs['4']. ':' .$t20_pcs['5']. '';
}

$itemlevels = mysqli_fetch_array(mysqli_query($stream, "SELECT `ilvl_on`, `ilvl_off` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_general`"));

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect" id="equip">
<span style="color: orange; font-size: 20px;">Current Equipment (' .$itemlevels['ilvl_on']. '/' .$itemlevels['ilvl_off']. ')</span>
<br />
<table style="margin: 0 auto; text-align: left; margin-top: 15px; border-bottom: 1px solid white; width: 100%;">
<tbody>';
		
$slots = array('1' => 'Head', '2' => 'Neck', '3' => 'Shoulders', '4' => 'Back', '5' => 'Chest', '6' => 'Wrists', '7' => 'Hands', '8' => 'Waist', '9' => 'Legs', '10' => 'Feet', '11' => 'Finger1', '12' => 'Finger2', '13' => 'Trinket1', '14' => 'Trinket2');
$weapon = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_weapons`"));
	
foreach($slots as $id => $slot) {
	$item_info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_equip` WHERE `id` = '" .$id. "'"));
					
	// GEM CHECK
	if((strpos($item_info['bonus'], '1808') !== FALSE) && ($item_info['gem'] == '0')) {
		$gem = '<a style="background: transparent url(img/mg.png) no-repeat scroll left center;"><span style="margin-left: 18px; color: coral;">missing gem</span></a>';
	}
	elseif($item_info['gem'] != '0') {
		$gem = '<a href="http://wowhead.com/?item=' .$item_info['gem']. '">' .$item_info['gem']. '</a>';
	}
			
	$enchantable = array('2', '3', '4', '11', '12');
			
	if(in_array($id, $enchantable) && $item_info['enchant'] == '0') {
		$enchant = '<a style="background: transparent url(img/me.png) no-repeat scroll left center;"><span style="margin-left: 18px; color: coral;">missing enchant</span></a>';
	}
	elseif($item_info['enchant'] != '0') {
		$conversion = mysqli_fetch_array(mysqli_query($stream, "SELECT `wowhead_id` FROM `ovw_enchants` WHERE `enchant_id` = '" .$item_info['enchant']. "'"));
		$gem = '<a href="http://wowhead.com/?item=' .$conversion['wowhead_id']. '">' .$conversion['wowhead_id']. '</a>';
	}
			
	if($item_info['itemlevel'] == '940') {
		$rarity = '#ff8000';
	}
			
	// ENCHANT CHECK
	
	echo '<tr>
		<td>' .$slot. '</td>
		<td><a href="http://wowhead.com/?item=' .$item_info['itemid']. '&bonus=' .$item_info['bonus']. '" rel="ench=' .$item_info['enchant']. '&gems=' .$item_info['gem']. '&pcs=' .$set. '">' .$item_info['itemid']. '</a></td>
		<td><span style="color: ' .$rarity. ';">' .$item_info['itemlevel']. '</span></td>
		<td>' .$enchant. ' ' .$gem. ' </td>
	</tr>';
			
	unset($rarity); unset($enchant); unset($gem);
}		
				
echo '</tbody>
</table>
<table style="margin: 0 auto; text-align: center; width: 100%;">
<tbody>
<tr>
	<td colspan="3"><a href="http://wowhead.com/?item=' .$weapon['item_id']. '&bonus=' .$weapon['bonus']. '" rel="gems=' .$weapon['r1']. ':' .$weapon['r2']. ':' .$weapon['r3']. '">' .$weapon['item_id']. '</a> (' .$weapon['itemlevel']. ')</td>
</tr>
<tr>';

for($i = '1'; $i <= '3'; $i++) {
	if($weapon['r' .$i. ''] != '0') {
		echo '<td><a href="http://wowhead.com/?item=' .$weapon['r' .$i. '']. '" rel="bonus=' .$weapon['bonus_r' .$i. '']. '">' .$weapon['r' .$i. '']. '</a></td>';
	}
	elseif($weapon['r' .$i. ''] == '0') {
		echo '<td>no relic in slot ' .$i. '</td>';
	}
}

echo '</tr>		
</tbody>
</table>
</div>';
		
?>