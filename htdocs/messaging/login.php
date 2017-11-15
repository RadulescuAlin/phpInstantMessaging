<?php

	include '..\..\messaging_manager\DBUserUtils.php';

	session_start();

	// If we already have a valid session, go to messages. No need to login.
	if( $_SESSION
			&& $_SESSION['username']
			&& $_SESSION['sessionStart']
			&& !checkExpiredSession($_SESSION['username'], $_SESSION['sessionStart'])
	) {
		header("Location: /messaging/messages.php");
		die();
	}
?>


<html>

	<table style="margin-left:auto; margin-right:auto;">
		<td>
			<form action='rest_api/startSession.php' method='POST'>
				Username: <input type='text' name='username'><br>
				Password: <input type='password' name='password'><br>
				<input type='submit' value='Log in'><br>
			</form>
		</td>
		<td>
			<form action='rest_api/createAccount.php' method='POST'>
				Username: <input type='text'     name='username'><br>
				Password: <input type='password' name='password'><br>
				Email:    <input type='email'    name='email'><br>
				<input type='submit' value='Sign up'><br>
			</form>
		</td>
	</table>

</html>
