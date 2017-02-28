<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">
<span style="color: orange; font-size: 20px;">Dungeons</span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>
	<th><span title="Black Rook Hold">BRH</span></th>
	<th><span title="Cathedral of Eternal Night">CEN</span></th>
	<th><span title="Court of Stars">CoS</span></th>
	<th><span title="Darkheart Thicket">DHT</span></th>
	<th><span title="Eye of Azshara">EoA</span></th>
	<th><span title="Halls of Valor">HoV</span></th>
	<th><span title="Lower Karazhan">LKZ</span></th>
	<th><span title="Maw of Souls">MoS</span></th>
	<th><span title="Neltharions Lair">NEL</span></th>
	<th><span title="The Arcway">ARC</span></th>
	<th><span title="Upper Karazhan">UKZ</span></th>
	<th><span title="Vault of the Wardens">VotW</span></th>
	<th><span title="The Violet Hold">VH</span></th>
</tr>
</thead>
<tbody>
<tr>';
		
for($i = '1'; $i <= '13'; $i++) {
	$dungeon_stats = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_dungeons` WHERE `id` = '" .$i. "'"));
	echo '<td><span title="' .$dungeon_stats['normal']. ' N ' .$dungeon_stats['heroic']. ' HC">' .$dungeon_stats['mythic']. '</span></td>';
}
		
echo '</tr>
</tbody>
</table>
</div>';
	
?>