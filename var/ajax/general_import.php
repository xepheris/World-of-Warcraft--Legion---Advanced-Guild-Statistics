<?php

session_start();

include( 'stream.php' );

$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

$key = mysqli_fetch_array( mysqli_query( $stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'" ) );

$escaped_session_guild_name = str_replace( ' ', '%20', $_SESSION[ 'guild' ] );
$actual_realm_name = mysqli_fetch_array( mysqli_query( $stream, "SELECT `short` FROM `ovw_realms` WHERE `id` = '" . $_GET[ 'realm' ] . "'" ) );

$old = mysqli_fetch_array( mysqli_query( $stream, "SELECT `updated`, `realm` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'character' ] . "'" ) );

$name = mysqli_fetch_array( mysqli_query( $stream, "SELECT `name` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'character' ] . "'" ) );

$url = 'https://' . $_SESSION[ 'region' ] . '.api.battle.net/wow/character/' . $actual_realm_name[ 'short' ] . '/' . $name[ 'name' ] . '?fields=guild,items,statistics,achievements,talents&locale=en_GB&apikey=' . $key[ 'wow_key' ] . '';

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

			// CREATE INDIVIDUAL CHARACTER TABLES
			$legendary_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_legendaries` (`id` int(11) NOT NULL AUTO_INCREMENT, `item_id` int(6) NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `item_id` (`item_id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$weapon_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_weapons` (`id` int(11) NOT NULL AUTO_INCREMENT, `item_id` mediumint(6) NOT NULL, `itemlevel` mediumint(4) NOT NULL, `bonus` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r1` mediumint(6) NOT NULL, `bonus_r1` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r2` mediumint(6) NOT NULL, `bonus_r2` varchar(20) COLLATE latin1_german2_ci NOT NULL, `r3` mediumint(6) NOT NULL, `bonus_r3` varchar(20) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=3";

			$dungeons_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id` int(11) NOT NULL AUTO_INCREMENT, `normal` mediumint(5) NOT NULL, `heroic` mediumint(5) NOT NULL, `mythic` mediumint(5) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$equip_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_equip` (`id` int(11) NOT NULL AUTO_INCREMENT, `itemid` mediumint(6) NOT NULL, `itemlevel` smallint(4) NOT NULL, `bonus` varchar(20) COLLATE latin1_german2_ci NOT NULL, `enchant` mediumint(6) NOT NULL, `gem` mediumint(6) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;";

			$general_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_general` (`id` int(11) NOT NULL AUTO_INCREMENT, `ilvl_on` mediumint(4) NOT NULL, `ilvl_off` mediumint(4) NOT NULL, `alvl` smallint(3) NOT NULL, `ak` smallint(2) NOT NULL, `ap` bigint(16) NOT NULL, `m2` smallint(4) NOT NULL, `m5` smallint(4) NOT NULL, `m10` smallint(4) NOT NULL, `m15` smallint(4) NOT NULL, `m_achv` smallint(2) NOT NULL, `wq` mediumint(5) NOT NULL, `rep_nightfallen` int(6) NOT NULL, `rep_valarjar` int(6) NOT NULL, `rep_wardens` int(6) NOT NULL, `rep_farondis` int(6) NOT NULL, `rep_highmountain` int(6) NOT NULL, `rep_dreamweaver` int(6) NOT NULL, `rep_legionfall` int(6) NOT NULL, UNIQUE KEY `id` (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=1 ;";

			$raid_1_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$raid_2_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_2` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$raid_3_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$raid_4_table = "CREATE TABLE IF NOT EXISTS `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_4` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(32) COLLATE latin1_german2_ci NOT NULL, `lfr` mediumint(5) NOT NULL, `lfr_parse` smallint(3) NOT NULL, `lfr_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `normal` mediumint(5) NOT NULL, `normal_parse` smallint(3) NOT NULL, `normal_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `heroic` mediumint(5) NOT NULL, `heroic_parse` smallint(3) NOT NULL, `heroic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, `mythic` mediumint(5) NOT NULL, `mythic_parse` smallint(3) NOT NULL, `mythic_log` varchar(16) COLLATE latin1_german2_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=2 ;";

			$en_boss = array( "Nythendra", "Elerethe Renferal", "Il'gynoth", "Ursoc", "Dragons of Nightmare", "Cenarius", "Xavius" );
			$tov_boss = array( "Odyn", "Guarm", "Helya" );
			$nh_boss = array( "Skorpyron", "Chronomatic Anomaly", "Trilliax", "Spellblade Aluriel", "Star Augur Etraeus", "High Botanist Tel'arn", "Tichondrius", "Krosus", "Elisande", "Gul'dan" );
			$tos_boss = array( "Goroth", "Demonic Inquisition", "Harjatan the Bludger", "Mistress Sassz'ine", "Sisters of the Moon", "The Desolate Host", "Maiden of Vigilance", "Fallen Avatar", "Kil'jaeden" );

			$sql_array = array( $legendary_table, $weapon_table, $dungeons_table, $raid_1_table, $raid_2_table, $raid_3_table, $raid_4_table, $equip_table, $general_table );

			foreach ( $sql_array as $sql ) {
				$execute = mysqli_query( $stream, $sql );
			}

			$k = '1';
			foreach ( $en_boss as $boss ) {
				$raid_1_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` (`id`, `name`) VALUES ('" . $k . "', '" . addslashes( $boss ) . "')" );
				$k++;
			}

			$k = '1';
			foreach ( $tov_boss as $boss ) {
				$raid_2_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_2` (`id`, `name`) VALUES ('" . $k . "', '" . addslashes( $boss ) . "')" );
				$k++;
			}

			$k = '1';
			foreach ( $nh_boss as $boss ) {
				$raid_3_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` (`id`, `name`) VALUES ('" . $k . "', '" . addslashes( $boss ) . "')" );
				$k++;
			}

			$k = '1';
			foreach ( $tos_boss as $boss ) {
				$raid_4_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_4` (`id`, `name`) VALUES ('" . $k . "', '" . addslashes( $boss ) . "')" );
				$k++;
			}

			// LAST LOGOUT
			$logout = substr( $data[ 'lastModified' ], '0', '10' );

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

			$update = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `spec` = '" . $fetch_spec_var[ 'id' ] . "' WHERE `id` = '" . $_GET[ 'character' ] . "'" );

			$class = $data[ 'class' ];

			// FETCH GENERAL INFORMATION
			$realm = $data[ 'realm' ];

			$realm = mysqli_fetch_array( mysqli_query( $stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" . $_SESSION[ 'region' ] . "' AND `short` = '" . $actual_realm_name[ 'short' ] . "'" ) );

			$realm = $realm[ 'id' ];

			$update = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `realm` = '" . $realm . "', `status` = '0', `class` = '" . $class . "'	WHERE `id` = '" . $_GET[ 'character' ] . "'" );

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
			$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `logout` = '" . $logout . "', `updated` = '" . time( 'now' ) . "', `talents` = '" . $talent_calc_var . "' WHERE `id` = '" . $_GET[ 'character' ] . "'" );

			$general_table = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_general` (`ilvl_on`, `ilvl_off`, `alvl`, `ak`, `ap`, `m2`, `m5`, `m10`, `m15`, `m_achv`, `wq`, `rep_nightfallen`, `rep_valarjar`, `rep_wardens`, `rep_farondis`, `rep_highmountain`, `rep_dreamweaver`, `rep_legionfall`) VALUES ('" . $ilvlaverage . "', '" . $ilvlaveragebags . "', '" . $artifact_level . "', '" . $artifact_knowledge . "', '" . $artifact_power . "', '" . $mythic_plus2 . "', '" . $mythic_plus5 . "', '" . $mythic_plus10 . "', '" . $mythic_plus15 . "', '" . $mplus . "', '" . $world_quests . "', '" . $rep_suramar . "', '" . $rep_stormheim . "', '" . $rep_wardens . "', '" . $rep_azsuna . "', '" . $rep_highmountain . "', '" . $rep_valsharah . "', '" . $rep_legionfall . "');" );

			// EQUIP INSERTION
			$items = array( '1' => 'head', '2' => 'neck', '3' => 'shoulder', '4' => 'back', '5' => 'chest', '6' => 'wrist', '7' => 'hands', '8' => 'waist', '9' => 'legs', '10' => 'feet', '11' => 'finger1', '12' => 'finger2', '13' => 'trinket1', '14' => 'trinket2' );
			foreach ( $items as $id => $item ) {
				$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_equip` (`id`, `itemid`, `itemlevel`, `bonus`, `enchant`, `gem`) VALUES ('" . $id . "', '" . $ {
					'' . $item . '_id'
				} . "', '" . $ {
					'' . $item . '_ilvl'
				} . "', '" . $ {
					'' . $item . '_bonus'
				} . "', '" . $ {
					'' . $item . '_ench'
				} . "', '" . $ {
					'' . $item . '_gem0'
				} . "')" );
			}


			// LEGENDARIES INSERTION
			foreach ( $legendary as $item ) {
				$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_legendaries` (`item_id`) VALUES('" . $item . "')" );
			}

			// WEAPONS INSERTION
			if ( $mhrelic1 != '' ) {
				$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_weapons` (`item_id`, `itemlevel`, `bonus`, `r1`, `bonus_r1`, `r2`, `bonus_r2`, `r3`, `bonus_r3`) VALUES('" . $weapon[ 'weapon_id' ] . "', '" . $mhilvl . "', '" . $mh_bonus . "', '" . $mhrelic0 . "', '" . $mhrelicbonus0 . "', '" . $mhrelic1 . "', '" . $mhrelicbonus1 . "', '" . $mhrelic2 . "', '" . $mhrelicbonus2 . "')" );
			} elseif ( $ohrelic1 != '' ) {
				$insert = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_weapons` (`item_id`, `itemlevel`, `bonus`, `r1`, `bonus_r1`, `r2`, `bonus_r2`, `r3`, `bonus_r3`) VALUES('" . $weapon[ 'weapon_id' ] . "', '" . $ohilvl . "', '" . $oh_bonus . "', '" . $ohrelic0 . "', '" . $ohrelicbonus0 . "', '" . $ohrelic1 . "', '" . $ohrelicbonus1 . "', '" . $ohrelic2 . "', '" . $ohrelicbonus2 . "')" );
			}

			// RAID INSERTION

			$raid_1_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_1 . "', `normal` = '" . $en_normal_1 . "', `heroic` = '" . $en_heroic_1 . "', `mythic` = '" . $en_mythic_1 . "' WHERE `id` = '1'" );
			$raid_1_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_2 . "', `normal` = '" . $en_normal_2 . "', `heroic` = '" . $en_heroic_2 . "', `mythic` = '" . $en_mythic_2 . "' WHERE `id` = '2'" );
			$raid_1_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_3 . "', `normal` = '" . $en_normal_3 . "', `heroic` = '" . $en_heroic_3 . "', `mythic` = '" . $en_mythic_3 . "' WHERE `id` = '3'" );
			$raid_1_update_4 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_4 . "', `normal` = '" . $en_normal_4 . "', `heroic` = '" . $en_heroic_4 . "', `mythic` = '" . $en_mythic_4 . "' WHERE `id` = '4'" );
			$raid_1_update_5 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_5 . "', `normal` = '" . $en_normal_5 . "', `heroic` = '" . $en_heroic_5 . "', `mythic` = '" . $en_mythic_5 . "' WHERE `id` = '5'" );
			$raid_1_update_6 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_6 . "', `normal` = '" . $en_normal_6 . "', `heroic` = '" . $en_heroic_6 . "', `mythic` = '" . $en_mythic_6 . "' WHERE `id` = '6'" );
			$raid_1_update_7 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_1` SET `lfr` = '" . $en_lfr_7 . "', `normal` = '" . $en_normal_7 . "', `heroic` = '" . $en_heroic_7 . "', `mythic` = '" . $en_mythic_7 . "' WHERE `id` = '7'" );

			$raid_2_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_1 . "', `normal` = '" . $tov_normal_1 . "', `heroic` = '" . $tov_heroic_1 . "', `mythic` = '" . $tov_mythic_1 . "' WHERE `id` = '1'" );
			$raid_2_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_2 . "', `normal` = '" . $tov_normal_2 . "', `heroic` = '" . $tov_heroic_2 . "', `mythic` = '" . $tov_mythic_2 . "' WHERE `id` = '2'" );
			$raid_2_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_2` SET `lfr` = '" . $tov_lfr_3 . "', `normal` = '" . $tov_normal_3 . "', `heroic` = '" . $tov_heroic_3 . "', `mythic` = '" . $tov_mythic_3 . "' WHERE `id` = '3'" );

			$raid_3_update_1 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_1 . "', `normal` = '" . $nh_normal_1 . "', `heroic` = '" . $nh_heroic_1 . "', `mythic` = '" . $nh_mythic_1 . "' WHERE `id` = '1'" );
			$raid_3_update_2 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_2 . "', `normal` = '" . $nh_normal_2 . "', `heroic` = '" . $nh_heroic_2 . "', `mythic` = '" . $nh_mythic_2 . "' WHERE `id` = '2'" );
			$raid_3_update_3 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_3 . "', `normal` = '" . $nh_normal_3 . "', `heroic` = '" . $nh_heroic_3 . "', `mythic` = '" . $nh_mythic_3 . "' WHERE `id` = '3'" );
			$raid_3_update_4 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_4 . "', `normal` = '" . $nh_normal_4 . "', `heroic` = '" . $nh_heroic_4 . "', `mythic` = '" . $nh_mythic_4 . "' WHERE `id` = '4'" );
			$raid_3_update_5 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_5 . "', `normal` = '" . $nh_normal_5 . "', `heroic` = '" . $nh_heroic_5 . "', `mythic` = '" . $nh_mythic_5 . "' WHERE `id` = '5'" );
			$raid_3_update_6 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_6 . "', `normal` = '" . $nh_normal_6 . "', `heroic` = '" . $nh_heroic_6 . "', `mythic` = '" . $nh_mythic_6 . "' WHERE `id` = '6'" );
			$raid_3_update_7 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_7 . "', `normal` = '" . $nh_normal_7 . "', `heroic` = '" . $nh_heroic_7 . "', `mythic` = '" . $nh_mythic_7 . "' WHERE `id` = '7'" );
			$raid_3_update_8 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_8 . "', `normal` = '" . $nh_normal_8 . "', `heroic` = '" . $nh_heroic_8 . "', `mythic` = '" . $nh_mythic_8 . "' WHERE `id` = '8'" );
			$raid_3_update_9 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_9 . "', `normal` = '" . $nh_normal_9 . "', `heroic` = '" . $nh_heroic_9 . "', `mythic` = '" . $nh_mythic_9 . "' WHERE `id` = '9'" );
			$raid_3_update_10 = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_3` SET `lfr` = '" . $nh_lfr_10 . "', `normal` = '" . $nh_normal_10 . "', `heroic` = '" . $nh_heroic_10 . "', `mythic` = '" . $nh_mythic_10 . "' WHERE `id` = '10'" );

			#$raid_4_update_1 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_1. "', `normal` = '" .$tos_normal_1. "', `heroic` = '" .$tos_heroic_1. "', `mythic` = '" .$tos_mythic_1. "' WHERE `id` = '1'");
			#$raid_4_update_2 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_2. "', `normal` = '" .$tos_normal_2. "', `heroic` = '" .$tos_heroic_2. "', `mythic` = '" .$tos_mythic_2. "' WHERE `id` = '2'");
			#$raid_4_update_3 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_3. "', `normal` = '" .$tos_normal_3. "', `heroic` = '" .$tos_heroic_3. "', `mythic` = '" .$tos_mythic_3. "' WHERE `id` = '3'");
			#$raid_4_update_4 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_4. "', `normal` = '" .$tos_normal_4. "', `heroic` = '" .$tos_heroic_4. "', `mythic` = '" .$tos_mythic_4. "' WHERE `id` = '4'");
			#$raid_4_update_5 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_5. "', `normal` = '" .$tos_normal_5. "', `heroic` = '" .$tos_heroic_5. "', `mythic` = '" .$tos_mythic_5. "' WHERE `id` = '5'");
			#$raid_4_update_6 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_6. "', `normal` = '" .$tos_normal_6. "', `heroic` = '" .$tos_heroic_6. "', `mythic` = '" .$tos_mythic_6. "' WHERE `id` = '6'");
			#$raid_4_update_7 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_7. "', `normal` = '" .$tos_normal_7. "', `heroic` = '" .$tos_heroic_7. "', `mythic` = '" .$tos_mythic_7. "' WHERE `id` = '7'");
			#$raid_4_update_8 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_8. "', `normal` = '" .$tos_normal_8. "', `heroic` = '" .$tos_heroic_8. "', `mythic` = '" .$tos_mythic_8. "' WHERE `id` = '8'");
			#$raid_4_update_9 = mysqli_query($stream, "UPDATE `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_raid_4` SET `lfr` = '" .$tos_lfr_9. "', `normal` = '" .$tos_normal_9. "', `heroic` = '" .$tos_heroic_9. "', `mythic` = '" .$tos_mythic_9. "' WHERE `id` = '9'");

			// DUNGEON INSERTION

			$dungeon_1_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('1', '" . $brh_normal . "', '" . $brh_heroic . "', '" . $brh_mythic . "')" );
			$dungeon_2_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('2', '" . $cen_normal . "', '" . $cen_heroic . "', '" . $cen_mythic . "')" );
			$dungeon_3_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('3', '0', '0', '" . $cos_mythic . "')" );
			$dungeon_4_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('4', '" . $dht_normal . "', '" . $dht_heroic . "', '" . $dht_mythic . "')" );
			$dungeon_5_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('5', '" . $eoa_normal . "', '" . $eoa_heroic . "', '" . $eoa_mythic . "')" );
			$dungeon_6_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('6', '" . $hov_normal . "', '" . $hov_heroic . "', '" . $hov_mythic . "')" );
			#$dungeon_7_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('7', '0', '0', '" .$lkz_mythic. "')");
			$dungeon_8_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('8', '" . $mos_normal . "', '" . $mos_heroic . "', '" . $mos_mythic . "')" );
			$dungeon_9_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('9', '" . $nl_normal . "', '" . $nl_heroic . "', '" . $nl_mythic . "')" );
			$dungeon_10_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('10', '0', '0', '" . $arc_mythic . "')" );
			$dungeon_11_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('11', '" . $vh_normal . "', '" . $vh_heroic . "', '" . $vh_mythic . "')" );
			#$dungeon_12_insertion = mysqli_query($stream, "INSERT INTO `" .$guild_id['id']. "_" .$fetch_char_id['id']. "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('12', '0', '0', '" .$ukz_mythic. "')");
			$dungeon_13_insertion = mysqli_query( $stream, "INSERT INTO `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_dungeons` (`id`, `normal`, `heroic`, `mythic`) VALUES ('13', '" . $votw_normal . "', '" . $votw_heroic . "', '" . $votw_mythic . "')" );


			// EQ CALCULATION

			$threshold = '2228766330';

			$artifact_knowledge_levels = array( '0' => '1', '1' => '1.25', '2' => '1.5', '3' => '1.9', '4' => '2.4', '5' => '3', '6' => '3.75', '7' => '4.75', '8' => '6', '9' => '7.5', '10' => '9.5', '11' => '12', '12' => '15', '13' => '18.75', '14' => '23.5', '15' => '29.5', '16' => '37', '17' => '46.5', '18' => '58', '19' => '73', '20' => '91', '21' => '114', '22' => '143', '23' => '179', '24' => '224', '25' => '250', '26' => '1000', '27' => '1300', '28' => '1700', '29' => '2200', '30' => '2900', '31' => '3800', '32' => '4900', '33' => '6400', '34' => '8300', '35' => '10800', '36' => '14000', '37' => '18200', '38 ' => '23700', '39' => '30800', '40' => '40000', '41' => '52000', '42' => '67600', '43' => '87900', '44' => '114300', '45' => '148600', '46' => '193200', '47' => '251200', '48' => '326600', '49' => '424500', '50' => '552000' );

			foreach ( $artifact_knowledge_levels as $current_ak => $increase ) {
				if ( $current_ak == $artifact_knowledge ) {
					$ap_per_10_or_higher_key = 600 * $increase * 2;
				}
			}

			$worth = ( ( $threshold / $ap_per_10_or_higher_key ) * 1431 ) / 150;

			if ( $data[ 'class' ] == '11' ) {
				$eq_ap = ( ( ( ( $artifact_power / $threshold ) * $worth ) / 4 ) * 3 ) / 2.5;
			} elseif ( $data[ 'class' ] == '12' ) {
				$eq_ap = ( ( ( ( $artifact_power / $threshold ) * $worth ) / 2 ) * 3 ) / 2.5;
			}
			elseif ( $data[ 'class' ] != '11' && $data[ 'class' ] != '12' ) {
				$eq_ap = ( ( $artifact_power / $threshold ) * $worth ) / 2.5;
			}

			$sum = $brh_mythic + $cen_mythic + $cos_mythic + $dht_mythic + $eoa_mythic + $hov_mythic + $lkz_mythic + $mos_mythic + $nl_mythic + $arc_mythic + $vh_mythic + $ukz_mythic + $votw_mythic;
			$m0 = $sum - $mythic_plus2;
			$m2_to_m5 = $mythic_plus2 - $mythic_plus5;
			$m5_to_m10 = $mythic_plus5 - $mythic_plus10;
			$m10_to_m15 = $mythic_plus10 - $mythic_plus15;
			$m15p = $mythic_plus15;


			if ( $m2_to_m5 < '0' ) {
				$m2_to_m5 = '0';
				$m5_to_m10 = '0';
				$m10_to_m15 = '0';
				$m15p = '0';
			}
			if ( $m5_to_m10 < '0' ) {
				$m5_to_m10 = '0';
				$m10_to_m15 = '0';
				$m15p = '0';
			}
			if ( $m10_to_m15 < '0' ) {
				$m10_to_m15 = '0';
				$m15p = '0';
			}

			// EN BOSSKILLS
			$en_lfr_bosskills = $en_lfr_1 + $en_lfr_2 + $en_lfr_3 + $en_lfr_4 + $en_lfr_5 + $en_lfr_6 + $en_lfr_7;
			$en_n_bosskills = $en_normal_1 + $en_normal_2 + $en_normal_3 + $en_normal_4 + $en_normal_5 + $en_normal_6 + $en_normal_7;
			$en_hc_bosskills = $en_heroic_1 + $en_heroic_2 + $en_heroic_3 + $en_heroic_4 + $en_heroic_5 + $en_heroic_6 + $en_heroic_7;
			$en_m_bosskills = $en_mythic_1 + $en_mythic_2 + $en_mythic_3 + $en_mythic_4 + $en_mythic_5 + $en_mythic_6 + $en_mythic_7;
			// TOV BOSSKILLS
			$tov_lfr_bosskills = $tov_lfr_1 + $tov_lfr_2 + $tov_lfr_3;
			$tov_n_bosskills = $tov_normal_1 + $tov_normal_2 + $tov_normal_3;
			$tov_hc_bosskills = $tov_heroic_1 + $tov_heroic_2 + $tov_heroic_3;
			$tov_m_bosskills = $tov_mythic_1 + $tov_mythic_3 + $tov_mythic_3;
			// NH BOSSKILLS
			$nh_lfr_bosskills = $nh_lfr_1 + $nh_lfr_2 + $nh_lfr_3 + $nh_lfr_4 + $nh_lfr_5 + $nh_lfr_6 + $nh_lfr_7 + $nh_lfr_8 + $nh_lfr_9 + $nh_lfr_10;
			$nh_n_bosskills = $nh_normal_1 + $nh_normal_2 + $nh_normal_3 + $nh_normal_4 + $nh_normal_5 + $nh_normal_6 + $nh_normal_7 + $nh_normal_8 + $nh_normal_9 + $nh_normal_10;
			$nh_hc_bosskills = $nh_heroic_1 + $nh_heroic_2 + $nh_heroic_3 + $nh_heroic_4 + $nh_heroic_5 + $nh_heroic_6 + $nh_heroic_7 + $nh_heroic_8 + $nh_heroic_9 + $nh_heroic_10;
			$nh_m_bosskills = $nh_mythic_1 + $nh_mythic_2 + $nh_mythic_3 + $nh_mythic_4 + $nh_mythic_5 + $nh_mythic_6 + $nh_mythic_7 + $nh_mythic_8 + $nh_mythic_9 + $nh_mythic_10;
			// TOS BOSSKILLS
			$tos_lfr_bosskills = $tos_lfr_1 + $tos_lfr_2 + $tos_lfr_3 + $tos_lfr_4 + $tos_lfr_5 + $tos_lfr_6 + $tos_lfr_7 + $tos_lfr_8 + $tos_lfr_9;
			$tos_n_bosskills = $tos_normal_1 + $tos_normal_2 + $tos_normal_3 + $tos_normal_4 + $tos_normal_5 + $tos_normal_6 + $tos_normal_7 + $tos_normal_8 + $tos_normal_9;
			$tos_hc_bosskills = $tos_heroic_1 + $tos_heroic_2 + $tos_heroic_3 + $tos_heroic_4 + $tos_heroic_5 + $tos_heroic_6 + $tos_heroic_7 + $tos_heroic_8 + $tos_heroic_9;
			$tos_m_bosskills = $tos_mythic_1 + $tos_mythic_2 + $tos_mythic_3 + $tos_mythic_4 + $tos_mythic_5 + $tos_mythic_6 + $tos_mythic_7 + $tos_mythic_8 + $tos_mythic_9;

			$eq_weights = mysqli_fetch_array( mysqli_query( $stream, "SELECT * FROM `ovw_eq` WHERE `id` = '1'" ) );

			$eq = $world_quests * 1 + ( $m0 * $eq_weights[ 'm0' ] ) +
				( $m2_to_m5 * $eq_weights[ 'm2_5' ] ) +
				( $m5_to_m10 * $eq_weights[ 'm5_10' ] ) +
				( $m10_to_m15 * $eq_weights[ 'm10_15' ] ) +
				( $m15p * $eq_weights[ 'm15p' ] ) +
				( $en_lfr_bosskills * $eq_weights[ 'en_lfr' ] ) +
				( $en_n_bosskills * $eq_weights[ 'en_n' ] ) +
				( $en_hc_bosskills * $eq_weights[ 'en_hc' ] ) +
				( $en_m_bosskills * $eq_weights[ 'en_m' ] ) +
				( $tov_lfr_bosskills * $eq_weights[ 'tov_lfr' ] ) +
				( $tov_n_bosskills * $eq_weights[ 'tov_n' ] ) +
				( $tov_hc_bosskills * $eq_weights[ 'tov_hc' ] ) +
				( $tov_m_bosskills * $eq_weights[ 'tov_m' ] ) +
				( $nh_lfr_bosskills * $eq_weights[ 'nh_lfr' ] ) +
				( $nh_n_bosskills * $eq_weights[ 'nh_n' ] ) +
				( $nh_hc_bosskills * $eq_weights[ 'nh_hc' ] ) +
				( $nh_m_bosskills * $eq_weights[ 'nh_m' ] ) +
				( $tos_lfr_bosskills * $eq_weights[ 'tos_lfr' ] ) +
				( $tos_n_bosskills * $eq_weights[ 'tos_n' ] ) +
				( $tos_hc_bosskills * $eq_weights[ 'tos_hc' ] ) +
				( $tos_m_bosskills * $eq_weights[ 'tos_m' ] ) +
				$eq_ap +
				( ( $ilvlaverage - 850 ) * $eq_weights[ 'itemlevel' ] );

			if ( $eq < '0' ) {
				$eq = '0';
			}

			$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `eq` = '" . $eq . "'  WHERE `id` = '" . $_GET[ 'character' ] . "'" );


			////////
			// WARCRAFTLOGS API
			////////

			// INTERNAL WLOGS ZONE IDs
			$zones = array( '10' => 'Emerald Nightmare', '11' => 'The Nighthold', '12' => 'Trial of Valor', '13' => 'Tomb of Sargeras' );

			// MAKE CURRENT LOGS ROLE NOT SPEC DEPENDANT
			$primary_role = mysqli_fetch_array( mysqli_query( $stream, "SELECT `role1` FROM `" . $table_name . "` WHERE `id` = '" . $_GET[ 'character' ] . "'" ) );

			if ( $primary_role[ 'role1' ] == '0' || $primary_role[ 'role1' ] == '1' || $primary_role[ 'role1' ] == '2' ) {
				$metric = 'dps';
			} elseif ( $primary_role[ 'role1' ] == '3' ) {
				// FETCH HPS STATS FOR HEALER
				$metric = 'hps';
			}

			$key = mysqli_fetch_array( mysqli_query( $stream, "SELECT `wlogs_key` FROM `ovw_api` WHERE `id` = '1'" ) );

			foreach ( $zones as $zone_id => $name ) {

				$url = 'https://www.warcraftlogs.com/v1/parses/character/' . $_GET[ 'character' ] . '/' . $_SESSION[ 'realm' ] . '/' . $_SESSION[ 'region' ] . '?zone=' . $zone_id . '&metric=' . $metric . '&api_key=' . $key[ 'wlogs_key' ] . '';

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
							if ( !isset( $wlogs_id ) ) {
								// UNIQUE WLOGS CHAR ID
								$wlogs_id = $data[ '0' ][ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'character_id' ];
								$update_guild_table = mysqli_query( $stream, "UPDATE `" . $table_name . "` SET `wlogs_id` = '" . $wlogs_id . "' WHERE `id` = '" . $_GET[ 'character' ] . "'" );
							}

							if ( $encounter[ 'name' ] == "Il'gynoth, Heart of Corruption" ) {
								$encounter[ 'name' ] = "Il'gynoth";
							} elseif ( $encounter[ 'name' ] == 'Grand Magistrix Elisande' ) {
								$encounter[ 'name' ] = 'Elisande';
							}

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

							$query = mysqli_query( $stream, "UPDATE `" . $_SESSION[ 'table' ] . "_" . $_GET[ 'character' ] . "_raid_" . $internal_table_var . "` SET `" . $dif . "_parse` = '" . round( $encounter[ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'percent' ], 0 ) . "', `" . $dif . "_log` = '" . $encounter[ 'specs' ][ '0' ][ 'data' ][ '0' ][ 'report_code' ] . "' WHERE `name` = '" . addslashes( $encounter[ 'name' ] ) . "'" );
						}
					}
				}
			}

		} else {
			echo '<span style="color: coral; text-align: center;">The character is not level 110.</span><br />';
		}
	} else {
		echo '<span style="color: coral; text-align: center;">The character is no longer in your guild according to the armory.</span><br />';
	}
} else {
	echo '<span style="color: coral; text-align: center;">UNKNOWN ERROR - please report this problem with your guild information to the admin.</span>';
}

$current_chars = mysqli_fetch_array( mysqli_query( $stream, "SELECT COUNT(`id`) AS `current_chars` FROM `" . $table_name . "`" ) );

$sync = mysqli_query( $stream, "UPDATE `ovw_guilds` SET `tracked_chars` = '" . $current_chars[ 'current_chars' ] . "' WHERE `guild_name` = '" . $_SESSION[ 'guild' ] . "'" );

$_SESSION[ 'tracked' ] = $current_chars[ 'current_chars' ];

?>