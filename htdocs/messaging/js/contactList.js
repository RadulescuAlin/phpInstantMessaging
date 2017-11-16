// https://stackoverflow.com/questions/247483/http-get-request-in-javascript
var HttpClient = function() {
	this.get = function(aUrl, aCallback) {
		var anHttpRequest = new XMLHttpRequest();
		anHttpRequest.onreadystatechange = function() { 
			if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
				aCallback(anHttpRequest.responseText);
		}

		anHttpRequest.open( "GET", aUrl, true );
		anHttpRequest.send( null );
	}
}

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
