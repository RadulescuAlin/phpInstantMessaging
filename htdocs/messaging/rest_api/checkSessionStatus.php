<?php

	include(dirname(__DIR__) . '\..\..\messaging_manager\DBUserUtils.php');

	/**
	 * This file is supposed to be included by any other file which expects
	 * the user to have a session (almost every other php file in the  rest API)
	 * If the user has no session when making an API request, he is automatically
	 * declined
	 */



	session_start();

	// Check if there is no active session, return unauthorized
	if( (!$_SESSION) || (!$_SESSION['username']) || (!$_SESSION['sessionStart']) ) {
		header("HTTP/1.1 401 Unauthorized");
		exit;
	}

	// Check if the currently active session was rendered obsolete by a password change
	if( checkExpiredSession($_SESSION['username'], $_SESSION['sessionStart']) ) {
		session_destroy();
		header("HTTP/1.1 401 Unauthorized");
		exit;
	}


	// If I need to redirect:
	// header("Location: /messaging/login.php");
	// die();

?>