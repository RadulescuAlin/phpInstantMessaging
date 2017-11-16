<?php

	include_once('checkSessionStatus.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBContactListUtils.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php'); 
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBMessageUtils.php'); 

	// Get request params
	$userName = $_SESSION['username'];
	$interlocutorId = $_GET['withUser'];
	$afterMessageId = $_GET['afterId'];

	// Identify the user
	$user = getUser($userName);

	// TODO check if all requested params are OK

	// Check if we're attempting to fetch messages from a conv. with a user in our contact list.
	$isContact = isContactOf($user["id"], $interlocutorId);
	if(!$isContact) {
		echo json_encode([]);
		die();
	}

	// Fetch the messages
	$newMessages = fetchNewMessages($user["id"], $interlocutorId, $afterMessageId);
	echo json_encode($newMessages);

?>