<?php

include('var/stream.php');
						
echo '<div style="width: 20%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left;">
</div>
<div style="width: 60%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px;">
	<div style="width: 100%; height: auto; float: left;">
		<img src="img/wip.jpg" alt="404 title="404" style="border-bottom: 1px solid darkslategray;"/>
	</div>
	<div style="width: 50%; height: auto; float: left;">
		<span style="color: orange; font-size: 22px;">Login</span>
		<br />
		' .$login_wrong_pw. '
		<br />
		<form method="POST" action="">
		<select required name="guild" style="text-align: center;">
		<option selected disabled>select guild</option>';
			
		$alph = array('Non-Romanic characters', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		foreach($alph as $letter) {
			if($letter != 'Non-Romanic characters') {
				echo '<optgroup label="' .$letter. '">';
				$guilds = mysqli_query($stream, "SELECT `id`, `guild_name`, `region`, `realm` FROM `ovw_guilds` WHERE LEFT(`guild_name`, 1) = '" .$letter. "' ORDER BY `guild_name` ASC");
					
				while($guild = mysqli_fetch_array($guilds)) {	
					$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$guild['realm']. "'"));
								
					echo '<option value="' .$guild['id']. '">' .$guild['guild_name']. ' (' .$guild['region']. '-' .$realm_name['name']. ')</option>';
				}
			}
			elseif($letter == 'Non-Romanic characters') {
				echo '<optgroup label="' .$letter. '">';
		
				$alph = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
							
				foreach($alph as $nr_characters) {
					if(!isset($sql)) {
						$sql = 'WHERE LEFT(`guild_name`, 1) != "' .$nr_characters. '"';
					}
					elseif(isset($sql)) {
						$sql.= ' AND LEFT (`guild_name`, 1) != "' .$nr_characters. '"';
					}
				}
				
				$guilds = mysqli_query($stream, "SELECT `id`, `guild_name`, `region`, `realm` FROM `ovw_guilds` " .$sql. " ORDER BY `guild_name` ASC");
				while($guild = mysqli_fetch_array($guilds)) {
					$realm_name = mysqli_fetch_array(mysqli_query($stream, "SELECT `name` FROM `ovw_realms` WHERE `id` = '" .$guild['realm']. "'"));
								
					echo '<option value="' .$guild['id']. '">' .$guild['guild_name']. ' (' .$guild['region']. '-' .$guild['realm']. ')</option>';
				}
			}
						
			echo '</optgroup>';
						
	}
			
	echo '
		</select>
		<br />
		<input type="password" name="pw" placeholder="password" />
		<br />
		<button type="submit">Enter</button>
		</form>
	</div>
	<div style="width: 50%; height: auto; float: left;">
		<span style="color: orange; font-size: 22px">Register</span>
		<br />
		' .$duplicate_guild. '
		' .$new_guild_insert_fail. '
		' .$new_guild_insert_success. '
		' .$new_guild_name_fail. '
		' .$crash. '
		<br />
		<form method="POST" action="">
		<input type="text" name="gname" required placeholder="guild name case sensitive" value="' .$_POST['gname']. '"/>
		<br />
		<select required id="region" name="region" onchange="realms();" style="text-align: center;">>
			<option selected disabled>select region</option>
			<option value="EU">EU</option>
			<option value="US">US</option>
			<option value="TW">TW</option>
			<option value="KR">KR</option>
			<option disabled>CN API currently unavailable, sorry</option>
		</select>
		<div id="realms">
		</div>					
		<input type="password" name="pw" required placeholder="3+ characters password" minlength="3" value="' .$_POST['pw']. '"/>
		<br />
		<button type="submit">Register</button>
		</form>
	</div>
	<p style="text-align: center; color: orange;">best viewed on 1980x1080 or higher on a desktop browser</p>
</div>';

?>