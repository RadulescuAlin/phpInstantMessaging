<?php

	include_once('checkSessionStatus.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBContactListUtils.php');
	include_once(dirname(__DIR__) . '/../../messaging_manager/DBUserUtils.php'); 



	/**
	 * This endpoint is a supplier for this user's conversations.
	 * Although a contact is not a conversation, we don't need to
	 * support "conferences", so we can consider every contact
	 * a conversation.
	 */

	$name = $_SESSION['username'];
	$user = getUser($name);
	$contacts = fetchAllContacts($user['id']);
	echo json_encode($contacts);
?>