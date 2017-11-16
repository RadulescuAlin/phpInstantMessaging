var recipientId = -1;
var lastMessageId = -1;
var fetchMessages = 0;
var veryCrudeLockSTOP = 0;

function switchChatWindow(user_id){
	// Stop polling messages
	fetchMessages = 0;
	recipientId = user_id;
	clearMessages();
	getConversation(user_id);
	// Start polling for new messages automatically after the old conv messages have been displayed.
	fetchMessages = 1;
}

function clearMessages() {
	document.getElementById("messagesPanel").innerHTML = "";
}

function getConversation(withUser) {
	var client = new HttpClient();
	client.get('/messaging/rest_api/getConversation.php?withUser=' + withUser, function(response) {
		var messages = JSON.parse(response);
		messages.forEach(function(entry){
			addMessage(
					"", //entry["from_user"], // Later maybe we display the names of the people
					entry["content"],
					entry["from_user"] == recipientId ? "left" : "right"
			);
		});

		// Set last message. Can't do this if there was no message sent ever.
		if(messages.length > 0) {
			// Set last message ID
			lastMessageId = messages[messages.length - 1]["id"];
		}
	});
}

function addMessage(from, message, alignment) {
	var messagesPanel = document.getElementById("messagesPanel");
	var newParagraph = document.createElement("p");
	newParagraph.align = alignment;
	newParagraph.innerHTML = /*from + ": " + */ message; // Later maybe we display from whom. Now just align
	messagesPanel.appendChild(newParagraph);
	console.log("Added message: " + message);
}

function sendMessage() {
	var textArea = document.getElementById("txtAreaMessage");
	var message = textArea.value;
	if(recipientId < 1 || message.length < 1) {
		return;
	}

	var client = new HttpClient();
	client.post(
			'/messaging/rest_api/sendMessage.php',
			'recipient=' + recipientId + '&message=' + message,
			function(response) {
				var messageId = JSON.parse(response);
				if(messageId != 0) {
					document.getElementById("txtAreaMessage").value = "";
				}
			}
			// Trigger a message request now (don't wait 1s anymore)
	);
}

window.setInterval(
		function(){
			if(recipientId >= 0 && lastMessageId >= 0 && fetchMessages && !veryCrudeLockSTOP) {
				veryCrudeLockSTOP = 1;
				var client = new HttpClient();
					client.get(
							"/messaging/rest_api/pollMessages.php"
									+ "?withUser=" + recipientId
									+ "&afterId=" + lastMessageId
							, function(response) {
								var messages = JSON.parse(response);
								messages.forEach(function(entry){
									addMessage(
											"", //entry["from_user"], // Later maybe we display the names of the people
											entry["content"],
											entry["from_user"] == recipientId ? "left" : "right"
									);
								});

								// Set last message if we got one
								if(messages.length > 0) {
									// Set last message ID
									lastMessageId = messages[messages.length - 1]["id"];
								}
								veryCrudeLockSTOP = 0;
							}
					);
				}
		},
		1000
);
