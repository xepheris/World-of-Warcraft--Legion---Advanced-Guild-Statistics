<?php

if ( !isset( $_POST[ 'content' ] ) ) {
	echo '<span style="font-size: 20px; color: orange;">Contact</span>
	<p style="font-size: 16px; color: orange;">if you wish a reply, please manually write a email to <u>ags_feedback@gmail.com</u>.</p>
	<form method="post" action="">
	<input type="text" value="' . $_SESSION[ 'guild' ] . '" name="guild" hidden required />
	<input type="text" value="' . $_SESSION[ 'region' ] . '" name="region" hidden required />
	<input type="text" value="' . $_SESSION[ 'realm' ] . '" name="realm" hidden required />
	<select required name="type">
		<option disabled selected>select topic</option>
		<option value="1">Bug report</option>
		<option value="2">Suggestions/ideas</option>
		<option value="3">Other</option>
	</select>
	<br />
	<p style="color: orange;" font-size: 16px;">please describe your issue or suggestion as clearly as possible<br />information about your guild is tracked automatically<br />spamming will not speed up ticket processing and may get you banned in excessive cases<br />this website is a one-man-project so please bear with me in case something takes a bit longer than expected</p>
	<textarea required name="content" maxlength="1000" style="width: 30%; height: 200px;"></textarea>
	<br />
	<br />
	<button type="submit">Send</button>
	</form>';

} elseif ( isset( $_POST[ 'content' ] ) ) {

	require( 'var/PHPMailer5216/class.phpmailer.php' );
	require( 'var/PHPMailer5216/class.pop3.php' );

	$mail = new PHPMailer;

	$mail->CharSet = 'UTF-8';

	$mail->setFrom( 'no-reply@artifactpower.info', 'Advanced Guild Statistics' );

	if ( $_POST[ 'type' ] == '1' ) {
		$subject = 'Bug Report';
	} elseif ( $_POST[ 'type' ] == '2' ) {
		$subject = 'Suggestion';
	}
	elseif ( $_POST[ 'type' ] == '3' ) {
		$subject = 'Other Topic';
	}

	$mail->Subject = '' . $subject . '';

	$mail->addAddress( 'xhs207ga@gmail.com' );

	$mail->msgHTML( '<!doctype html>
	<html>
	<head>
	<style type="text/css">
	#t {
		display: table;
		border-collapse:collapse; 
		}

	#tr {
		display: table-row;
		}

	#td {
		display: table-cell;
		padding: 3px; 
		}
		</style>
		<meta charset="UTF-8">
		</head>

	<body style="background-color: #DCD0C0; color: #373737;">
	<div id="t">
		<div id="tr">
			<div id="td">
				<span style="font-size: 17px;">Hey dev,</span>
			</div>
		</div>
		<div id="tr">
			<div id="td">
				<br />' . $_POST[ 'content' ] . '
			</div>
		</div>	
		<div id="tr">
			<div id="td">
				<span style="font-size: 17px;"><br />- ' . $_SESSION[ 'guild' ] . ' (' . $_SESSION[ 'region' ] . '-' . $_SESSION[ 'realm' ] . ')</span>
			</div>
		</div>
	</div>
	</body>
	</html>
	' );

	if ( !$mail->send() ) {
		echo '<p style="color: coral;">Mail failed to send:<br />' . $mail->ErrorInfo . '<br />Please copy this information and send it manually to xhs207ga@gmail.com, thanks!</p>';
	} else {
		echo '<p style="color: yellowgreen;; text-align: center;">Your mail has been sent, thanks! Will be looking into it shortly.<br />
		<a href="http://artifactpower.info/dev">Back to your roster</a></p>';
	}
}


?>