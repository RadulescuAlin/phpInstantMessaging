<?php

	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php');



	/**
	 * If the post request is made using a valid username & password combination,
	 * a session will start and the user will have access to the complete rest API.
	 * TODO remove redirrects, make requests via javascript.
	 */

	session_start();

	$username = $_POST['username'];
	$password = $_POST['password'];

	if( $username && $password ) {
		if( checkCredentials($username, $password) ) {
			$_SESSION['username'] = $username;
			$_SESSION['sessionStart'] = time();
			header("Location: /messaging/messages.php");
			die();
		}
	}

	header("Location: /messaging/login.php");
	die();

?>
