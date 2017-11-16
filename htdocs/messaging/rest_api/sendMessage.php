<?php

	include_once('checkSessionStatus.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBContactListUtils.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php'); 
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBMessageUtils.php'); 

	// Get request params
	$userName = $_SESSION['username'];
	$recipientName = $_POST['recipient'];
	$messageBody = $_POST['message'];

	// Identify the sender and the receiver
	$user = getUser($userName);
	$recipient = getUser($recipientName);

	// Check if we're attempting to send a message to a user in our contact list.
	$isContact = isContactOf($user["id"], $recipient["id"]);
	if(!$isContact) {
		echo json_encode(0);
		die();
	}

	// Send the message
	$messageId = sendMessage($user["id"], $recipient["id"], $messageBody);
	echo json_encode($messageId);

?>
