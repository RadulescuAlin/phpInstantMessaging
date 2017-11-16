

function switchChatWindow(user){
	// Stop polling messages
	clearMessages();
	getConversation(user);
	// Start polling asynchronously, automatically after the old conv messages have been displayed.
}

function getConversation(withUser) {
	var client = new HttpClient();
	client.get('/messaging/rest_api/getConversation.php?withUser=' + withUser, function(response) {
		var messages = JSON.parse(response);
		messages.forEach(function(entry){
			addMessage(
					entry["from"],
					entry["message"],
					"k" == "k" ? "left" : "right"
			);
		});

		// Start polling from this ID
	});
}


function addMessages(conversation) {
	//TODO
	;
}

function clearMessages() {
	document.getElementById("messagesPanel").innerHTML = "";
}

function addMessage(from, message, alignment) {
	var messagesPanel = document.getElementById("messagesPanel");
	var newParagraph = document.createElement("p");
	newParagraph.align = alignment;
	newParagraph.innerHTML = from + ": " + message;
	messagesPanel.appendChild(newParagraph);
}