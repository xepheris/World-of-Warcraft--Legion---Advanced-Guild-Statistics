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

$itemlevels = mysqli_fetch_array(mysqli_query($stream, "SELECT `ilvl_on`, `ilvl_off` FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_general`"));

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;" class="inspect">
<span style="color: orange; font-size: 20px;">Current Equipment (' .$itemlevels['ilvl_on']. '/' .$itemlevels['ilvl_off']. ')</span>
<br />
<span style="color: black; font-size: 10px;">legendaries dropped after 7.1.5 are currently bugged and show as 910 which reduces itemlevel averages</span>
<table style="margin: 0 auto; text-align: left; margin-top: 15px; border-bottom: 1px solid white; width: 100%;">
<tbody>';
		
$slots = array('1' => 'Head', '2' => 'Neck', '3' => 'Shoulders', '4' => 'Back', '5' => 'Chest', '6' => 'Wrists', '7' => 'Hands', '8' => 'Waist', '9' => 'Legs', '10' => 'Feet', '11' => 'Finger1', '12' => 'Finger2', '13' => 'Trinket1', '14' => 'Trinket2');
$weapon = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_weapons`"));
	
foreach($slots as $id => $slot) {
	$item_info = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_equip` WHERE `id` = '" .$id. "'"));
					
	// GEM CHECK
	if((strpos($item_info['bonus'], '1808') !== FALSE) && ($item_info['gem'] == '0')) {
		$gem = '<img src="img/mg.png" title="missing gem" alt="missing gem" />';
	}
	elseif($item_info['gem'] != '0') {
		$gem = '<a href="http://wowhead.com/?item=' .$item_info['gem']. '">' .$item_info['gem']. '</a>';
	}
			
	$enchantable = array('2', '3', '4', '11', '12');
			
	if(in_array($id, $enchantable) && $item_info['enchant'] == '0') {
		$enchant = '<img src="img/me.png" title="missing enchant" alt="missing gem" />';
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
		<td><a href="http://wowhead.com/?item=' .$item_info['itemid']. '&bonus=' .$item_info['bonus']. '" rel="ench=' .$item_info['enchant']. '&gems=' .$item_info['gem']. '">' .$item_info['itemid']. '</a></td>
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