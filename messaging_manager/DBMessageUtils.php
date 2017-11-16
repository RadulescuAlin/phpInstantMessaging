<?php

	include_once('DBConnect.php');



	/**
	 * This file contains utility functions to simplify work with the message DB table.
	 * Exports functions to send and pull messages.
	 * - boolean sendMessage(String from, String to, String message)
	 * - array getLastMessages(String user, String interlocutor, Int count)
	 * - array fetchNewMessages(String user, String interlocutor, Int afterid)
	 */



	/**
	 * Sends a message from a user to another.
	 * Returns:
	 *     MessageID (positive int) in case of success
	 *     0: fail
	 */
	function sendMessage( $from_id, $to_id, $message ) {
		$db = getMessagingDb();

		$lastInsert = $db->lastInsertId();

		$query = "INSERT INTO message (from_user, to_user, content) "
				. "VALUES (:from, :to, :content);";
		$statement = $db->prepare($query);
		$statement->bindParam(":from", $from_id);
		$statement->bindParam(":to", $to_id);
		$statement->bindParam(":content", substr($message , 0, 2048 ));
		$statement->execute();
		$id_of_this_message = $db->lastInsertId();

		if( $id_of_this_message > $lastInsert ) {
			return $id_of_this_message;
		}

		return 0;
	}



	/**
	 * Return the last COUNT messages between the two given users.
	 * Returns:
	 *     An array of message objects.
	 *     And empty array, if there's no message
	 *
	 * TODO: Add an extra argument: "maxId", to be able to fetch past messages
	 * in $count sized pages (e.g. fetch last 50 messages BEFORE message #1359871289)
	 * It's a nicer solution compared to using a very large $count in one single call.
	 */
	function getLastMessages( $user, $interlocutor, $count) {
		$db = getMessagingDb();
		$query = ""
				. "SELECT id, from_user, to_user, content "
				. "FROM message "
				. "WHERE ( from_user = :user AND   to_user = :interlocutor ) "
				. "   OR (   to_user = :user AND from_user = :interlocutor ) "
				. "ORDER BY id DESC "
				. "LIMIT :count;";

		$statement = $db->prepare($query);
		$statement->bindParam(":user", $user);
		$statement->bindParam(":interlocutor", $interlocutor);
		$statement->bindParam(":count", $count);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		//TODO print and inspect result
		return $result;
	}


	/**
	 * Fetch all new messages between the two users which were sent
	 * after the message with the given ID.
	 * This function is meant to be called every second by
	 * every active user to chck for new messages.
	 * Returns:
	 *     An array of message objects.
	 *     An empty array, if there's no new message.
	 *
	 * TODO: MASSIVE TODO: You just can't stress the DB like this.
	 * A caching mechanism or a flag is required to reduce DB calls.
	 * Alternatively, find a better method than getting a request every second,
	 * we wouldn't like to DDoS ourselves.
	 */
	function fetchNewMessages( $user, $interlocutor, $afterId ) {
		$db = getMessagingDb();
		$query = ""
				. "SELECT id, from_user, to_user, content "
				. "FROM message "
				. "WHERE id > :after_id "
				. "  AND ( "
				. "          ( from_user = :user AND   to_user = :interlocutor ) "
				. "       OR (   to_user = :user AND from_user = :interlocutor ) "
				. "  ) "
				. "ORDER BY id DESC "
				. "LIMIT 100"; // This limit is here just for safety

		$statement = $db->prepare($query);
		$statement->bindParam(":user", $user);
		$statement->bindParam(":interlocutor", $interlocutor);
		$statement->bindParam(":after_id", $afterId);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		//TODO print and inspect result
		return $result;
	}

?>
