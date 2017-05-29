<?php
	
session_start();

include('stream.php');

header('Content-type: application/json');


$table_name = '' . $_SESSION[ 'table' ] . '_' . $_SESSION[ 'guild' ] . '_' . $_SESSION[ 'region' ] . '_' . $_SESSION[ 'realm' ] . '';

$rows = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `class` = '0' ORDER BY `name` ASC");
$update_this = array();

while($char_id = mysqli_fetch_array($rows)) {
	array_push($update_this, $char_id['id']);
}

$last_id = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` WHERE `class` = '0' ORDER BY `name` DESC LIMIT 1"));
$last_id = $last_id['id'];

$key = mysqli_fetch_array(mysqli_query($stream, "SELECT `wow_key` FROM `ovw_api` WHERE `id` = '1'"));

$escaped_session_guild_name = str_replace(' ', '%20', $_SESSION['guild']);

$actual_realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `short` FROM `ovw_realms` WHERE `name` = '" .addslashes($_SESSION['realm']). "'"));

// FETCH GUILD MEMBER API
$url = 'https://' .$_SESSION['region']. '.api.battle.net/wow/guild/' .$actual_realm_name['short']. '/' .$escaped_session_guild_name. '?fields=members&locale=en_GB&apikey=' .$key['wow_key']. '';

$arrContextOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, ),);

$data = @file_get_contents($url, false, stream_context_create($arrContextOptions));

$import = array();

if($data != '') {
	$data = json_decode($data, true);
		
	// PARSE GUILD MEMBERS WITH MEMBERS TO IMPORT		
	foreach($update_this as $char_id) {
		foreach($data['members'] as $members) {
			
			$name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `" .$table_name. "` WHERE `id` = '" .$char_id. "'"));
			$name = $name['name'];
						
			if($members['character']['name'] == $name && $members['character']['level'] == '110') {
				$realm = $members['character']['realm'];
				
				if($_SESSION['region'] == 'TW') {
					if($realm == 'Crystalpine Stinger') {
						$realm = '水晶之刺';
					}
					elseif($realm == 'Bleeding Hollow') {
						$realm = '血之谷';
					}
					elseif($realm == 'Chillwind Point') {
						$realm = '冰風崗哨';
					}
					elseif($realm == 'Demon Fall Canyon') {
						$realm = '屠魔山谷';
					}
					elseif($realm == 'Light\'s Hope') {
						$realm = '聖光之願';
					}
					elseif($realm == 'Order of the Cloud Serpent') {
						$realm = '雲蛟衛';
					}
					elseif($realm == 'Sundown Marsh') {
						$realm = '日落沼澤';
					}
					elseif($realm == 'World Tree') {
						$realm = '世界之樹';
					}
					elseif($realm == 'Zealot Blade') {
						$realm = '狂熱之刃';
					}
					elseif($realm == 'Arthas') {
						$realm = '阿薩斯';
					}
					elseif($realm == 'Arygos') {
						$realm = '亞雷戈斯';
					}
					elseif($realm == 'Dragonmaw') {
						$realm = '巨龍之喉';
					}
					elseif($realm == 'Frostmane') {
						$realm = '冰霜之刺';
					}
					elseif($realm == 'Hellscream') {
						$realm = '地獄吼';
					}
					elseif($realm == 'Icecrown') {
						$realm = '寒冰皇冠';
					}
					elseif($realm == 'Menethil') {
						$realm = '米奈希爾';
					}
					elseif($realm == 'Nightsong') {
						$realm = '夜空之歌';
					}
					elseif($realm == 'Quel\'dorei') {
						$realm = '眾星之子';
					}
					elseif($realm == 'Queldorei') {
						$realm = '眾星之子';
					}
					elseif($realm == 'Shadowmoon') {
						$realm = '暗影之月';
					}
					elseif($realm == 'Skywall') {
						$realm = '天空之牆';
					}
					elseif($realm == 'Spirestone') {
						$realm = '尖石';
					}
					elseif($realm == 'Stormscale') {
						$realm = '雷鱗';
					}
					elseif($realm == 'Whisperwind') {
						$realm = '語風';
					}
					elseif($realm == 'Wrathbringer') {
						$realm = '憤怒使者';
					}
				}
				if($_SESSION['region'] == 'KR') {
					if($realm == 'Alexstrasza') {
						$realm = '알렉스트라자';
					}
					elseif($realm == 'Azshara') {
						$realm = '아즈샤라';
					}
					elseif($realm == 'Burning Legion') {
						$realm = '불타는 군단';
					}
					elseif($realm == 'Cenarius') {
						$realm = '세나리우스';
					}
					elseif($realm == 'Dalaran') {
						$realm = '달라란';
					}
					elseif($realm == 'Deathwing') {
						$realm = '데스윙';
					}
					elseif($realm == 'Durotan') {
						$realm = '듀로탄';
					}
					elseif($realm == 'Garona') {
						$realm = '가로나';
					}
					elseif($realm == 'Guldan') {
						$realm = '굴단';
					}
					elseif($realm == 'Gul\'dan') {
						$realm = '굴단';
					}
					elseif($realm == 'Hellscream') {
						$realm = '헬스크림';
					}
					elseif($realm == 'Hyjal') {
						$realm = '하이잘';
					}
					elseif($realm == 'Illidan') {
						$realm = '스톰레이지';
					}
					elseif($realm == 'Malfurion') {
						$realm = '말퓨리온';
					}
					elseif($realm == 'Norgannon') {
						$realm = '노르간논';
					}
					elseif($realm == 'Rexxar') {
						$realm = '렉사르';
					}
					elseif($realm == 'Wildhammer') {
						$realm = '와일드해머';
					}
					elseif($realm == 'Windrunner') {
						$realm = '윈드러너';
					}
					elseif($realm == 'Zul\'jin') {
						$realm = '줄진';
					}
					elseif($realm == 'Zuljin') {
						$realm = '줄진';
					}
				}
												
				$realm = mysqli_fetch_array(mysqli_query($stream, "SELECT `id` FROM `ovw_realms` WHERE `region` = '" .$_SESSION['region']. "' AND `name` = '" .addslashes($realm). "'"));				
				
				$realm = $realm['id']; 
				
				// PUSH INTO ARRAY: ID -> REALM NUMBER
				$import[$char_id] = $realm;
			}
		}
	}
}


$last_entry = end($import);

echo '[';

foreach($import as $id => $realm) {
	if($id != $last_id) {
		echo '{"id":"' .$id. '", "realm_id":"' .$realm. '"},';
	}
	elseif($id == $last_id) {
		echo '{"id":"' .$id. '", "realm_id":"' .$realm. '"}';
	}
}

echo ']';

?>