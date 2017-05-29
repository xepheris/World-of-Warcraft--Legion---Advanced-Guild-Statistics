<?php

// Effort Quota CALCULATION

$threshold = '2228766330';
				
$artifact_knowledge_levels = array(1.25, 1.5, 1.9, 2.4, 3, 3.75, 4.75, 6, 7.5, 9.5, 12, 15, 18.75, 23.5, 29.5, 37, 46.5, 58, 73, 91, 114, 143, 179, 224, 250, 1000, 1300, 1700, 2200, 2900, 3800, 4900, 6400, 8300, 10800, 14000, 18200, 23700, 30800, 40000, 52000, 67600, 87900, 114300, 148600, 193200, 251200, 326600, 424500, 552000);
			
$ap_per_10_or_higher_key = 600*2*$artifact_knowledge_levels[$artifact_knowledge];
				
$worth = (($threshold/$ap_per_10_or_higher_key)*1466)/150;

// CLASS NORMALIZATION								
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

// EN BOSSKILLS
$en_lfr_bosskills = $en_lfr_1+$en_lfr_2+$en_lfr_3+$en_lfr_4+$en_lfr_5+$en_lfr_6+$en_lfr_7;
$en_n_bosskills = $en_normal_1+$en_normal_2+$en_normal_3+$en_normal_4+$en_normal_5+$en_normal_6+$en_normal_7;
$en_hc_bosskills = $en_heroic_1+$en_heroic_2+$en_heroic_3+$en_heroic_4+$en_heroic_5+$en_heroic_6+$en_heroic_7;
$en_m_bosskills = $en_mythic_1+$en_mythic_2+$en_mythic_3+$en_mythic_4+$en_mythic_5+$en_mythic_6+$en_mythic_7;
// TOV BOSSKILLS
$tov_lfr_bosskills = $tov_lfr_1+$tov_lfr_2+$tov_lfr_3;
$tov_n_bosskills = $tov_normal_1+$tov_normal_2+$tov_normal_3;
$tov_hc_bosskills = $tov_heroic_1+$tov_heroic_2+$tov_heroic_3;
$tov_m_bosskills = $tov_mythic_1+$tov_mythic_3+$tov_mythic_3;
// NH BOSSKILLS
$nh_lfr_bosskills = $nh_lfr_1+$nh_lfr_2+$nh_lfr_3+$nh_lfr_4+$nh_lfr_5+$nh_lfr_6+$nh_lfr_7+$nh_lfr_8+$nh_lfr_9+$nh_lfr_10;
$nh_n_bosskills = $nh_normal_1+$nh_normal_2+$nh_normal_3+$nh_normal_4+$nh_normal_5+$nh_normal_6+$nh_normal_7+$nh_normal_8+$nh_normal_9+$nh_normal_10;
$nh_hc_bosskills = $nh_heroic_1+$nh_heroic_2+$nh_heroic_3+$nh_heroic_4+$nh_heroic_5+$nh_heroic_6+$nh_heroic_7+$nh_heroic_8+$nh_heroic_9+$nh_heroic_10;
$nh_m_bosskills = $nh_mythic_1+$nh_mythic_2+$nh_mythic_3+$nh_mythic_4+$nh_mythic_5+$nh_mythic_6+$nh_mythic_7+$nh_mythic_8+$nh_mythic_9+$nh_mythic_10;
// TOS BOSSKILLS
$tos_lfr_bosskills = $tos_lfr_1+$tos_lfr_2+$tos_lfr_3+$tos_lfr_4+$tos_lfr_5+$tos_lfr_6+$tos_lfr_7+$tos_lfr_8+$tos_lfr_9;
$tos_n_bosskills = $tos_normal_1+$tos_normal_2+$tos_normal_3+$tos_normal_4+$tos_normal_5+$tos_normal_6+$tos_normal_7+$tos_normal_8+$tos_normal_9;
$tos_hc_bosskills = $tos_heroic_1+$tos_heroic_2+$tos_heroic_3+$tos_heroic_4+$tos_heroic_5+$tos_heroic_6+$tos_heroic_7+$tos_heroic_8+$tos_heroic_9;
$tos_m_bosskills = $tos_mythic_1+$tos_mythic_2+$tos_mythic_3+$tos_mythic_4+$tos_mythic_5+$tos_mythic_6+$tos_mythic_7+$tos_mythic_8+$tos_mythic_9;
				
$eq_weights = mysqli_fetch_array(mysqli_query($stream, "SELECT * FROM `ovw_eq` WHERE `id` = '1'"));
				
$eq = $world_quests*1+($m0*$eq_weights['m0'])
	+($m2_to_m5*$eq_weights['m2_5'])
	+($m5_to_m10*$eq_weights['m5_10'])
	+($m10_to_m15*$eq_weights['m10_15'])
	+($m15p*$eq_weights['m15p'])
	+($en_lfr_bosskills*$eq_weights['en_lfr'])
	+($en_n_bosskills*$eq_weights['en_n'])
	+($en_hc_bosskills*$eq_weights['en_hc'])
	+($en_m_bosskills*$eq_weights['en_m'])
	+($tov_lfr_bosskills*$eq_weights['tov_lfr'])
	+($tov_n_bosskills*$eq_weights['tov_n'])
	+($tov_hc_bosskills*$eq_weights['tov_hc'])
	+($tov_m_bosskills*$eq_weights['tov_m'])
	+($nh_lfr_bosskills*$eq_weights['nh_lfr'])
	+($nh_n_bosskills*$eq_weights['nh_n'])
	+($nh_hc_bosskills*$eq_weights['nh_hc'])
	+($nh_m_bosskills*$eq_weights['nh_m'])
	+($tos_lfr_bosskills*$eq_weights['tos_lfr'])
	+($tos_n_bosskills*$eq_weights['tos_n'])
	+($tos_hc_bosskills*$eq_weights['tos_hc'])
	+($tos_m_bosskills*$eq_weights['tos_m'])
	+$eq_ap
	+(($ilvlaverage-850)*$eq_weights['itemlevel']);

if($eq < '0') {
	$eq = '0';
}
				
$update_guild_table = mysqli_query($stream, "UPDATE `" .$table_name. "` SET `eq` = '" .$eq. "'  WHERE `name` = '" .$character. "'");


?>