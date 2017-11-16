<?php

	include_once('checkSessionStatus.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBContactListUtils.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php'); 
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBMessageUtils.php'); 

	// Get request params
	$userName = $_SESSION['username'];
	$interlocutorId = $_GET['withUser'];

	// Identify the user
	$user = getUser($userName);

	// Check if we're attempting to get a conversation with a user in our contact list.
	$isContact = isContactOf($user["id"], $interlocutorId);
	if(!$isContact) {
		echo json_encode([]);
		die();
	}

	// Fetch the conversation
	$conversation = getLastMessages($user["id"], $interlocutorId, 50);
	echo json_encode($conversation);

?>