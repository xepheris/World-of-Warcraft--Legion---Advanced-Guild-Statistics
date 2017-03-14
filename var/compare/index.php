<?php

echo '<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-bottom: 15px; opacity: 1.0 !important;">

<span style="font-size: 20px; color: orange;">Compare</span>
<br />
<br />
<form action="" method="get">
<select required name="source">
<option selected disabled>select</option>';

$class_fetcher = mysqli_query($stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`");
while($class = mysqli_fetch_array($class_fetcher)) {
	echo '<optgroup label="' .$class['class']. '">';
	
	$guild_class_fetcher = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `class` = '" .$class['id']. "' ORDER BY `name` ASC");
	while($class_player = mysqli_fetch_array($guild_class_fetcher)) {
		echo '<option value="' .$class_player['id']. '">' .$class_player['name']. '</option>';
	}
	
	echo '</optgroup>';
}


echo '
</select>
<br />
<br />
<span style="color: orange; font-size: 20px;">with...</span>
<br />
<br />
<select required name="compare1">
<option selected disabled>select</option>';

$class_fetcher = mysqli_query($stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`");
while($class = mysqli_fetch_array($class_fetcher)) {
	echo '<optgroup label="' .$class['class']. '">';
	
	$guild_class_fetcher = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `class` = '" .$class['id']. "' ORDER BY `name` ASC");
	while($class_player = mysqli_fetch_array($guild_class_fetcher)) {
		echo '<option value="' .$class_player['id']. '">' .$class_player['name']. '</option>';
	}
	
	echo '</optgroup>';
}


echo '
</select>
<br />		
<select name="compare2">
<option selected disabled>select</option>';

$class_fetcher = mysqli_query($stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`");
while($class = mysqli_fetch_array($class_fetcher)) {
	echo '<optgroup label="' .$class['class']. '">';
	
	$guild_class_fetcher = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `class` = '" .$class['id']. "' ORDER BY `name` ASC");
	while($class_player = mysqli_fetch_array($guild_class_fetcher)) {
		echo '<option value="' .$class_player['id']. '">' .$class_player['name']. '</option>';
	}
	
	echo '</optgroup>';
}


echo '
</select>
<br />
<select name="compare3">
<option selected disabled>select</option>';

$class_fetcher = mysqli_query($stream, "SELECT `id`, `class`, `color` FROM `ovw_classes`");
while($class = mysqli_fetch_array($class_fetcher)) {
	echo '<optgroup label="' .$class['class']. '">';
	
	$guild_class_fetcher = mysqli_query($stream, "SELECT `id`, `name` FROM `" .$table_name. "` WHERE `class` = '" .$class['id']. "' ORDER BY `name` ASC");
	while($class_player = mysqli_fetch_array($guild_class_fetcher)) {
		echo '<option value="' .$class_player['id']. '">' .$class_player['name']. '</option>';
	}
	
	echo '</optgroup>';
}


echo '
</select>
<br />
<br />
<button type="submit">Compare</button>
</form>
</div>';

?>