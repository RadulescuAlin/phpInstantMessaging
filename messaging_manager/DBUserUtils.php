<?php

	include 'DBConnect.php';



	/**
	 * This file contains utility functions to simplify work with the user DB table.
	 * Exports functions to login, sign up, change password, various status queries.
	 * - boolean isUsernameTaken(String username)
	 * - boolean checkCredentials(String username, String password)
	 * - boolean checkExpiredSession(String username, String sessionStart)
	 * - boolean createUser(String username, String password)
	 * - boolean changePassword(String username, String password, String newPassword)
	 */



	/**
	 * Checks if a username is already taken
	 * Returns:
	 *     1 if the username is taken
	 *     0 otherwise.
	 */
	function isUsernameTaken( $username ) {
		$db = getMessagingDb();
		$query = "SELECT count(*) FROM user WHERE username = :username;";
		$statement = $db->prepare($query);
		$statement->bindParam(":username", $username);
		$statement->execute();
		$result = $statement->fetchAll();
		return $result[0]['count(*)'];
	}



	/**
	 * Computes a cryptographically secure pseudorandom string.
	 * Such a random string cannot be guessed, wheras a default random string which
	 * relies on simple mechanics (using timestamp since epoch as a seed) can be guessed.
	 * Returns a 128 characters long string.
	 *
	 * TODO: Actually compute a secure string, we're using rand now.
	 *     Consult: https://github.com/ircmaxell/RandomLib
	 *         $factory = new RandomLib\Factory;
	 *         $generator = $factory->getMediumStrengthGenerator();
	 *         $randomSalt = $generator->generateString(32);
	 */
	function newPasswordSalt() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);

		$randomString = '';
		for ($i = 0; $i < 128; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}



	/**
	 * Computes the SHA-3-512 of the <password + salt> concatenated string.
	 * Returns a 128 characters long string.
	 */
	function generatePasswordHash( $password, $salt ) {
		return hash('sha3-512', $password . $salt);
	}



	/**
	 * Check if a user with this username and password exists.
	 * Returns:
	 *     1 if the combination is valid
	 *     0 for bad username, password, or combination.
	 */
	function checkCredentials ( $username, $password ) {
		$db = getMessagingDb();

		$query = "SELECT id, salt, hashed_pwd FROM user where username = :username;";
		$statement = $db->prepare($query);
		$statement->bindParam(":username", $username);
		$statement->execute();
		$result = $statement->fetchAll();

		if(sizeof($result) > 0) {
			$stored_salt = $result[0]['salt'];
			$stored_hash = $result[0]['hashed_pwd'];
			$computed_hash = generatePasswordHash($password, $stored_salt);
			if($computed_hash == $stored_hash) {
				return 1;
			}
		}

		return 0;
	}



	/**
	 * Check if the current user has an active session which has begun
	 * prior to a recent change of password (and thus should be terminated).
	 * Returns:
	 *     1: This session should be terminated. A password change has occured since last login.
	 *     0: This session can remain active. No password change has occured since last login.
	 */
	function checkExpiredSession($username, $sessionStart) {
		$db = getMessagingDb();

		$query = "SELECT last_password_change FROM user where username = :username;";
		$statement = $db->prepare($query);
		$statement->bindParam(":username", $username);
		$statement->execute();
		$result = $statement->fetchAll();

		if(sizeof($result) > 0) {
			$lastPasswordChange = $result[0]['last_password_change'];

			// If the last password change happened before this session start, stay logged in.

			// TODO: Compare timestamps.
			echo "lastPasswordChange: " . $lastPasswordChange . "<br>";
			echo "sessionStart: " . $sessionStart . "<br>";
			if($lastPasswordChange <= $sessionStart) {
				return 0;
			}
		}

		return 1;
	}



	/**
	 * Create a new user with the specified username and password.
	 * Returns:
	 *     1: The user has been succesfully created.
	 *     0: An error occured. (username taken or server error)
	 */
	function createUser( $username, $password ) {
		if( isUsernameTaken($username) ) {
			return 0;
		}

		$salt = newPasswordSalt();
		$hash = generatePasswordHash($password, $salt);

		$db = getMessagingDb();
		$query = "INSERT INTO user (username, salt, hashed_pwd) "
				. "VALUES (:username, :salt, :hashed_pwd);";
		$statement = $db->prepare($query);
		$statement->bindParam(":username", $username);
		$statement->bindParam(":salt", $salt);
		$statement->bindParam(":hashed_pwd", $hash);
		$statement->execute();

		return 1;
	}



	/**
	 * Change the password for the specified user
	 * Returns:
	 *     1: A change of password has happened.
	 *     0: An error occured (invalid old_password or server error)
	 */
	function changePassword( $username, $oldPassword, $newPassword ) {
		if(! checkCredentials($username, $oldPassword) ){
			return 0;
		}

		$salt = newPasswordSalt();
		$hash = generatePasswordHash($newPassword, $salt);

		$db = getMessagingDb();
		$query = "UPDATE user SET "
				. "salt = :salt, hashed_pwd = :hashed_pwd, last_password_change = CURRENT_TIMESTAMP "
				. "WHERE username = :username;";
		$statement = $db->prepare($query);
		$statement->bindParam(":username", $username);
		$statement->bindParam(":salt", $salt);
		$statement->bindParam(":hashed_pwd", $hash);
		$statement->execute();

		return 1;
	}

?>
