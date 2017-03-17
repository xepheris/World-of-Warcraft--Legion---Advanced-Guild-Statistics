<?php


echo '<span style="font-size: 20px; color: orange;">Contact</span>
<p style="font-size: 16px; color: orange;">if you wish a reply, please manually write a email to <u>ags_feedback@gmail.com</u>.</p>
<form method="post" action="">
<input type="text" value="' .$_SESSION['guild']. '" name="guild" hidden required />
<input type="text" value="' .$_SESSION['region']. '" name="region" hidden required />
<input type="text" value="' .$_SESSION['realm']. '" name="realm" hidden required />
<select required name="type">
<option disabled selected>select topic</option>
<option value="1">Bug report</option>
<option value="2">Suggestions/ideas</option>
<option value="3">Other</option>
</select>
<br />
<br />
<textarea required placeholder="please describe your issue as clearly as possible" maxlength="1000" style="width: 30%; height: 200px;"></textarea>
<br />
<br />
<button type="submit">Send</button>
</form>';



?>