
<?php

$url = 'https://eu.api.battle.net/wow/character/Blackmoore/' .$_GET['char']. '?fields=talents&locale=en_GB&apikey=qxdrxhra6h3z5n3vrz3xks7779krxdcw';



$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);
$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
if($data != '') {
			
	$data = json_decode($data, true);
	
	include('stream.php');
	
	$character = $_GET['char'];
	$table_name = '2_BrøFørce_EU_Blackmoore';
	
	// FETCH INTERNAL SPEC ID
					$spec = mysqli_fetch_array(mysqli_query($stream, "SELECT `spec` FROM `" .$table_name. "` WHERE `name` = '" .$character. "'"));
	// CURRENTLY SELECTED TALENTS
	
	
	
	// CURRENTLY SELECTED TALENTS
					for($i = '0'; $i <= '4'; $i++) {
						if($data['talents'][$i]['selected'] == '1') {
							
							$class_tcalc_conversion = array('1' => 'Z', '2' => 'b', '3' => 'Y', '4' => 'c', '5' => 'X', '6' => 'd', '7' => 'W', '8' => 'e', '9' => 'V', '10' => 'f', '11' => 'U', '12' => 'g');
							
							foreach($class_tcalc_conversion as $class => $prefix) {
								if($data['class'] == $class) {
									$talent_calc_var = '' .$prefix. '' .$data['talents'][$i]['calcSpec']. '!' .$data['talents'][$i]['calcTalent']. '!';
									if(isset($data['talents'][$i]['talents'][$k]['spec']['name'])) {
										$specc = $data['talents'][$i]['talents'][$k]['spec']['name'];
									}
								}
							}
						}
					}
				}
		
	echo $talent_calc_var;

?>