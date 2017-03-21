t<?php

echo '<div style="width: 90%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; background-color: #84724E; margin-top: 15px; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-left: 5%;">';

echo '<h1 style="text-align: center; color: orange;">Effort Quota - unifying everything</h1><br />

<p style="text-align: center; color: orange;">Chapters: <a href="#goal"><u>Goal</u></a> <a href="#dropluck"><u>Evening out dropluck</u></a> <a href="#build"><u>Building a formula</u></a> <a href="#weighting"><u>Weighting</u></a> <a href="#normlization"><u>Normalization</u></a> <a href="#formula"><u>Formula</u></a> <a href="#examples"><u>Examples</u></a></p><br />';

echo '<h2 style="text-align: center; color: orange;"><a name="goal">Goal</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">


	The theory is that with a certain amount of different metrics, it should be possible to find a formula that shows you with one single value how much effort someone put into this character.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	There is already much information available on this page, but there still might be the situation, in which you\'re still unsure who deserves an item more.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	On the other side, loot is how people stay motivated, which is especially important in a progression guild.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	In the worst case scenario, someone who puts a lot of time into his character(s) has just bad luck concerning items and thus gets demotivated, losing the interest to raid or even play.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	Here the <b>Effort Quota</b> comes into play.
	
	
	</p>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="dropluck">Evening out dropluck</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">


	Player A and player B may have nearly the same stats overall - this is where every loot council system fails to find an answer for this.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	Normally you\'d let people roll for the item or just give it to the player who is longer in the guild. The EQ is here to help in this situation - the logical answer would be to give it to the person that puts more effort into his character.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	Assume now that player A was extremely lucky with drops or was, intentional or not, in mythic+ groups that favored drops for his armor class. Or he\'s one of those that got legendaries very quickly after each other.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	Meanwhile player B religiously did every World Quest cache and is still down 2 legendaries, did 200 more mythic+ dungeons, or just constantly higher mythic+ dungeons than player B, yet still  performs on the same level as player A.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	This is currently invisible for every other metric.
	
	
	</p>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="build">Building a formula</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">

	Now this is where it get\'s interesting. Let\'s take a look at what can be factored in and what cannot. This is the information we currently have available:
	
	</p>';

echo '<ul style="color: black;">';

$array = array( 'Itemlevel',
	'World Quests',
	'Mythic 0',
	'Mythic 2 to 5',
	'Mythic 5 to 10',
	'Mythic 10 to 15',
	'Mythic 15+',
	'Artifact Power',
	'Artifact Knowledge',
	'Artifact Level',
	'every boss kill on heroic for each raid', 'every boss kill on mythic for each raid' );

foreach ( $array as $li ) {
	echo '<li>' . $li . '</li>';
}

echo '</ul>';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">


	Since Artifact Knowledge is something that will in the long run be the exact same value on all characters and especially something that you don\'t actively contribute to, it falls off the grid.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	Artifact Level is the only reliable way of checking whether someone completed his weapon or not, but cannot be considered either as some classes had major changes during Legion and had to keep several specs ideally at Artifact Level 35. Tracking this value would favor players of classes that did not have to respec.
	
	</p>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="weighting">Weighting</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">


	The tricky part. Obviously some values are worth less than others, so they need to be weighted. Effort is time, so I looked at a few numbers.
	
	
	</p><p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">
	
	
	All of the mythic dungeon and raid boss kill timers factor in that you once progressed on them. Obviously nobody needs 21.7 minutes to 3-chest a MoS+2 especially since the timer is 14:24 minutes. But this is only a thing as you progressed further in general, reaching 890 itemlevel equipped when Nighthold was not even out yet. Also, dungeons will be retuned in 7.2.
	
	</p>';

echo '<ul style="color: black;">';

$array = array( 'time needed to complete a world quest: 150 seconds (2.5 minutes)',
	'time needed to "3-chest" a mythic+0: 1244 seconds (20.7 minutes) - this is the average time of all 9 dungeons available at 7.1.5',
	'time needed to finish a mythic2-5: 5% longer than m0  = 1306 seconds (21.7 minutes)',
	'time needed to finish a mythic 5-10: 10% longer than m0 = 1368 seconds (22.8 minutes)',
	'time needed to finish a mythic 10-15: 15% longer than m0 = 1431 seconds (23.85 minutes)',
	'time needed to finish a mythic 15+: 20% longer than m0 = 1492 seconds (24.9 minutes)',
	'time needed to kill a heroic raid boss = 900 seconds * progression time = 900*2 = 1800 seconds (30 minutes)',
	'time needed to kill a mythic raid boss = 900 seconds * progression time = 900*5 = 4500 seconds (75 minutes)',
	'time needed to reach 52 traits (the new 35 traits of 7.2) the most effective way (mythic 10+ chest AP) - this value varies depending on the characters Artifact Knowledge',
	'time needed for each itemlevel above Emerald Nightmare Normal Nythendra drops (850)<ul><li>gear is available via mythic+ and raids</li><li>most people farmed 5-10 for the extra AP but I\'m assuming that most gear upgrades come from mythics higher than 10 as well as the raid progress time for both heroic and mythic</li><li>(1431+1492)/2+1800+4500 = 7762 seconds per itemlevel (129 minutes)</li></ul>' );

foreach ( $array as $li ) {
	echo '<li>' . $li . '</li>';
}

echo '</ul>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="normalization">Normalization</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">

	Raids must be normalized due to the different size. Trial of Valor mythic was much harder than Emerald Nightmare mythic, but Trial of Valor has only three bosses that you could get credit for whilst the Nightmare had 7. Because of that, all raids will be normalized to the highest amount of bosses that there currently are in a raid (10, The Nighthold). This means:
	
	</p>';

echo '<table style="margin: 0 auto; color: orange;">
<thead>
<tr><th>effort</th><th>worth</th></tr>
</thead>
<tbody>';

$eq_weights = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ovw_eq` WHERE `id` = '1'"));

$array = array( 'worldquest' => '1',
	'mythic 0' => '' .$eq_weights['m0']. '',
	'mythic 2-5' => '' .$eq_weights['m2_5']. '',
	'mythic 5-10' => '' .$eq_weights['m5_10']. '',
	'mythic 10-15' => '' .$eq_weights['m10_15']. '',
	'mythic 15+' => '' .$eq_weights['m15p']. '',
	'EN heroic raid boss kill' => '' .$eq_weights['en_hc']. '',
	'EN mythic raid boss kill' => '' .$eq_weights['en_m']. '',
	'ToV heroic raid boss kill' => '' .$eq_weights['tov_hc']. '',
	'ToV mythic raid boss kill' => '' .$eq_weights['tov_m']. '',
	'NH heroic raid boss kill' => '' .$eq_weights['nh_hc']. '',
	'NH mythic raid boss kill' => '' .$eq_weights['nh_m']. '',
	'ToS heroic raid boss kill' => '' .$eq_weights['tos_hc']. '',
	'ToS mythic raid boss kill' => '' .$eq_weights['tos_m']. '',
	'trait 52 threshold' => 'individual',
	'each itemlevel above 850' => '' .$eq_weights['itemlevel']. '' );

foreach ( $array as $effort => $worth ) {
	echo '<tr><td>' . $effort . '</td><td>' . $worth . '</td></tr>';
}

echo '</tbody></table>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="formula">Formula</a></h2><br />';

echo '<p style="text-align: left; color: black; padding-left: 25px; padding-right: 25px; ">


	This is pretty much straight forward now:
	
	
	</p><p style="text-align: left; color: black; padding-left: 40px; padding-right: 25px;">
	
	$eq = ($worldquest) * 1 +<br />
	<br />
	$mythic0*' .$eq_weights['m0']. ' + $mythic2_to_5 * ' .$eq_weights['m2_5']. ' + $mythic5_to_10 * ' .$eq_weights['m5_10']. ' + $mythic10_to_15 * ' .$eq_weights['m10_15']. ' +	$mythic15_or_higher * ' .$eq_weights['m15p']. ' +<br />
	<br />
	$en_hc_boss_kill * ' .$eq_weights['en_hc']. ' + $en_m_boss_kill * ' .$eq_weights['en_m']. ' +<br />
	<br />
	$tov_hc_boss_kill * ' .$eq_weights['tov_hc']. ' + $tov_m_boss_kill * ' .$eq_weights['tov_m']. ' +<br />
	<br />
	$nh_hc_boss_kill * ' .$eq_weights['nh_hc']. ' + $nh_m_boss_kill * ' .$eq_weights['nh_m']. ' +<br />
	<br />
	$tos_hc_boss_kill * ' .$eq_weights['tos_hc']. ' +	$tos_m_boss_kill * ' .$eq_weights['tos_m']. ' +<br />
	<br />
	($itemlevel-850) * ' .$eq_weights['itemlevel']. ';
	<br />
	<br />
	$ap is a bit complicated as Artifact Knowledge plays a role:<br />
	the most effective way to farm AP would be to farm mythic10+ = 1431 seconds per run = 2*600*AK_%_increase per run<br />
	the target to reach is 52 traits (the new 35 traits in 7.2), which is at <u>$threshold = 2228766330</u> AP<br />
	thus, the amount of time needed to get there with the current AP/run is the weighted measure: at AK 25 it\'s <u>$worth = ($threshold/$ap_per_run*1431/150)</u> = 73895,98587<br />
	now we currently have amount <u>$ap farmed</u>, which is $x % of $threshold: <u>$x = $ap/$threshold</u><br />
	...now factor in it\'s current worth: <u>($ap/$threshold)*$worth</u> for classes that have 3 specs; for Demon Hunters it\'s ((($ap/$threshold)*$worth)/2)*3 and for Druids ((($ap/$threshold)*$worth)/4)*3
	<br />
	as Artifact Power is an extra inflationary value due to Artifact Knowledge, there\'s an extra penalty of 2.5 on top of it, so ultimately, the formula is<br />
	<u>($ap/$threshold)*($threshold/$ap_per_run*$time_per_run/$weighting)/2.5*$class</u>
	
	
	</p>';

echo '<br /><h2 style="text-align: center; color: orange;"><a name="examples">Examples</a></h2><br />';

$m0 = 131*$eq_weights['m0'];
$m2_5 = 114 * $eq_weights['m2_5'];
$m5_10 = 268 * $eq_weights['m5_10'];
$m10_15 = 189 *$eq_weights['m10_15'];
$m15 = 40 * $eq_weights['m15p'];
$en_hc = 71 * $eq_weights['en_hc'];
$en_m = ( 34 * $eq_weights['en_m'] );
$tov_hc = ( 30 * $eq_weights['tov_hc'] );
$tov_m = ( 1 * $eq_weights['tov_m'] );
$nh_hc = ( 51 * $eq_weights['nh_hc'] );
$nh_m = ( 5 * $eq_weights['nh_m'] );
$tos_hc = ( 0 * $eq_weights['tos_hc'] );
$tos_m = ( 0 * $eq_weights['tos_m'] );
$itemlevel = ( 906 - 850 ) * $eq_weights['itemlevel'];
$ap = ( ( ( ( 130621556 / 2228766330 ) * ( 2228766330 / 300000 * 1431 / 150 ) / 2.5 ) / 2 ) * 3 );

$sum = 1786+$m0+$m2_5+$m5_10+$m10_15+$m15+$en_hc+$en_m+$tov_hc+$tov_m+$nh_hc+$nh_m+$tos_hc+$tos_m+$itemlevel+$ap;
echo '<div style="display: table; margin: auto;">
	<table style="margin: 0 auto; color: black; float: left;">
	<thead>
	<tr>
	<th>effort</th><th>amount</th><th>points</th>
	</thead>
	<tbody>
	<tr><td>World Quests</td><td>1786</td><td>1786</td></tr>
	<tr><td>M0</td><td>131</td><td>' .$m0. '</td></tr>
	<tr><td>M2-5</td><td>114</td><td>' .$m2_5. '</td></tr>
	<tr><td>M5-10</td><td>268</td><td>' .$m5_10. '</td></tr>
	<tr><td>M10-15</td><td>189</td><td>' .$m10_15. '</td></tr>
	<tr><td>M15+</td><td>40</td><td>' .$m15. '</td></tr>
	<tr><td>EN HC Boss Kills</td><td>71</td><td>' .$en_hc. '</td></tr>
	<tr><td>EN M Boss Kills</td><td>34</td><td>' .$en_m. '</td></tr>
	<tr><td>ToV HC Boss Kills</td><td>30</td><td>' .$tov_hc. '</td></tr>
	<tr><td>ToV M Boss Kills</td><td>1</td><td>' .$tov_m. '</td></tr>
	<tr><td>NH HC Boss Kills</td><td>51</td><td>' .$nh_hc. '</td></tr>
	<tr><td>NH M Boss Kills</td><td>5</td><td>' .$nh_m. '</td></tr>
	<tr><td>ToS HC Boss Kills</td><td>0</td><td>' .$tos_hc. '</td></tr>
	<tr><td>ToS M Boss Kills</td><td>0</td><td>' .$tos_m. '</td></tr>
	<tr><td>Itemlevel</td><td>906</td><td>' .$itemlevel. '</td></tr>
	<tr><td>AP</td><td>130621556 at AK 25 as Demon Hunter</td><td>' .$ap. '</td></tr>
	<tr><td></td><td><b>total</b></td><td><b>' .$sum. '</b></td></tr>
	</tbody>
	</table>';

$m0 = 431*$eq_weights['m0'];
$m2_5 = 98 * $eq_weights['m2_5'];
$m5_10 = 371 * $eq_weights['m5_10'];
$m10_15 = 304 *$eq_weights['m10_15'];
$m15 = 115 * $eq_weights['m15p'];
$en_hc = 127 * $eq_weights['en_hc'];
$en_m = ( 63 * $eq_weights['en_m'] );
$tov_hc = ( 19 * $eq_weights['tov_hc'] );
$tov_m = ( 3 * $eq_weights['tov_m'] );
$nh_hc = ( 58 * $eq_weights['nh_hc'] );
$nh_m = ( 17 * $eq_weights['nh_m'] );
$tos_hc = ( 0 * $eq_weights['tos_hc'] );
$tos_m = ( 0 * $eq_weights['tos_m'] );
$itemlevel = ( 903 - 850 ) * $eq_weights['itemlevel'];
$ap = ( 196133799 / 2228766330 ) * ( 2228766330 / 300000 * 1431 / 150 ) / 2.5;
$sum = 1613+$m0+$m2_5+$m5_10+$m10_15+$m15+$en_hc+$en_m+$tov_hc+$tov_m+$nh_hc+$nh_m+$tos_hc+$tos_m+$itemlevel+$ap;
	
	echo '<table style="margin: 0 auto; color: black; float: left; margin-left: 15px;">
	<thead>
	<tr>
	<th>effort</th><th>amount</th><th>points</th>
	</thead>
	<tbody>
	<tr><td>World Quests</td><td>1613</td><td>1613</td></tr>
	<tr><td>M0</td><td>431</td><td>' .$m0. '</td></tr>
	<tr><td>M2-5</td><td>98</td><td>' .$m2_5. '</td></tr>
	<tr><td>M5-10</td><td>371</td><td>' .$m5_10. '</td></tr>
	<tr><td>M10-15</td><td>304</td><td>' .$m10_15. '</td></tr>
	<tr><td>M15+</td><td>115</td><td>' .$m15. '</td></tr>
	<tr><td>EN HC Boss Kills</td><td>127</td><td>' .$en_hc. '</td></tr>
	<tr><td>EN M Boss Kills</td><td>63</td><td>' .$en_m. '</td></tr>
	<tr><td>ToV HC Boss Kills</td><td>19</td><td>' .$tov_hc. '</td></tr>
	<tr><td>ToV M Boss Kills</td><td>3</td><td>' .$tov_m. '</td></tr>
	<tr><td>NH HC Boss Kills</td><td>58</td><td>' .$nh_hc. '</td></tr>
	<tr><td>NH M Boss Kills</td><td>17</td><td>' .$nh_m. '</td></tr>
	<tr><td>ToS HC Boss Kills</td><td>0</td><td>' .$tos_hc. '</td></tr>
	<tr><td>ToS M Boss Kills</td><td>0</td><td>' .$tos_m. '</td></tr>
	<tr><td>Itemlevel</td><td>903</td><td>' .$itemlevel. '</td></tr>
	<tr><td>AP</td><td>196133799 at AK 25 as Monk</td><td>' .$ap. '</td></tr>
	<tr><td></td><td><b>total</b></td><td><b>' .$sum. '</b></td></tr>
	</tbody>
	</table>';
	
$m0 = 122*$eq_weights['m0'];
$m2_5 = 116 * $eq_weights['m2_5'];
$m5_10 = 181 * $eq_weights['m5_10'];
$m10_15 = 61 *$eq_weights['m10_15'];
$m15 = 17 * $eq_weights['m15p'];
$en_hc = 116 * $eq_weights['en_hc'];
$en_m = ( 32 * $eq_weights['en_m'] );
$tov_hc = ( 12 * $eq_weights['tov_hc'] );
$tov_m = ( 1 * $eq_weights['tov_m'] );
$nh_hc = ( 56 * $eq_weights['nh_hc'] );
$nh_m = ( 11 * $eq_weights['nh_m'] );
$tos_hc = ( 0 * $eq_weights['tos_hc'] );
$tos_m = ( 0 * $eq_weights['tos_m'] );
$itemlevel = ( 903 - 850 ) * $eq_weights['itemlevel'];
$ap = ( 86789102 / 2228766330 ) * ( 2228766330 / 300000 * 1431 / 150 ) / 2.5;
$sum = 722+$m0+$m2_5+$m5_10+$m10_15+$m15+$en_hc+$en_m+$tov_hc+$tov_m+$nh_hc+$nh_m+$tos_hc+$tos_m+$itemlevel+$ap;
	
	echo '<table style="margin: 0 auto; color: black; float: left; margin-left: 15px;">
	<thead>
	<tr>
	<th>effort</th><th>amount</th><th>points</th>
	</thead>
	<tbody>
	<tr><td>World Quests</td><td>722</td><td>722</td></tr>
	<tr><td>M0</td><td>122</td><td>' .$m0. '</td></tr>
	<tr><td>M2-5</td><td>116</td><td>' .$m2_5. '</td></tr>
	<tr><td>M5-10</td><td>181</td><td>' .$m5_10. '</td></tr>
	<tr><td>M10-15</td><td>61</td><td>' .$m10_15. '</td></tr>
	<tr><td>M15+</td><td>17</td><td>' .$m15. '</td></tr>
	<tr><td>EN HC Boss Kills</td><td>116</td><td>' .$en_hc. '</td></tr>
	<tr><td>EN M Boss Kills</td><td>32</td><td>' .$en_m. '</td></tr>
	<tr><td>ToV HC Boss Kills</td><td>12</td><td>' .$tov_hc. '</td></tr>
	<tr><td>ToV M Boss Kills</td><td>1</td><td>' .$tov_m. '</td></tr>
	<tr><td>NH HC Boss Kills</td><td>56</td><td>' .$nh_hc. '</td></tr>
	<tr><td>NH M Boss Kills</td><td>11</td><td>' .$nh_m. '</td></tr>
	<tr><td>ToS HC Boss Kills</td><td>0</td><td>' .$tos_hc. '</td></tr>
	<tr><td>ToS M Boss Kills</td><td>0</td><td>' .$tos_m. '</td></tr>
	<tr><td>Itemlevel</td><td>903</td><td>' .$itemlevel. '</td></tr>
	<tr><td>AP</td><td>86789102 at AK 25 as Shaman</td><td>' .$ap. '</td></tr>
	<tr><td></td><td><b>total</b></td><td><b>' .$sum. '</b></td></tr>
	</tbody>
	</table>
	</div>';

echo '</div>';

?>