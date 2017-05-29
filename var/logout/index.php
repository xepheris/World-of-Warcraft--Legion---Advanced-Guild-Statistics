<?php

echo '<div style="width: 90%;  margin-left: 5%; height: auto; padding-bottom: 15px; padding-top: 15px; float: left; text-align: center;">
	<div style="width: 100%; height: auto; padding-top: 15px; padding-bottom: 15px; float: left; background-color: #84724E; box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -moz-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); -webkit-box-shadow: 0px 10px 35px 10px rgba(0,0,0,0.5); margin-top: 15px; margin-bottom: 15px; opacity: 1.0 !important;">
	<span style="color: yellowgreen; font-size: 20px; text-align: center; margin-top: 15px;">logging out...</span>';

	unset($_SESSION);
	session_unset();
	
	echo '</div></div>';
	echo '<meta http-equiv="refresh" content="0;url=http://ags.gerritalex.de/" />';

?>