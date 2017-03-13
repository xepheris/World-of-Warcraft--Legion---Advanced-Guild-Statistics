<?php

// FETCH ALL VALID IDs
$all_ids = mysqli_query($stream, "SELECT `id` FROM `" .$table_name. "` ORDER BY `name` ASC");
$id_array = array();

while($id_fetcher = mysqli_fetch_array($all_ids)) {
	array_push($id_array, $id_fetcher['id']);
}

foreach($id_array as $id) {
	$sql.= "
	SELECT `ap` FROM `" .$_SESSION['table']. "_" .$id. "_past` WHERE `timestamp` >= '" .(time('now')-604800). "' ASC LIMIT 1 UNION ALL ";
}

$sql = substr($sql, '0', '-11');

echo $sql;

echo '
<div style="width: 21.25%; padding-top: 15px; height: 60%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
<span style="color: orange; text-align: center; font-size: 20px;">Artifact Power</span>
<table style="margin: 0 auto; margin-top: 15px;">
<tbody>';



echo '
</tbody>
</table>
</div>

<div style="width: 21.25%; padding-top: 15px; height: 60%; margin-left: 5%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
<span style="color: orange; text-align: center; font-size: 20px;">Mythic Dungeons</span>
<table style="margin: 0 auto; margin-top: 15px;">
<tbody>';



echo '
</tbody>
</table>
</div>

<div style="width: 21.25%; padding-top: 15px; height: 60%; margin-left: 5%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
<span style="color: orange; text-align: center; font-size: 20px;">World Quests</span>
<table style="margin: 0 auto; margin-top: 15px;">
<tbody>';



echo '
</tbody>
</table>
</div>

<div style="width: 21.25%; padding-top: 15px; height: 60%; margin-left: 5%; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5);">
<span style="color: orange; text-align: center; font-size: 20px;">Itemlevel</span>
<table style="margin: 0 auto; margin-top: 15px;">
<tbody>';



echo '
</tbody>
</table>
</div>';

?>