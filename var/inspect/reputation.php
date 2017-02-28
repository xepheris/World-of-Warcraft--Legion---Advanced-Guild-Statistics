<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">
<span style="color: orange; font-size: 20px;">Reputation</span>
<table style="margin: 0 auto; text-align: left; margin-top: 15px; width: 90%;">
<thead>
<tr>
	<th>Court of Farondis</th>
	<th>Dreamweavers</th>
	<th>Highmountain Tribe</th>
	<th>Legionfall</th>			
	<th>The Nightfallen</th>
	<th>The Wardens</th>
	<th>The Valarjar</th>			
</tr>
</thead>
<tbody>
<tr>';
		
$reputation_array = array($general_table['rep_farondis'], $general_table['rep_dreamweaver'], $general_table['rep_highmountain'], $general_table['rep_legionfall'], $general_table['rep_nightfallen'], $general_table['rep_wardens'], $general_table['rep_valarjar']);
		
foreach($reputation_array as $faction) {
	if($faction >= '42000') {
		$rep = '<span style="color: cyan;">Exalted</span>';
	}
	elseif($faction < '42000' && $faction >= '21000') {
		$rep = '<span style="color: #00ffcc;">Revered</span>';
	}
	elseif($faction < '21000' && $faction >= '9000') {
		$rep = '<span style="color: #00ff88;">Honored</span>';
	}
	elseif($faction < '9000' && $faction >= '3000') {
		$rep = '<span style="color: lime;">Friendly<span>';
	}
	elseif($faction < '3000') {
		$rep = '<span style="color: yellow;">Neutral or worse</span>';
	}
	
	echo '<td>' .$rep. '</td>';
}
	
echo '</tr>
</tbody>
</table>
</div>';

?>