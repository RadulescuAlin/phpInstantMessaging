<?php

	include '..\..\..\messaging_manager\DBUserUtils.php';



	/**
	 * If the post request is made using a valid username & password combination,
	 * a session will start and the user will have access to the complete rest API.
	 * TODO remove redirrects, make requests via javascript.
	 */

	session_start();

	$username = $_POST['username'];
	$password = $_POST['password'];
	$email    = $_POST['email'];

	// TODO: Perform user input validation.
	// username: Only letters, numbers, underscores.
	// password:
	// email: check if valid. duplicates will be blocked by the DB, no need to check.

	if( createUser($username, $password, $email) ) {
		$_SESSION['username'] = $username;
		$_SESSION['sessionStart'] = time();
		header("Location: /messaging/messages.php");
		die();
	}

	//header("Location: /messaging/login.php");
	//die();

?>