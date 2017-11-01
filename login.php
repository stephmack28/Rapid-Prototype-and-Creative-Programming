<?php
    //CHECK TO SEE IF USER ALREADY EXISTS (hash pass, verify hashed password where username exists)
    require 'database_cal.php';
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

    //Taking password, hashing it
    $pwd_guess = (string) $_POST['pass'];
    $pwd_hash = password_hash($pwd_guess, PASSWORD_BCRYPT);

   //password-based user authentication
   //check if the username and password are in the database
   $stmt = $mysqli->prepare("SELECT COUNT(*), user_ID, password_hash FROM users WHERE username=?");

   // Bind the parameter
   $stmt->bind_param('s', $user);

   //Handle Reflected XSS
   $user = htmlentities((string) $_POST['user']);
   $stmt->execute();

   // Bind the results
   $stmt->bind_result($cnt, $user_id, $pwd_hash);
   $stmt->fetch();

   // Compare the submitted password to the actual password hash
   // VERIFY PASSWORD IS IN TABLE AT THAT USERNAME
   if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
		//Login succeeded!
		ini_set("session.cookie_httponly", 1);
		session_start();
		$_SESSION['user_id'] = $user_id;
		$_SESSION['token'] = substr(md5(rand()), 0, 10);

		echo json_encode(array(
			"success" => true
		));
		//REDIRECT TO LOGIN PAGE
        header("Location: calendar.php");
        die();
		exit;
	}
   else{
		// Login failed; redirect back to the login screen
		echo json_encode(array(
			"success" => false,
			"message" => "Incorrect Username or Password"
		));
		header("Location: calendar.php?error=3");
		exit;

   }
?>
