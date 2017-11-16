
function createContactButtons() {
	var client = new HttpClient();
	client.get('/messaging/rest_api/getContacts.php', function(response) {
		var contacts = JSON.parse(response);
		contacts.forEach(function(entry){
			addContactButton(entry["id"], entry["username"]);
		});
	});
}


function addContactButton(contactId, contactName) {
	var contact_list = document.getElementById("contact_list");
	var contactButton = document.createElement("BUTTON");
	contactButton.id = 'contactButton' + contactId;
	contactButton.innerHTML = contactName;
	contactButton.onclick = function() { switchChatWindow(contactId); };
	contact_list.appendChild(contactButton);
	contact_list.appendChild(document.createElement("br"));
}



createContactButtons();
