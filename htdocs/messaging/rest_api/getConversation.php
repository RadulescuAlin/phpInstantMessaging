<?php

	include_once('checkSessionStatus.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBContactListUtils.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php'); 
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBMessageUtils.php'); 

	// Get request params
	$userName = $_SESSION['username'];
	$interlocutorName = $_GET['withUser'];

	// Identify the two users
	$user = getUser($userName);
	$interlocutor = getUser($interlocutorName);

	// Check if we're attempting to get a conversation with a user in our contact list.
	$isContact = isContactOf($user["id"], $interlocutor["id"]);
	if(!$isContact) {
		echo json_encode([]);
		die();
	}

	// Fetch the conversation
	$conversation = getLastMessages($user["id"], $interlocutor["id"], 50);
	echo json_encode($conversation);

?>