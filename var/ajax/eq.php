<?php

// Effort Quota CALCULATION

$threshold = '2228766330';
				
$artifact_knowledge_levels = array('0' => '1', '1' => '1.25', '2' => '1.5', '3' => '1.9', '4' => '2.4', '5' => '3', '6' => '3.75', '7' => '4.75', '8' => '6', '9' => '7.5', '10' => '9.5', '11' => '12', '12' => '15', '13' => '18.75', '14' => '23.5', '15' => '29.5', '16' => '37', '17' => '46.5', '18' => '58', '19' => '73', '20' => '91', '21' => '114', '22' => '143', '23' => '179', '24' => '224', '25' => '250', '26' => '1000', '27' => '1300', '28' => '1700', '29' => '2200', '30' => '2900', '31' => '3800', '32' => '4900', '33' => '6400', '34' => '8300', '35' => '10800', '36' => '14000', '37' => '18200', '38 '=> '23700', '39' => '30800', '40' => '40000', '41' => '52000', '42' => '67600', '43' => '87900', '44' => '114300', '45' => '148600', '46' => '193200', '47' => '251200', '48' => '326600', '49' => '424500', '50' => '552000');
				
foreach($artifact_knowledge_levels as $current_ak => $increase) {
	if($current_ak == $artifact_knowledge) {
		$ap_per_10_or_higher_key = 600*$increase*2;
	}
}
				
$worth = (($threshold/$ap_per_10_or_higher_key)*1431)/150;
								
if($data['class'] == '11') {
	$eq_ap = (((($artifact_power/$threshold)*$worth)/4)*3)/2.5;
}
elseif($data['class'] == '12') {
	$eq_ap = (((($artifact_power/$threshold)*$worth)/2)*3)/2.5;
}
elseif($data['class'] != '11' && $data['class'] != '12') {
	$eq_ap = (($artifact_power/$threshold)*$worth)/2.5;
}
								
$sum = $brh_mythic+$cen_mythic+$cos_mythic+$dht_mythic+$eoa_mythic+$hov_mythic+$lkz_mythic+$mos_mythic+$nl_mythic+$arc_mythic+$vh_mythic+$ukz_mythic+$votw_mythic;
$m0 = $sum-$mythic_plus2;
$m2_to_m5 = $mythic_plus2-$mythic_plus5;
$m5_to_m10 = $mythic_plus5-$mythic_plus10;
$m10_to_m15 = $mythic_plus10-$mythic_plus15;
$m15p = $mythic_plus15;
				

if($m2_to_m5 < '0') {
	$m2_to_m5 = '0';
	$m5_to_m10 = '0';
	$m10_to_m15 = '0';
	$m15p = '0';
}
if($m5_to_m10 < '0') {
	$m5_to_m10 = '0';
	$m10_to_m15 = '0';
	$m15p = '0';
}
if($m10_to_m15 < '0') {
	$m10_to_m15 = '0';
	$m15p = '0';
}
				
$en_hc_bosskills = $en_heroic_1+$en_heroic_2+$en_heroic_3+$en_heroic_4+$en_heroic_5+$en_heroic_6+$en_heroic_7;
$en_m_bosskills = $en_mythic_1+$en_mythic_2+$en_mythic_3+$en_mythic_4+$en_mythic_5+$en_mythic_6+$en_mythic_7;
$tov_hc_bosskills = $tov_heroic_1+$tov_heroic_2+$tov_heroic_3;
$tov_m_bosskills = $tov_mythic_1+$tov_mythic_3+$tov_mythic_3;
$nh_hc_bosskills = $nh_heroic_1+$nh_heroic_2+$nh_heroic_3+$nh_heroic_4+$nh_heroic_5+$nh_heroic_6+$nh_heroic_7+$nh_heroic_8+$nh_heroic_9+$nh_heroic_10;
$nh_m_bosskills = $nh_mythic_1+$nh_mythic_2+$nh_mythic_3+$nh_mythic_4+$nh_mythic_5+$nh_mythic_6+$nh_mythic_7+$nh_mythic_8+$nh_mythic_9+$nh_mythic_10;
$tos_hc_bosskills = $tos_heroic_1+$tos_heroic_2+$tos_heroic_3+$tos_heroic_4+$tos_heroic_5+$tos_heroic_6+$tos_heroic_7+$tos_heroic_8+$tos_heroic_9;
$tos_m_bosskills = $tos_mythic_1+$tos_mythic_2+$tos_mythic_3+$tos_mythic_4+$tos_mythic_5+$tos_mythic_6+$tos_mythic_7+$tos_mythic_8+$tos_mythic_9;
				
$eq_weights = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ovw_eq` WHERE `id` = '1'"));
				
$eq = $world_quests*1+($m0*$eq_weights['m0'])+($m2_to_m5*$eq_weights['m2_5'])+($m5_to_m10*$eq_weights['m5_10'])+($m10_to_m15*$eq_weights['m10_15'])+($m15p*$eq_weights['m15p'])+($en_hc_bosskills*$eq_weights['en_hc'])+($en_m_bosskills*$eq_weights['en_m'])+($tov_hc_bosskills*$eq_weights['tov_hc'])+($tov_m_bosskills*$eq_weights['tov_m'])+($nh_hc_bosskills*$eq_weights['nh_hc'])+($nh_m_bosskills*$eq_weights['nh_m'])+($tos_hc_bosskills*$eq_weights['tos_hc'])+($tos_m_bosskills*$eq_weights['tos_m'])+$eq_ap+(($ilvlaverage-850)*$eq_weights['itemlevel']);
				
$update_guild_table = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `eq` = '" .$eq. "'  WHERE `name` = '" .$character. "'");


?>