<?php

	/**
	 * Return a database object which can be used to run queries on the messaging SQLite database
	 * TODO: Would be cool to have a Singleton or a Factory, something more enterprise-ish.
	 * The IF wrapper here exists because multiple file include this file, and if one of them
	 * includes another, we end up including (declaring) this funciton multiple times.
	 */

	if (!function_exists('getMessagingDb')) {
		function getMessagingDb() {
			return new PDO('sqlite:' . dirname(__DIR__) . '../messaging_db/messaging_project.db');
		}
	}
?>
