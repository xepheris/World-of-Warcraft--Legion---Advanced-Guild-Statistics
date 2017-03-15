<?php

echo '<div style="width: 100%; height: auto; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-bottom: 15px; opacity: 1.0 !important;">';

$class_fetcher = mysqli_query($stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`");
while($class = mysqli_fetch_array($class_fetcher)) {
	echo '<div style="width: 33.33%; float: left; height: auto; background-color: ' .$class['color']. '; min-height: 200px;">
	<br /><span style="color: white; text-transform: uppercase; font-size: 25px; opacity: 0.5;">' .$class['class']. '</span><br />
	';
	
	$guild_class_fetcher = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `class` = '" .$class['id']. "' ORDER BY `name` ASC");
	while($class_player = mysqli_fetch_array($guild_class_fetcher)) {
		echo '<a style="text-transform: uppercase;" href="?inspect=' .$class_player['id']. '">[ ' .$class_player['name']. ' ]</a>   ';
	}
	
	echo '</div>';
}

echo '
</div>';

?>