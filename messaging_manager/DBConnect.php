<?php

	/**
	 * Return a database object which can be used to run queries on the messaging SQLite database
	 * TODO: Would be cool to have a Singleton or a Factory, something more enterprise-ish.
	 */
	function getMessagingDb() {
		return new PDO('sqlite:C:/xampp/messaging_db/messaging_project.db');
	}

?>
