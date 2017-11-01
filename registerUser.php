<?php
// login_ajax.php

  header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
  require 'database_cal.php';

  $newUser= (string) $_POST['user'];
  $usernameNew = $newUser;
  $pwd= (string) $_POST['pass'];
  $success = false;

  //CHECK THAT USERNAME DOESNT EXIST
  $stmt = $mysqli->prepare("SELECT username FROM users WHERE username = '$newUser'");

  if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
  }

  $stmt->execute();

  $stmt->bind_result($newUser);

  while($stmt->fetch()){
  }

  //IF THIS USERNAME EXISTS
  if($newUser != null){

    echo json_encode(array(
      "success" => false,
      "message" => "Incorrect Username or Password"
    ));
    header("Location: calendar.php?error=1");
    exit;


  }
  //USERNAME is valid. Add User.
  else{
    //hash password:
     $pwd_Hash = password_hash($pwd, PASSWORD_BCRYPT);

    /*Make a new user*/
    $stmt = $mysqli->prepare("insert into users (username, password_hash) values ( ?, ?)");
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      header("Location: calendar.php?error=2");
      exit;
    }

    $stmt->bind_param('ss', $usernameNew, $pwd_Hash);

    $stmt->execute();

    //DO A SQL QUERY TO GET THE USER ID WHERE THE USERNAME = usernamenew
    $stmt->close();


    session_start();
    $_SESSION['user'] = $_POST['user'];
    //Set user sesssion to the user id from the second query
    //$_SESSION['user_id'] = 0;
    $_SESSION['token'] = substr(md5(rand()), 0, 10);

    echo json_encode(array(
        "success" => true
      ));
     header("Location: calendar.php");
     exit;
    }
?>
