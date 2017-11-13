<?php

	include 'DBConnect.php';



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
	 *     1: A request has been sent to the other user
	 *     0: An error occured, contact already exists, etc.
	 */
	function addToContactList( $user, $newContact, $confirmed = 0 ) {
		return 0;
	}



	/**
	 * Removes a user from someone's contact list.
	 * Returns:
	 *     1: The users are no longer in contact
	 *     0: An error occured, they were not contacts, etc.
	 */
	function removeFromContactList( $username ) {
		return 0
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
	 *     An array of user objects.
	 *     An empty array, if there are no contacts.
	 * 
	 * TODO (Medium priority): Paginate
	 */
	function fetchAllContacts( $user ) {
		return 0;
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

