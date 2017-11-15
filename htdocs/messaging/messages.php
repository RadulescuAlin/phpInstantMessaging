<?php

	include 'rest_api/checkSessionStatus.php';

?>

<html>

	<button onclick="window.location.href='rest_api/endSession.php'">Logout</button>

	<p> Welcome, <?php echo $_SESSION['username']; ?>! </p>

	<table border=1 style="margin-left:auto; margin-right:auto;" width=60% height=80%>
		<td id="contact_list" width = 25%></td>
		<td id="messages"></td>
	</table>

	<form action='rest_api/startSession.php' method='POST'>
		<input
				type='text'
				name='message'
				style="margin-left:auto; margin-right:auto;"
				width=60%
				word-break: break-word;
		><br> <!-- Don't try this at home -->

		<input type='submit' value='Send'><br>
	</form>

</html>
