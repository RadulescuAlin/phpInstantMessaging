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
	};

	this.post = function(aUrl, params, aCallback) {
		var anHttpRequest = new XMLHttpRequest();
		anHttpRequest.onreadystatechange = function() { 
			if (anHttpRequest.readyState == 4 && anHttpRequest.status == 200)
				aCallback(anHttpRequest.responseText);
		}

		anHttpRequest.open("POST", aUrl, true);
		anHttpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		anHttpRequest.send( params );
	};
}
