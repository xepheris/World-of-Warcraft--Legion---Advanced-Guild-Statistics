<?php

// written by Xepheris, March 2017 - admin@gerritalex.de
// MIT License in case anyone ever wants to use this

if ( isset( $_GET[ 'character' ] ) && is_numeric( $_GET[ 'character' ] ) ) {

	session_start();

	include( 'stream.php' );

	$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

	$old = mysqli_fetch_array( mysqli_query( $stream, "SELECT `updated`, `realm`, `name`, `logout` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'character' ] . "'" ) );

	$character = $old[ 'name' ];

	$timediff = time( 'now' ) - $old[ 'updated' ];

	if ( $timediff >= '300' ) {

		$actual_realm_name = mysqli_fetch_array( mysqli_query( $stream, "SELECT `short` FROM `ovw_realms` WHERE `id` = '" . $old[ 'realm' ] . "'" ) );

		$key = mysqli_fetch_array( mysqli_query( $stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'" ) );

		$url = 'https://' . $_SESSION[ 'region' ] . '.api.battle.net/wow/character/' . $actual_realm_name[ 'short' ] . '/' . $character . '?fields=guild,items,statistics,achievements,talents&locale=en_GB&apikey=' . $key[ 'wow_key' ] . '';

		// ENABLE SSL
		$arrContextOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, ), );

		$data = @file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );

		if ( $data != '' ) {
			$data = json_decode( $data, true );

			$escaped_session_guild_name = str_replace( ' ', '%20', $_SESSION[ 'guild' ] );
			$escaped_source_guild_name = str_replace( ' ', '%20', $data[ 'guild' ][ 'name' ] );

			if ( $escaped_session_guild_name == $escaped_source_guild_name ) {

				// 110 CHECK
				if ( $data[ 'level' ] == '110' ) {

					// LAST LOGOUT
					$logout = substr( $data[ 'lastModified' ], '0', '10' );

					// COMPARE LOGOUT DATE WITH DB DATE

					if ( $logout > $old[ 'logout' ] ) {

						// PREPARE PAST VALUES
						$char_id = mysqli_fetch_array( mysqli_query( $stream, "SELECT `id` FROM `" . $table_name . "` WHERE `name` = '" . $character . "'" ) );
						$past_values = mysqli_fetch_array( mysqli_query( $stream, "SELECT `ilvl_on`, `ilvl_off`, `ap`, `wq`, `m2`, `m5`, `m10`, `m15` FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_general`" ) );

						// ALL ITEMS
						$items = array( 'head', 'neck', 'shoulder', 'back', 'chest', 'wrist', 'hands', 'waist', 'legs', 'feet', 'finger1', 'finger2', 'trinket1', 'trinket2' );
						$legendary = array();

						// ITERATE THROUGH ALL REGULAR ITEM SLOTS
						foreach ( $items as $item ) {
							// ITEM ID
							$ {
								'' . $item . '_id'
							} = $data[ 'items' ][ '' . $item . '' ][ 'id' ];

							// IF LEGENDARY, REMEMBER
							if ( $data[ 'items' ][ '' . $item . '' ][ 'quality' ] == '5' ) {
								array_push( $legendary, $data[ 'items' ][ '' . $item . '' ][ 'id' ] );
							}

							// ITEMLEVEL
							$ {
								'' . $item . '_ilvl'
							} = $data[ 'items' ][ '' . $item . '' ][ 'itemLevel' ];

							// IF ENCHANT VALUE IS SET
							if ( !empty( $data[ 'items' ][ '' . $item . '' ][ 'tooltipParams' ][ 'enchant' ] ) ) {
								$ {
									'' . $item . '_ench'
								} = $data[ 'items' ][ '' . $item . '' ][ 'tooltipParams' ][ 'enchant' ];
							}

							// IF GEM SLOT IS NOT EMPTY						
							if ( !empty( $data[ 'items' ][ '' . $item . '' ][ 'tooltipParams' ][ 'gem0' ] ) ) {
								$ {
									'' . $item . '_gem0'
								} = $data[ 'items' ][ '' . $item . '' ][ 'tooltipParams' ][ 'gem0' ];
							} else {
								$ {
									'' . $item . '_gem0'
								} = '';
							}

							// CONVERT BONUS LIST
							foreach ( $data[ 'items' ][ '' . $item . '' ][ 'bonusLists' ] as $bonus ) {
								if ( !isset( $ {
										'' . $item . '_bonus'
									} ) ) {
									$ {
										'' . $item . '_bonus'
									} = $bonus;
								} elseif ( isset( $ {
										'' . $item . '_bonus'
									} ) ) {
									$ {
										'' . $item . '_bonus'
									} .= ':' . $bonus . '';
								}
							}
						}


						// CURRENTLY SELECTED TALENTS
						for ( $i = '0'; $i <= '4'; $i++ ) {
							if ( $data[ 'talents' ][ $i ][ 'selected' ] == '1' ) {

								$class_tcalc_conversion = array( '1' => 'Z', '2' => 'b', '3' => 'Y', '4' => 'c', '5' => 'X', '6' => 'd', '7' => 'W', '8' => 'e', '9' => 'V', '10' => 'f', '11' => 'U', '12' => 'g' );

								if ( !isset( $talent_calc_var ) ) {
									foreach ( $class_tcalc_conversion as $class => $prefix ) {
										if ( $data[ 'class' ] == $class ) {
											$talent_calc_var = '' . $prefix . '' . $data[ 'talents' ][ $i ][ 'calcSpec' ] . '!' . $data[ 'talents' ][ $i ][ 'calcTalent' ] . '!';
											for ( $k = '0'; $k <= '6'; $k++ ) {
												if ( isset( $data[ 'talents' ][ $i ][ 'talents' ][ $k ][ 'spec' ][ 'name' ] ) ) {
													$spec = $data[ 'talents' ][ $i ][ 'talents' ][ $k ][ 'spec' ][ 'name' ];
												}
											}
										}
									}
								}
							}
						}


						$fetch_spec_var = mysqli_fetch_array( mysqli_query( $stream, "SELECT `id` FROM `ovw_weapons` WHERE `spec` = '" . $spec . "' AND `class` = '" . $data[ 'class' ] . "'" ) );

						$update = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `spec` = '" . $fetch_spec_var[ 'id' ] . "' WHERE `name` = '" . $character . "'" );

						// FETCH INTERNAL SPEC ID
						$spec = $fetch_spec_var[ 'id' ];

						// FETCH WEAPON ID OF SUPPOSEDLY EQUIPPED WEAPON (always assuming here that you have your artifact weapon equipped)
						$weapon = mysqli_fetch_array( mysqli_query( $stream, "SELECT `weapon_id` FROM `ovw_weapons` WHERE `id` = '" . $spec . "'" ) );

						// IF MAINHAND IS ARTIFACT WEAPON
						if ( $data[ 'items' ][ 'mainHand' ][ 'id' ] == $weapon[ 'weapon_id' ] ) {

							// MAINHAND ITEMLEVEL
							$mhilvl = $data[ 'items' ][ 'mainHand' ][ 'itemLevel' ];

							// IF OFFHAND HAS ITEMLEVEL AS WELL (excluding 2 handers)
							if ( !empty( $data[ 'items' ][ 'offHand' ][ 'itemLevel' ] ) ) {
								$ohilvl = $data[ 'items' ][ 'offHand' ][ 'itemLevel' ];
							}

							$traits = '0';
							foreach ( $data[ 'items' ][ 'mainHand' ][ 'artifactTraits' ] as $trait ) {
								$traits = $traits + $trait[ 'rank' ];
							}

							// CONVERT BONUS LIST
							foreach ( $data[ 'items' ][ 'mainHand' ][ 'bonusLists' ] as $bonus ) {
								if ( !isset( $mh_bonus ) ) {
									$mh_bonus = $bonus;
								} elseif ( isset( $mh_bonus ) ) {
									$mh_bonus .= ':' . $bonus . '';
								}
							}

							// COLLECTING RELICS IF EXISTING
							if ( !empty( $data[ 'items' ][ 'mainHand' ][ 'relics' ] ) ) {
								$p = '0';
								foreach ( $data[ 'items' ][ 'mainHand' ][ 'relics' ] as $relic ) {
									$ {
										'mhrelic' . $p . ''
									} = $relic[ 'itemId' ];

									foreach ( $relic[ 'bonusLists' ] as $bonus ) {
										if ( !isset( $ {
												'mhrelicbonus' . $p . ''
											} ) ) {
											$ {
												'mhrelicbonus' . $p . ''
											} = $bonus;
										} elseif ( isset( $ {
												'mhrelicbonus' . $p . ''
											} ) ) {
											$ {
												'mhrelicbonus' . $p . ''
											} .= ':' . $bonus . '';
										}
									}
									$p++;
								}
							}
						}
						// IF OFFHAND IS ARTIFACT WEAPON
						elseif ( $data[ 'items' ][ 'offHand' ][ 'id' ] == $weapon[ 'weapon_id' ] ) {

							// OFFHAND ITEMLEVEL
							$ohilvl = $data[ 'items' ][ 'offHand' ][ 'itemLevel' ];

							// IF MAINHAND HAS ITEMLEVEL AS WELL
							if ( !empty( $data[ 'items' ][ 'mainHand' ][ 'itemLevel' ] ) ) {
								$mhilvl = $data[ 'items' ][ 'mainHand' ][ 'itemLevel' ];
							}

							$traits = '0';
							foreach ( $data[ 'items' ][ 'offHand' ][ 'artifactTraits' ] as $trait ) {
								$traits = $traits + $trait[ 'rank' ];
							}

							// CONVERT BONUS LIST
							foreach ( $data[ 'items' ][ 'offHand' ][ 'bonusLists' ] as $bonus ) {
								if ( !isset( $oh_bonus ) ) {
									$oh_bonus = $bonus;
								} elseif ( isset( $oh_bonus ) ) {
									$oh_bonus .= ':' . $bonus . '';
								}
							}

							// COLLECTING RELICS IF EXISTING
							if ( !empty( $data[ 'items' ][ 'offHand' ][ 'relics' ] ) ) {
								$p = '0';
								foreach ( $data[ 'items' ][ 'offHand' ][ 'relics' ] as $relic ) {
									$ {
										'ohrelic' . $p . ''
									} = $relic[ 'itemId' ];

									foreach ( $relic[ 'bonusLists' ] as $bonus ) {
										if ( !isset( $ {
												'ohrelicbonus' . $p . ''
											} ) ) {
											$ {
												'ohrelicbonus' . $p . ''
											} = $bonus;
										} elseif ( isset( $ {
												'ohrelicbonus' . $p . ''
											} ) ) {
											$ {
												'ohrelicbonus' . $p . ''
											} .= ':' . $bonus . '';
										}
									}
									$p++;
								}
							}
						}

						// EQUIPPED ITEMLEVEL						
						$ilvlaverage = $data[ 'items' ][ 'averageItemLevelEquipped' ];

						// BAG ITEMLEVEL						
						$ilvlaveragebags = $data[ 'items' ][ 'averageItemLevel' ];

						// DUNGEON PROGRESS
						$eoa_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '0' ][ 'quantity' ];
						$eoa_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '1' ][ 'quantity' ];
						$eoa_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '2' ][ 'quantity' ];

						$dht_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '3' ][ 'quantity' ];
						$dht_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '4' ][ 'quantity' ];
						$dht_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '5' ][ 'quantity' ];

						$nl_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '6' ][ 'quantity' ];
						$nl_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '7' ][ 'quantity' ];
						$nl_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '8' ][ 'quantity' ];

						$hov_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '9' ][ 'quantity' ];
						$hov_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '10' ][ 'quantity' ];
						$hov_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '11' ][ 'quantity' ];

						$vh_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '12' ][ 'quantity' ] + $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '13' ][ 'quantity' ];
						$vh_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '14' ][ 'quantity' ] + $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '15' ][ 'quantity' ];
						$vh_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '16' ][ 'quantity' ] + $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '17' ][ 'quantity' ];

						$votw_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '18' ][ 'quantity' ];
						$votw_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '19' ][ 'quantity' ];
						$votw_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '20' ][ 'quantity' ];

						$brh_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '21' ][ 'quantity' ];
						$brh_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '22' ][ 'quantity' ];
						$brh_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '23' ][ 'quantity' ];

						$mos_normal = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '24' ][ 'quantity' ];
						$mos_heroic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '25' ][ 'quantity' ];
						$mos_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '26' ][ 'quantity' ];

						$arc_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '27' ][ 'quantity' ];

						$cos_mythic = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '28' ][ 'quantity' ];

						#$cen_mythic = '';

						#$ukz_mythic = '';

						#$lkz_mythic = '';

						// RAID PROGRESS

						////////// EMERALD NIGHTMARE
						// LFR EMERALD NIGHTMARE
						$en_lfr_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '30' ][ 'quantity' ];
						$en_lfr_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '34' ][ 'quantity' ];
						$en_lfr_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '38' ][ 'quantity' ];
						$en_lfr_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '42' ][ 'quantity' ];
						$en_lfr_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '46' ][ 'quantity' ];
						$en_lfr_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '50' ][ 'quantity' ];
						$en_lfr_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '54' ][ 'quantity' ];

						// NORMAL EMERALD NIGHTMARE					
						$en_normal_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '31' ][ 'quantity' ];
						$en_normal_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '35' ][ 'quantity' ];
						$en_normal_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '39' ][ 'quantity' ];
						$en_normal_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '43' ][ 'quantity' ];
						$en_normal_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '47' ][ 'quantity' ];
						$en_normal_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '51' ][ 'quantity' ];
						$en_normal_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '55' ][ 'quantity' ];

						// HEROIC EMERALD NIGHTMARE					
						$en_heroic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '32' ][ 'quantity' ];
						$en_heroic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '36' ][ 'quantity' ];
						$en_heroic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '40' ][ 'quantity' ];
						$en_heroic_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '44' ][ 'quantity' ];
						$en_heroic_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '48' ][ 'quantity' ];
						$en_heroic_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '52' ][ 'quantity' ];
						$en_heroic_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '56' ][ 'quantity' ];

						// MYTHIC EMERALD NIGHTMARE					
						$en_mythic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '33' ][ 'quantity' ];
						$en_mythic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '37' ][ 'quantity' ];
						$en_mythic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '41' ][ 'quantity' ];
						$en_mythic_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '45' ][ 'quantity' ];
						$en_mythic_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '49' ][ 'quantity' ];
						$en_mythic_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '53' ][ 'quantity' ];
						$en_mythic_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '57' ][ 'quantity' ];
						//////////

						////////// TRIAL OF VALOR
						// LFR TRIAL OF VALOR
						$tov_lfr_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '58' ][ 'quantity' ];
						$tov_lfr_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '62' ][ 'quantity' ];
						$tov_lfr_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '66' ][ 'quantity' ];

						// NORMAL TRIAL OF VALOR
						$tov_normal_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '59' ][ 'quantity' ];
						$tov_normal_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '63' ][ 'quantity' ];
						$tov_normal_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '67' ][ 'quantity' ];

						// HEROIC TRIAL OF VALOR
						$tov_heroic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '60' ][ 'quantity' ];
						$tov_heroic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '64' ][ 'quantity' ];
						$tov_heroic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '68' ][ 'quantity' ];

						// MYTHIC TRIAL OF VALOR
						$tov_mythic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '61' ][ 'quantity' ];
						$tov_mythic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '65' ][ 'quantity' ];
						$tov_mythic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '69' ][ 'quantity' ];
						//////////

						////////// THE NIGHTHOLD
						// LFR THE NIGHTHOLD
						$nh_lfr_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '70' ][ 'quantity' ];
						$nh_lfr_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '74' ][ 'quantity' ];
						$nh_lfr_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '78' ][ 'quantity' ];
						$nh_lfr_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '82' ][ 'quantity' ];
						$nh_lfr_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '86' ][ 'quantity' ];
						$nh_lfr_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '90' ][ 'quantity' ];
						$nh_lfr_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '94' ][ 'quantity' ];
						$nh_lfr_8 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '98' ][ 'quantity' ];
						$nh_lfr_9 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '102' ][ 'quantity' ];
						$nh_lfr_10 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '106' ][ 'quantity' ];

						// NORMAL THE NIGHTHOLD
						$nh_normal_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '71' ][ 'quantity' ];
						$nh_normal_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '75' ][ 'quantity' ];
						$nh_normal_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '79' ][ 'quantity' ];
						$nh_normal_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '83' ][ 'quantity' ];
						$nh_normal_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '87' ][ 'quantity' ];
						$nh_normal_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '91' ][ 'quantity' ];
						$nh_normal_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '95' ][ 'quantity' ];
						$nh_normal_8 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '99' ][ 'quantity' ];
						$nh_normal_9 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '103' ][ 'quantity' ];
						$nh_normal_10 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '107' ][ 'quantity' ];

						// HEROIC THE NIGHTHOLD
						$nh_heroic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '72' ][ 'quantity' ];
						$nh_heroic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '76' ][ 'quantity' ];
						$nh_heroic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '80' ][ 'quantity' ];
						$nh_heroic_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '84' ][ 'quantity' ];
						$nh_heroic_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '88' ][ 'quantity' ];
						$nh_heroic_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '92' ][ 'quantity' ];
						$nh_heroic_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '96' ][ 'quantity' ];
						$nh_heroic_8 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '100' ][ 'quantity' ];
						$nh_heroic_9 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '104' ][ 'quantity' ];
						$nh_heroic_10 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '108' ][ 'quantity' ];

						// MYTHIC THE NIGHTHOLD
						$nh_mythic_1 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '73' ][ 'quantity' ];
						$nh_mythic_2 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '77' ][ 'quantity' ];
						$nh_mythic_3 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '81' ][ 'quantity' ];
						$nh_mythic_4 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '85' ][ 'quantity' ];
						$nh_mythic_5 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '89' ][ 'quantity' ];
						$nh_mythic_6 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '93' ][ 'quantity' ];
						$nh_mythic_7 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '97' ][ 'quantity' ];
						$nh_mythic_8 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '101' ][ 'quantity' ];
						$nh_mythic_9 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '105' ][ 'quantity' ];
						$nh_mythic_10 = $data[ 'statistics' ][ 'subCategories' ][ '5' ][ 'subCategories' ][ '6' ][ 'statistics' ][ '109' ][ 'quantity' ];
						//////////

						// HIGHEST M+ IN TIME ACCORDING TO ACHIEVEMENTS
						if ( in_array( '11162', $data[ 'achievements' ][ 'achievementsCompleted' ] ) ) {
							$mplus = '15';
						} elseif ( in_array( '11185', $data[ 'achievements' ][ 'achievementsCompleted' ] ) ) {
							$mplus = '10';
						}
						elseif ( in_array( '11184', $data[ 'achievements' ][ 'achievementsCompleted' ] ) ) {
							$mplus = '5';
						}
						elseif ( in_array( '11183', $data[ 'achievements' ][ 'achievementsCompleted' ] ) ) {
							$mplus = '2';
						}

						// DUNGEONS
						$key_cen_normal = array_search( '36204', $data[ 'achievements' ][ 'criteria' ] );
						$key_cen_heroic = array_search( '36215', $data[ 'achievements' ][ 'criteria' ] );
						$key_cen_mythic = array_search( '36216', $data[ 'achievements' ][ 'criteria' ] );

						// REPUTATION
						$key_highmountain = array_search( '30497', $data[ 'achievements' ][ 'criteria' ] );
						$key_valsharah = array_search( '30500', $data[ 'achievements' ][ 'criteria' ] );
						$key_suramar = array_search( '30499', $data[ 'achievements' ][ 'criteria' ] );
						$key_stormheim = array_search( '30501', $data[ 'achievements' ][ 'criteria' ] );
						$key_azsuna = array_search( '30498', $data[ 'achievements' ][ 'criteria' ] );
						$key_wardens = array_search( '30496', $data[ 'achievements' ][ 'criteria' ] );
						$key_legionfall = array_search( '35977', $data[ 'achievements' ][ 'criteria' ] );

						// MYTHIC PLUS NUMBERS
						$key_mythicplus2 = array_search( '33096', $data[ 'achievements' ][ 'criteria' ] );
						$key_mythicplus5 = array_search( '33097', $data[ 'achievements' ][ 'criteria' ] );
						$key_mythicplus10 = array_search( '33098', $data[ 'achievements' ][ 'criteria' ] );
						$key_mythicplus15 = array_search( '32028', $data[ 'achievements' ][ 'criteria' ] );

						// ARTIFACT POWER, LEVEL AND KNOWLEDGE
						$key_artifactpower = array_search( '30103', $data[ 'achievements' ][ 'criteria' ] );
						$key_artifactlevel = array_search( '29395', $data[ 'achievements' ][ 'criteria' ] );
						$key_artifactknowledge = array_search( '31466', $data[ 'achievements' ][ 'criteria' ] );
						$key_worldquests = array_search( '33094', $data[ 'achievements' ][ 'criteria' ] );

						$criterias = array();
						array_push( $criterias, $data[ 'achievements' ][ 'criteriaQuantity' ] );
						$criterias = $criterias[ '0' ];

						$mythic_plus2 = $criterias[ $key_mythicplus2 ];
						$mythic_plus5 = $criterias[ $key_mythicplus5 ];
						$mythic_plus10 = $criterias[ $key_mythicplus10 ];
						$mythic_plus15 = $criterias[ $key_mythicplus15 ];
						$artifact_power = $criterias[ $key_artifactpower ];
						$artifact_level = $traits - 3;
						$artifact_knowledge = $criterias[ $key_artifactknowledge ];
						$world_quests = $criterias[ $key_worldquests ];
						$rep_suramar = $criterias[ $key_suramar ];
						$rep_highmountain = $criterias[ $key_highmountain ];
						$rep_valsharah = $criterias[ $key_valsharah ];
						$rep_stormheim = $criterias[ $key_stormheim ];
						$rep_azsuna = $criterias[ $key_azsuna ];
						$rep_wardens = $criterias[ $key_wardens ];
						$rep_legionfall = $criterias[ $key_legionfall ];
						if ( $key_cen_normal != '' ) {
							$cen_normal = $criterias[ $key_cen_normal ];
						} else {
							$cen_normal = '0';
						}

						if ( $key_cen_heroic != '' ) {
							$cen_heroic = $criterias[ $key_cen_heroic ];
						} else {
							$cen_heroic = '0';
						}

						if ( $key_cen_mythic != '' ) {
							$cen_mythic = $criterias[ $key_cen_mythic ];
						} else {
							$cen_mythic = '0';
						}


						// GENERAL INFORMATION
						$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `logout` = '" . $logout . "', `updated` = '" . time( 'now' ) . "', `talents` = '" . $talent_calc_var . "' WHERE `name` = '" . $character . "'" );

						$general_table = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_general` SET
						`ilvl_on` = '" . $ilvlaverage . "',
						`ilvl_off` = '" . $ilvlaveragebags . "',
						`alvl` = '" . $artifact_level . "',
						`ak` = '" . $artifact_knowledge . "',
						`ap` = '" . $artifact_power . "',
						`m2` = '" . $mythic_plus2 . "',
						`m5` = '" . $mythic_plus5 . "',
						`m10` = '" . $mythic_plus10 . "',
						`m15` = '" . $mythic_plus15 . "',
						`m_achv` = '" . $mplus . "',
						`wq` = '" . $world_quests . "',
						`rep_nightfallen` = '" . $rep_suramar . "',
						`rep_valarjar` = '" . $rep_stormheim . "',
						`rep_wardens` = '" . $rep_wardens . "',
						`rep_farondis` = '" . $rep_azsuna . "',
						`rep_highmountain` = '" . $rep_highmountain . "',
						`rep_dreamweaver` = '" . $rep_valsharah . "',
						`rep_legionfall` = '" . $rep_legionfall . "'
						WHERE `id` = '1'" );

						// CREATE PAST TABLE
						$past_table = mysqli_query( $stream, "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_past` (`id` int(11) NOT NULL AUTO_INCREMENT, `timestamp` int(10) NOT NULL, `ilvl_on` mediumint(4) NOT NULL, `ilvl_bags` mediumint(4) NOT NULL, `ap` bigint(16) NOT NULL, `wq` mediumint(5) NOT NULL, `m2` smallint(4) NOT NULL, `m5` smallint(4) NOT NULL, `m10` smallint(4) NOT NULL, `m15` smallint(4) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;" );

						// MAX 30 ENTRIES						
						$rows = mysqli_num_rows( mysqli_query( $stream, "SELECT * FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_past`" ) );
						if ( $rows > '30' ) {
							$delete = mysqli_query( $stream, "DELETE * FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_past` ORDER BY `id` DESC LIMIT 30,18446744073709551615" );
						}

						// PREVENT DATA INSERTION WHEN PLAYER LOGGED ON AND OFF AGAIN AND DIDN'T DO ANYTHING (HELLO LEGION APP)
						$previous_update = mysqli_fetch_array( mysqli_query( $stream, "SELECT * FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_past` ORDER BY `id` DESC LIMIT 1" ) );

						if (
							$previous_update[ 'ilvl_on' ] < $ilvlaverage ||
							$previous_update[ 'ilvl_bags' ] < $ilvlaveragebags ||
							$previous_update[ 'ap' ] < $artifact_power ||
							$previous_update[ 'm2' ] < $mythic_plus2 ||
							$previous_update[ 'm5' ] < $mythic_plus5 ||
							$previous_update[ 'm10' ] < $mythic_plus10 ||
							$previous_update[ 'm15' ] < $mythic_plus15 ||
							$previous_update[ 'wq' ] < $world_quests ) {

							$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_past` (`timestamp`, `ilvl_on`, `ilvl_bags`, `ap`, `wq`, `m2`, `m5`, `m10`, `m15`) VALUES ('" . $old[ 'logout' ] . "', '" . $past_values[ 'ilvl_on' ] . "', '" . $past_values[ 'ilvl_off' ] . "', '" . $past_values[ 'ap' ] . "', '" . $past_values[ 'wq' ] . "', '" . $past_values[ 'm2' ] . "' , '" . $past_values[ 'm5' ] . "', '" . $past_values[ 'm10' ] . "', '" . $past_values[ 'm15' ] . "')" );
						}

						// EQUIP INSERTION
						$items = array( '1' => 'head', '2' => 'neck', '3' => 'shoulder', '4' => 'back', '5' => 'chest', '6' => 'wrist', '7' => 'hands', '8' => 'waist', '9' => 'legs', '10' => 'feet', '11' => 'finger1', '12' => 'finger2', '13' => 'trinket1', '14' => 'trinket2' );

						foreach ( $items as $id => $item ) {

							$insert = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_equip` SET
							`itemid` = '" . $ {
								'' . $item . '_id'
							} . "',
							`itemlevel` = '" . $ {
								'' . $item . '_ilvl'
							} . "',
							`bonus` = '" . $ {
								'' . $item . '_bonus'
							} . "',
							`enchant` = '" . $ {
								'' . $item . '_ench'
							} . "',
							`gem` = '" . $ {
								'' . $item . '_gem0'
							} . "'
							WHERE `id` = '" . $id . "'" );

						}

						// LEGENDARIES INSERTION
						foreach ( $legendary as $item ) {
							$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_legendaries` (`item_id`) VALUES('" . $item . "')" );
						}

						// WEAPONS INSERTION
						if ( $mhrelic1 != '' ) {

							$old_id = mysqli_fetch_array( mysqli_query( $stream, "SELECT `id` FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_weapons` LIMIT 1" ) );

							$update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_weapons` SET
							`item_id` = '" . $weapon[ 'weapon_id' ] . "',
							`itemlevel` = '" . $mhilvl . "',
							`bonus` = '" . $mh_bonus . "',
							`r1` = '" . $mhrelic0 . "', 
							`bonus_r1` = '" . $mhrelicbonus0 . "',
							`r2`= '" . $mhrelic1 . "',
							`bonus_r2` = '" . $mhrelicbonus1 . "',
							`r3` = '" . $mhrelic2 . "',
							`bonus_r3` = '" . $mhrelicbonus2 . "'
							WHERE `id` = '" . $old_id[ 'id' ] . "'" );

						} elseif ( $ohrelic1 != '' ) {

							$old_id = mysqli_fetch_array( mysqli_query( $stream, "SELECT `id` FROM `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_weapons` LIMIT 1" ) );

							$update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_weapons` SET
							`item_id` = '" . $weapon[ 'weapon_id' ] . "',
							`itemlevel` = '" . $ohilvl . "',
							`bonus` = '" . $oh_bonus . "',
							`r1` = '" . $ohrelic0 . "', 
							`bonus_r1` = '" . $ohrelicbonus0 . "',
							`r2`= '" . $ohrelic1 . "',
							`bonus_r2` = '" . $ohrelicbonus1 . "',
							`r3` = '" . $ohrelic2 . "',
							`bonus_r3` = '" . $ohrelicbonus2 . "'
							WHERE `id` = '" . $old_id[ 'id' ] . "'" );

						}

						// RAID INSERTION

						$raid_1_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_1 . "', `normal` = '" . $en_normal_1 . "', `heroic` = '" . $en_heroic_1 . "', `mythic` = '" . $en_mythic_1 . "' WHERE `id` = '1'" );
						$raid_1_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_2 . "', `normal` = '" . $en_normal_2 . "', `heroic` = '" . $en_heroic_2 . "', `mythic` = '" . $en_mythic_2 . "' WHERE `id` = '2'" );
						$raid_1_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_3 . "', `normal` = '" . $en_normal_3 . "', `heroic` = '" . $en_heroic_3 . "', `mythic` = '" . $en_mythic_3 . "' WHERE `id` = '3'" );
						$raid_1_update_4 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_4 . "', `normal` = '" . $en_normal_4 . "', `heroic` = '" . $en_heroic_4 . "', `mythic` = '" . $en_mythic_4 . "' WHERE `id` = '4'" );
						$raid_1_update_5 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_5 . "', `normal` = '" . $en_normal_5 . "', `heroic` = '" . $en_heroic_5 . "', `mythic` = '" . $en_mythic_5 . "' WHERE `id` = '5'" );
						$raid_1_update_6 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_6 . "', `normal` = '" . $en_normal_6 . "', `heroic` = '" . $en_heroic_6 . "', `mythic` = '" . $en_mythic_6 . "' WHERE `id` = '6'" );
						$raid_1_update_7 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_1` SET `lfr` = '" . $en_lfr_7 . "', `normal` = '" . $en_normal_7 . "', `heroic` = '" . $en_heroic_7 . "', `mythic` = '" . $en_mythic_7 . "' WHERE `id` = '7'" );

						$raid_2_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_1 . "', `normal` = '" . $tov_normal_1 . "', `heroic` = '" . $tov_heroic_1 . "', `mythic` = '" . $tov_mythic_1 . "' WHERE `id` = '1'" );
						$raid_2_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_2 . "', `normal` = '" . $tov_normal_2 . "', `heroic` = '" . $tov_heroic_2 . "', `mythic` = '" . $tov_mythic_2 . "' WHERE `id` = '2'" );
						$raid_2_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_3 . "', `normal` = '" . $tov_normal_3 . "', `heroic` = '" . $tov_heroic_3 . "', `mythic` = '" . $tov_mythic_3 . "' WHERE `id` = '3'" );

						$raid_3_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_1 . "', `normal` = '" . $nh_normal_1 . "', `heroic` = '" . $nh_heroic_1 . "', `mythic` = '" . $nh_mythic_1 . "' WHERE `id` = '1'" );
						$raid_3_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_2 . "', `normal` = '" . $nh_normal_2 . "', `heroic` = '" . $nh_heroic_2 . "', `mythic` = '" . $nh_mythic_2 . "' WHERE `id` = '2'" );
						$raid_3_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_3 . "', `normal` = '" . $nh_normal_3 . "', `heroic` = '" . $nh_heroic_3 . "', `mythic` = '" . $nh_mythic_3 . "' WHERE `id` = '3'" );
						$raid_3_update_4 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_4 . "', `normal` = '" . $nh_normal_4 . "', `heroic` = '" . $nh_heroic_4 . "', `mythic` = '" . $nh_mythic_4 . "' WHERE `id` = '4'" );
						$raid_3_update_5 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_5 . "', `normal` = '" . $nh_normal_5 . "', `heroic` = '" . $nh_heroic_5 . "', `mythic` = '" . $nh_mythic_5 . "' WHERE `id` = '5'" );
						$raid_3_update_6 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_6 . "', `normal` = '" . $nh_normal_6 . "', `heroic` = '" . $nh_heroic_6 . "', `mythic` = '" . $nh_mythic_6 . "' WHERE `id` = '6'" );
						$raid_3_update_7 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_7 . "', `normal` = '" . $nh_normal_7 . "', `heroic` = '" . $nh_heroic_7 . "', `mythic` = '" . $nh_mythic_7 . "' WHERE `id` = '7'" );
						$raid_3_update_8 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_8 . "', `normal` = '" . $nh_normal_8 . "', `heroic` = '" . $nh_heroic_8 . "', `mythic` = '" . $nh_mythic_8 . "' WHERE `id` = '8'" );
						$raid_3_update_9 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_9 . "', `normal` = '" . $nh_normal_9 . "', `heroic` = '" . $nh_heroic_9 . "', `mythic` = '" . $nh_mythic_9 . "' WHERE `id` = '9'" );
						$raid_3_update_10 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_10 . "', `normal` = '" . $nh_normal_10 . "', `heroic` = '" . $nh_heroic_10 . "', `mythic` = '" . $nh_mythic_10 . "' WHERE `id` = '10'" );

						#$raid_4_update_1 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_1. "', `normal` = '" .$tos_normal_1. "', `heroic` = '" .$tos_heroic_1. "', `mythic` = '" .$tos_mythic_1. "' WHERE `id` = '1'");
						#$raid_4_update_2 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_2. "', `normal` = '" .$tos_normal_2. "', `heroic` = '" .$tos_heroic_2. "', `mythic` = '" .$tos_mythic_2. "' WHERE `id` = '2'");
						#$raid_4_update_3 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_3. "', `normal` = '" .$tos_normal_3. "', `heroic` = '" .$tos_heroic_3. "', `mythic` = '" .$tos_mythic_3. "' WHERE `id` = '3'");
						#$raid_4_update_4 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_4. "', `normal` = '" .$tos_normal_4. "', `heroic` = '" .$tos_heroic_4. "', `mythic` = '" .$tos_mythic_4. "' WHERE `id` = '4'");
						#$raid_4_update_5 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_5. "', `normal` = '" .$tos_normal_5. "', `heroic` = '" .$tos_heroic_5. "', `mythic` = '" .$tos_mythic_5. "' WHERE `id` = '5'");
						#$raid_4_update_6 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_6. "', `normal` = '" .$tos_normal_6. "', `heroic` = '" .$tos_heroic_6. "', `mythic` = '" .$tos_mythic_6. "' WHERE `id` = '6'");
						#$raid_4_update_7 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_7. "', `normal` = '" .$tos_normal_7. "', `heroic` = '" .$tos_heroic_7. "', `mythic` = '" .$tos_mythic_7. "' WHERE `id` = '7'");
						#$raid_4_update_8 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_8. "', `normal` = '" .$tos_normal_8. "', `heroic` = '" .$tos_heroic_8. "', `mythic` = '" .$tos_mythic_8. "' WHERE `id` = '8'");
						#$raid_4_update_9 = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_9. "', `normal` = '" .$tos_normal_9. "', `heroic` = '" .$tos_heroic_9. "', `mythic` = '" .$tos_mythic_9. "' WHERE `id` = '9'");


						// DUNGEON INSERTION

						$dungeon_1_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $brh_normal . "', `heroic` = '" . $brh_heroic . "', `mythic` = '" . $brh_mythic . "' WHERE `id` = '1')" );
						$dungeon_2_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $cen_normal . "', `heroic` = '" . $cen_heroic . "', `mythic` = '" . $cen_mythic . "' WHERE `id` = '2')" );
						$dungeon_3_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `mythic` = '" . $cos_mythic . "' WHERE `id` = '3')" );
						$dungeon_4_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $dht_normal . "', `heroic` = '" . $dht_heroic . "', `mythic` = '" . $dht_mythic . "' WHERE `id` = '4')" );
						$dungeon_5_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $eoa_normal . "', `heroic` = '" . $eoa_heroic . "', `mythic` = '" . $eoa_mythic . "' WHERE `id` = '5')" );
						$dungeon_6_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $hov_normal . "', `heroic` = '" . $hov_heroic . "', `mythic` = '" . $hov_mythic . "' WHERE `id` = '6')" );
						#$dungeon_7_update = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_dungeons` SET `mythic` = '" .$lkz_mythic. "' WHERE `id` = '7')");
						$dungeon_8_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $mos_normal . "', `heroic` = '" . $mos_heroic . "', `mythic` = '" . $mos_mythic . "' WHERE `id` = '8')" );
						$dungeon_9_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $nl_normal . "', `heroic` = '" . $nl_heroic . "', `mythic` = '" . $nl_mythic . "' WHERE `id` = '9')" );
						$dungeon_10_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `mythic` = '" . $arc_mythic . "' WHERE `id` = '10')" );
						$dungeon_11_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $vh_normal . "', `heroic` = '" . $vh_heroic . "', `mythic` = '" . $vh_mythic . "' WHERE `id` = '11')" );
						#$dungeon_12_update = mysqli_query($stream, "UPDATE `" .$_SESSION['table']. "_" .$char_id['id']. "_dungeons` SET `mythic` = '" .$ukz_mythic. "' WHERE `id` = '12')");
						$dungeon_13_update = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_dungeons` SET `normal` = '" . $votw_normal . "', `heroic` = '" . $votw_heroic . "', `mythic` = '" . $votw_mythic . "' WHERE `id` = '13')" );

						// EQ CALCULATION

						include( 'eq.php' );

						////////
						// WARCRAFTLOGS API
						////////

						// INTERNAL WLOGS ZONE IDs
						$zones = array( '10' => 'Emerald Nightmare', '11' => 'The Nighthold', '12' => 'Trial of Valor', '13' => 'Tomb of Sargeras' );

						// MAKE CURRENT LOGS ROLE NOT SPEC DEPENDANT
						$primary_role = mysqli_fetch_array( mysqli_query( $stream, "SELECT `role1` FROM `" . $table_name . "` WHERE `name` = '" . $character . "'" ) );

						if ( $primary_role[ 'role1' ] == '0' || $primary_role[ 'role1' ] == '1' || $primary_role[ 'role1' ] == '2' ) {
							$metric = 'dps';
						} elseif ( $primary_role[ 'role1' ] == '3' ) {
							// FETCH HPS STATS FOR HEALER
							$metric = 'hps';
						}

						$key = mysqli_fetch_array( mysqli_query( $stream, "SELECT `wlogs_key` FROM `ovw_api` WHERE `id` = '1'" ) );
						
						if($_SESSION[ 'realm' ] == 'ревущий-фьорд') {
							$wlogs_debug_realm = 'ревущии-фьорд';
						}
						elseif($_SESSION[ 'realm' ] == 'Ясеневый лес') {
							$wlogs_debug_realm = 'Ясеневыи-лес';
						}
						elseif($_SESSION[ 'realm' ] == 'Разувий') {
							$wlogs_debug_realm = 'Разувии';
						}
						elseif($_SESSION[ 'realm' ] == 'Черный Шрам') {
							$wlogs_debug_realm = 'Черныи-Шрам';
						}
						elseif($_SESSION[ 'realm' ] == 'Борейская тундра') {
							$wlogs_debug_realm = 'Бореиская-тундра';
						}
						elseif($_SESSION[ 'realm' ] == 'Confrérie du Thorium') {
							$wlogs_debug_realm = 'Confrerie-du-Thorium';
						}
						elseif($_SESSION[ 'realm' ] == 'Chants éternels') {
							$wlogs_debug_realm = 'Chants-eternels';
						}
						elseif($_SESSION[ 'realm' ] == 'La Croisade écarlate') {
							$wlogs_debug_realm = 'La-Croisade-ecarlate';
						}
						else {
							$wlogs_debug_realm = $_SESSION[ 'realm' ];
						}
						
						foreach ( $zones as $zone_id => $name ) {

							$url = 'https://www.warcraftlogs.com/v1/parses/character/' . $character . '/' . $wlogs_debug_realm . '/' . $_SESSION[ 'region' ] . '?zone=' . $zone_id . '&metric=' . $metric . '&api_key=' . $key[ 'wlogs_key' ] . '';

							$arrContextOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, ), );

							$data = @file_get_contents( $url, false, stream_context_create( $arrContextOptions ) );

							if ( $data != '' ) {
								$data = json_decode( $data, true );

								foreach ( $data as $encounter ) {
									if ( $zone_id == '10' ) {
										// INTERNAL TABLE CODE
										$internal_table_var = '1';
										// ITERATE THROUGH BOSSES
										$encounter_names = array( "Nythendra", "Elerethe Renferal", "Il'gynoth, Heart of Corruption", "Ursoc", "Dragons of Nightmare", "Cenarius", "Xavius" );
									} elseif ( $zone_id == '11' ) {
										$internal_table_var = '3';
										$encounter_names = array( "Skorpyron", "Chronomatic Anomaly", "Trilliax", "Spellblade Aluriel", "Star Augur Etraeus", "High Botanist Tel'arn", "Krosus", "Tichondrius", "Grand Magistrix Elisande", "Gul'dan" );
									}
									elseif ( $zone_id == '12' ) {
										$internal_table_var = '2';
										$encounter_names = array( 'Odyn', 'Guarm', 'Helya' );
									}
									elseif ( $zone_id == '13' ) {
										$internal_table_var = '4';
										$encounter_names = array( "Goroth", "Demonic Inquisition", "Harjatan the Bludger", "Mistress Sassz'ine", "Sisters of the Moon", "The Desolate Host", "Maiden of Vigilance", "Fallen Avatar", "Kil'jaeden" );
									}

									if ( in_array( $encounter[ 'name' ], $encounter_names ) ) {
										$wlogs_id_check = mysqli_fetch_array( mysqli_query( $stream, "SELECT `wlogs_id` FROM `" . $table_name . "` WHERE `name` = '" . $character . "'" ) );
										if ( $wlogs_id_check[ 'wlogs_id' ] == '0' ) {
											// UNIQUE WLOGS CHAR ID
											$wlogs_id = $data[ '0' ][ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'character_id' ];
											$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `wlogs_id` = '" . $wlogs_id . "' WHERE `name` = '" . $character . "'" );
										}

										// CONVERT WARCRAFTLOGS BOSS NAMES TO DB CONFORM VALUES
										if ( $encounter[ 'name' ] == "Il'gynoth, Heart of Corruption" ) {
											$encounter[ 'name' ] = "Il'gynoth";
										} elseif ( $encounter[ 'name' ] == 'Grand Magistrix Elisande' ) {
											$encounter[ 'name' ] = 'Elisande';
										}

										// CONVERT WARCRAFTLOGS DIFFICULTY TO DB CONFORM VALUES									
										if ( $encounter[ 'difficulty' ] == '1' ) {
											$dif = 'lfr';
										} elseif ( $encounter[ 'difficulty' ] == '3' ) {
											$dif = 'normal';
										}
										elseif ( $encounter[ 'difficulty' ] == '4' ) {
											$dif = 'heroic';
										}
										elseif ( $encounter[ 'difficulty' ] == '5' ) {
											$dif = 'mythic';
										}

										$query = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $char_id[ 'id' ] . "_raid_" . $internal_table_var . "` SET `" . $dif . "_parse` = '" . round( $encounter[ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'percent' ], 0 ) . "', `" . $dif . "_log` = '" . $encounter[ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'report_code' ] . "' WHERE `name` = '" . addslashes( $encounter[ 'name' ] ) . "'" );
									}
								}
							}
						}

						echo '<span style="color: yellowgreen; text-align: center; font-size: 20px;">Character successfully updated! Will reload page in 5 seconds.</span>';

					} else {
						$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `updated` = '" . time( 'now' ) . "' WHERE `name` = '" . $character . "'" );

						echo '<span style="color: coral; text-align: center; font-size: 20px;">According to the Armory, the character has not logged out since the last update.<br />Refreshing page in 5 seconds.</span>';
					}
				} else {
					echo '<span style="color: coral; text-align: center; font-size: 20px;">According to the Armory, the character ' . $character . ' is not level 110.<br />Possible reasons: transfer, rename or simply the Armory bugged out.<br />Refreshing page in 5 seconds.</span>';
				}
			} else {
				echo '<span style="color: coral; text-align: center; font-size: 20px;">According to the Armory, the character ' . $character . ' is no longer in your guild.<br />Refreshing page in 5 seconds.</span>';
			}
		} else {
			echo '<span style="color: coral; text-align: center; font-size: 20px;">According to the Armory, this character can no longer be found.<br />Possible reasons: transfer, rename, inactivity or simply the Armory being under maintenance.<br />Refreshing page in 5 seconds.</span>';
		}
	} else {
		echo '<span style="color: coral; text-align: center; font-size: 20px;">The character has been updated within the last 5 minutes, please wait ' . ( 300 - $timediff ) . ' seconds before trying to update again.<br />Refreshing page in 5 seconds.</span>';
	}
}

?>