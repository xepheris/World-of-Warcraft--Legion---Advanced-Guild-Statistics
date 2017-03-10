<?php

$url = 'https://eu.api.battle.net/wow/character/Blackmoore/Xepheris?fields=achievements&locale=en_GB&apikey=qxdrxhra6h3z5n3vrz3xks7779krxdcw';
				
	// ENABLE SSL
	$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);
		
	$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));
	
	if($data != '') {		
		$data = json_decode($data, true);
		
		// MYTHIC PLUS NUMBERS
				$key_mythicplus2 = array_search('33096', $data['achievements']['criteria']);
				$key_mythicplus5 = array_search('33097', $data['achievements']['criteria']);
				$key_mythicplus10 = array_search('33098', $data['achievements']['criteria']);
				$key_mythicplus15 = array_search('32028', $data['achievements']['criteria']);
										
												
				$criterias = array();
				array_push($criterias, $data['achievements']['criteriaQuantity']);
				$criterias = $criterias['0'];
					
				$mythic_plus2 = $criterias[$key_mythicplus2];
				$mythic_plus5 = $criterias[$key_mythicplus5];
				$mythic_plus10 = $criterias[$key_mythicplus10];
				$mythic_plus15 = $criterias[$key_mythicplus15];
	}
echo '' .$mythic_plus2. '<br />' .$mythic_plus5. '<br /> ' .$mythic_plus10. '<br />' .$mythic_plus15. '';

?>
		