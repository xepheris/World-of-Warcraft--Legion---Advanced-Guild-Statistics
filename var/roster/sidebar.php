<?php

// RIGHT COLUMN TOP		
echo '<div style="width: auto; height: 82.434vh; padding-top: 15px; float: right; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-right: 5%; min-height: 575px; padding-bottom: 15px;">
<ul style="list-style-type: none; text-align: left; padding-left: 15px; padding-right: 12px;">';
		
$classes = array('1' => 'Warrior', '2' => 'Paladin', '3' => 'Hunter', '4' => 'Rogue', '5' => 'Priest', '6' => 'Death Knight', '7' => 'Shaman', '8' => 'Mage', '9' => 'Warlock', '10' => 'Monk', '11' => 'Druid', '12' => 'Demon Hunter');
foreach($classes as $id => $class) {
	$amount = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `class` FROM `" .$table_name. "` WHERE `class` = '" .$id. "' AND `status` = '0'"));
	echo '<li><img src="img/' .$id. '.png" title="' .$class. '" alt="404" /> ' .$amount['class']. '</li>';
}
		
$count_cloth = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `cloth` FROM `" .$table_name. "` WHERE `class` = '5' OR `class` = '8' OR `class` = '9'"));		
$count_leather = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `leather` FROM `" .$table_name. "` WHERE `class` = '4' OR `class` = '10' OR `class` = '11' OR `class` = '12'"));
$count_mail = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `mail` FROM `" .$table_name. "` WHERE `class` = '3' OR `class` = '7'"));
$count_plate = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `plate` FROM `" .$table_name. "` WHERE `class` = '1' OR `class` = '2' OR `class` = '6'"));
		
echo '
<hr>
<li><img src="img/cloth.png" alt="404" title="cloth users"/> ' .$count_cloth['cloth']. '</li>
<li><img src="img/leather.png" alt="404" title="leather users" /> ' .$count_leather['leather']. '</li>
<li><img src="img/mail.png" alt="404" title="mail users" /> ' .$count_mail['mail']. '</li>
<li><img src="img/plate.png" alt="404" title="plate users" /> ' .$count_plate['plate']. '</li>';
		
$count_mdps = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `mdps` FROM `" .$table_name. "` WHERE `role1` = '0'  AND `status` = '0' AND (`class` = '1' OR `class` = '2' OR `class` = '3' OR `class` = '4' OR `class` = '6' OR `class` = '7' OR `class` = '10' OR `class` = '11' OR `class` = '12')"));
$count_rdps = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `rdps` FROM `" .$table_name. "` WHERE `role1` = '1' AND `status` = '0' AND (`class` = '3' OR `class` = '5' OR `class` = '7' OR `class` = '8' OR `class` = '9' OR `class` = '11')"));
$count_tank = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `tank` FROM `" .$table_name. "` WHERE `role1` = '2' AND `status` = '0'"));
$count_heal = mysqli_fetch_array(mysqli_query($stream, "SELECT COUNT(`id`) AS `heal` FROM `" .$table_name. "` WHERE `role1` = '3' AND `status` = '0'"));
		
echo '
<hr>
<li><img src="img/dps.png" alt="404" width="21px" title="Primary: Melee DPS" /> ' .$count_mdps['mdps']. '</li>
<li><img src="img/rdps.png" alt="404" width="21px" title="Primary: Ranged DPS" /> ' .$count_rdps['rdps']. '</li>
<li><img src="img/tank.png" alt="404" width="21px" title="Primary: Tank" /> ' .$count_tank['tank']. '</li>
<li><img src="img/heal.png" alt="404" width="21px" title="Primary: Heal" /> ' .$count_heal['heal']. '</li>
</ul>		
</div>';

?>