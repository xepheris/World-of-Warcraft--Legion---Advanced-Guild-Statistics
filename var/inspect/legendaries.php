<?php

echo '<div style="width: 47.5%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 5%;">
<span style="color: orange; font-size: 20px;">Known Legendaries across all specs <a href="?edit_legendaries=' .$_GET['inspect']. '" style="font-size: 13px;">(manually add/remove legendaries)</a></span>
<table style="margin: 0 auto; text-align: center; margin-top: 15px; width: 90%;">
<thead>
</thead>
<tbody>
<tr>';

$legendaries = mysqli_query($stream, "SELECT * FROM `" .$_SESSION['table']. "_" .$_GET['inspect']. "_legendaries`");

while($legendary = mysqli_fetch_array($legendaries)) {
	echo '<td><a href="http://wowhead.com/?item=' .$legendary['item_id']. '">' .$legendary['item_id']. '</a></td>';
}

echo '</tr>
</tbody>
</table>
</div>';
	
?>