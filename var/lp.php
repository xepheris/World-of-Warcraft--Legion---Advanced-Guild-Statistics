<?php

include('var/stream.php');
						
echo '
<div style="width: 60%; height: auto; float: left; text-align: center; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-left: 20%;">	
	<div style="width: 100%; height: auto; float: left;">
	<p style="color: orange; font-size: 18px;">
	<u>Advanced Guild Statistics</u> combines all necessary utilities a raid leader or loot council needs to make roster or loot decisions.<br /><br />Minimalistically pre-processed data helps guilds to min-max raid performance.
	</p>
	
	<div style="width: 50%; height: auto; float: left; padding-top: 15px; min-height: 165px;">
		<span style="color: orange; font-size: 18px;">Login</span>
		<br />
		<span style="color: orange; font-size: 12px;">Guests require no password</span>
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
								
					echo '<option value="' .$guild['id']. '">' .$guild['guild_name']. ' (' .$guild['region']. '-' .$realm_name['name']. ')</option>';
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
	<div style="width: 50%; height: auto; float: left; padding-top: 15px; min-height: 165px;">
		<span style="color: orange; font-size: 18px">Register</span>
		<br />
		' .$duplicate_guild. '
		' .$password_fail. '
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
	<p style="color: orange; font-size: 18px;">For a testrun, just select any guild from the login menu and login without a password!</p>
	<p style="color: yellowgreen; font-size: 16px;">
	29/5/17 news:<br />
	- added link to <a href="https://wowanalyzer.com/#/" title="WoW Analyzer">WoW Analyzer</a><br />
	24/5/17 news:<br />
	concerning a recent ticket:<br />
	- if within a x-realm guild two characters have the exact same name across two servers, they won\'t be distinguishable for me
	</p>
	<p style="color: coral; font-size: 16px;"></p>
	
	<table style="width: 100%; border-top: 1px solid white;">
		<thead>
			<th>Features</th>
			<th>new version</th>
			<th>former version</th>
			<th>other pages/spreadsheets</th>
		</thead>
		<tbody>
			<tr>
				<td>direct Armory Import based on Guild rank</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>4 supported regions (US, EU, TW, KR)</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: orange;">partially</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>incredibly easy update function</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: orange;">partially</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>Admin and Guest mode</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>Bench system</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>Role system</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>individual character inspection (Equip, Dungeons, Raids and much more)</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: orange;">partially</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>in-depth tracking of Artifact Power, M+, etc</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: orange;">partially</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>Legendary tracking</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: orange;">partially</td>
			</tr>
			<tr>
				<td>includes Warcraftlog parses if existing</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>includes SimulationCraft string generation with many options</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>compare up to 4 characters</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>unifying internal metric "Effort Quota"</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>visually night compatible</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: coral;">no</td>
			</tr>
			<tr>
				<td>open source</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: yellowgreen;">yes</td>
				<td style="color: orange;">partially</td>
			</tr>
		</tbody>
	</table>
	</div>
</div>';

?>