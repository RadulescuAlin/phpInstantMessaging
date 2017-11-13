<?php

	// Return a database object which can be used to run queries on the messaging SQLite database via:
	// $database->queryExec($sql)
	function get_messaging_db() {
		return new SQLiteDatabase('../../messaging_db/messaging_db.db');
	}

?>
