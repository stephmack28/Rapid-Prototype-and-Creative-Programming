//USE LOGIN
function loginAjax(event){
  var username = document.getElementById("user").value; // Get the username from the form
  var password = document.getElementById("pass").value; // Get the password from the form

	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);

  // Initialize our XMLHttpRequest instance
	var xmlHttp = new XMLHttpRequest();                                   

   // Starting a POST request
  xmlHttp.open("POST", "login.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	xmlHttp.addEventListener("load", function(event){
    //VALIDATE user login
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){                                 // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("You've been Logged In!");
		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false);                // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}

if(document.getElementById("login")){
	document.getElementById("login").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click
}
