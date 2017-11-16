<?php

	include_once('rest_api/checkSessionStatus.php');

?>

<html>
	<head>
		<script src="js/httpUtils.js"></script>
		<script src="js/contactList.js"></script>
		<script src="js/messagesPanel.js"></script>
	</head>


	<button onclick="window.location.href='rest_api/endSession.php'">Logout</button>

	<p> Welcome, <?php echo $_SESSION['username']; ?>! </p>

	<table border = 1 rules = none style="margin-left:auto; margin-right:auto;" width=60% height=80%>
		<tr height=90% style="outline: thin solid gray;">
			<td id="contact_list" width = 25% style="outline: thin solid gray;"></td>
			<td>
				<div id="messagesPanel" class=scrollable style="width: 100%; height: 100%; margin: 0; padding: 0; overflow: auto;">
				</div>
			</td>
		</tr>
		<tr height=10%>
			<td colspan=2>
				<textarea id="txtAreaMessage" rows="3" style="width: 100%; height: 100%; resize: none;" ></textarea>
			</td>
		</tr>
		<tr>
			<td colspan=2 align="center">
				<button id="btnSendMessage" onclick="sendMessage()">Send</button>
			</td>
		</tr>
	</table>

</html>
