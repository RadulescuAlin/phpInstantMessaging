<?php

	include_once('DBConnect.php');
	include_once('DBUserUtils.php');



	/**
	 * This file contains utility functions to simplify work with the contact_list DB table.
	 * Exports functions to add and remove people from contact lists.
	 * - boolean addToContactList(String username)
	 * - boolean removeFromContactList(String username)
	 * - boolean acceptContactRequest(String username)
	 * - array fetchAllContacts(String username)
	 * - array fetchNewContacts(String username)
	 */



	/**
	 * Adds a user to another user's contact list.
	 * Returns:
	 *     Request ID: A request has been sent to the other user
	 *     0: An error occured, contact already exists, etc.
	 */
	function addToContactList( $user, $newContact, $confirmed = 0 ) {
		$db = getMessagingDb();

		$query = "INSERT INTO contact_list (from_user, to_user, content) "
				. "VALUES (:from, :to, :content);";
		$statement = $db->prepare($query);
		$statement->bindParam(":from", $from);
		$statement->bindParam(":to", $to);
		$statement->bindParam(":content", substr($message , 0, 2048 ));
		$statement->execute();
		$this_last_insert = $db->lastInsertId();

		if( $this_last_insert > $lastInsert ) {
			return $this_last_insert;
		}

		return 0;
	}



	/**
	 * Removes a user from someone's contact list.
	 * Returns:
	 *     1: The users are no longer in contact
	 *     0: An error occured, they were not contacts, etc.
	 */
	function removeFromContactList( $username ) {
		return 0;
	}



	/**
	 * Set the "confirmed" flag of this request to "true", allowing the requester
	 * to send messages to this user.
	 * Returns:
	 *     1: The confirmed flag has been set to 1
	 *     0: Request did not exist, was already accepted, etc.
	 */
	function acceptContactRequest( $user, $requester ) {
		return 0;
	}



	/**
	 * Fetch all contacts this user has.
	 * Returns:
	 *     An array of <user_id, user_name> pairs.
	 *     An empty array, if there are no contacts.
	 * 
	 * TODO (Medium priority): Paginate
	 */
	function fetchAllContacts( $user_id ) {
		$db = getMessagingDb();
		$query = ""
				. "SELECT user.id, user.username "
				. "FROM contact_list AS contacts "
				. "JOIN user AS user "
				. "  ON contacts.contact_id = user.id "
				. "WHERE contacts.user_id = :user_id "
				. "  AND contacts.confirmed = 1 "
				. ";";
		$statement = $db->prepare($query);
		$statement->bindParam(":user_id", $user_id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}



	/**
	 * Fetch all new contacts this user has. Only fetch contact pairs
	 * with ID larger than the one provided
	 * Returns:
	 *     An array of user objects.
	 *     An empty array, if there are no new contacts.
	 * 
	 * TODO (Low priority): Paginate
	 */
	function fetchNewContacts( $user, $afterId ) {
		return 0;
	}



	/**
	 * Checks if 'contact' is a contact of 'user'
	 * Returns:
	 *     1 if the 'contact' is in user's contact list (and approved)
	 *     0 otherwise
	 */
	function isContactOf( $user_id, $contact_id ) {
		$db = getMessagingDb();
		$query = ""
				. "SELECT count(*) FROM contact_list "
				. "WHERE user_id = :user_id "
				. "  AND contact_id = :contact_id "
				. "  AND confirmed = 1 "
				. ";";
		$statement = $db->prepare($query);
		$statement->bindParam(":user_id", $user_id);
		$statement->bindParam(":contact_id", $contact_id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		return $result[0]["count(*)"];
	}
