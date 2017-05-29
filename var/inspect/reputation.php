<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;" class="inspect" id="reputation">
<span style="color: orange; font-size: 20px;">Reputation</span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
<tr>
	<th><span title="Court of Farondis">Azsuna</span></th>
	<th><span title="Legionfall">Broken Shore</span></th>	
	<th><span title="Highmountain Tribe">Highmountain</span></th>
	<th><span title="The Valarjar">Stormheim</span></th>	
	<th><span title="The Nightfallen">Suramar</span></th>
	<th><span title="Dreamweavers">Valsharah</span></th>
	<th><span title="The Wardens">Wardens</span></th>
</tr>
</thead>
<tbody>
<tr>';
		
$reputation_array = array($general_table['rep_farondis'], $general_table['rep_legionfall'], $general_table['rep_highmountain'], $general_table['rep_valarjar'], $general_table['rep_nightfallen'], $general_table['rep_dreamweaver'], $general_table['rep_wardens']);
		
foreach($reputation_array as $faction) {
	if($faction >= '42000') {
		$rep = '<span style="color: cyan;" title="' .$faction. '">Exalted</span>';
	}
	elseif($faction < '42000' && $faction >= '21000') {
		$rep = '<span style="color: #00ffcc;" title="' .$faction. '">Revered</span>';
	}
	elseif($faction < '21000' && $faction >= '9000') {
		$rep = '<span style="color: #00ff88;" title="' .$faction. '">Honored</span>';
	}
	elseif($faction < '9000' && $faction >= '3000') {
		$rep = '<span style="color: lime;" title="' .$faction. '">Friendly<span>';
	}
	elseif($faction < '3000') {
		$rep = '<span style="color: yellow;" title="' .$faction. '">Neutral or worse</span>';
	}
	
	echo '<td>' .$rep. '</td>';
}
	
echo '</tr>
</tbody>
</table>
</div>';

?>