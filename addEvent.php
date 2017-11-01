<?php

	require 'database_cal.php';
	header("Content-Type: application/json");

	ini_set("session.cookie_httponly", 1);
	session_start();

	//Taking in all event variables
	$name = (string) $_POST['eventName'];
	$eventDate = $_POST['eventDate'];
	$eventType = (string) $_POST['eventType'];
	$user_id = $_SESSION['user'];
	$success = false;


	if($eventType == "soc"){
		$eventType = 'SO';
	}
	else if($eventType == "bus"){
		$eventType = 'B';
	}
	else if($eventType == "spo"){
		$eventType = 'SP';
	}
	else if($eventType == "sch"){
		$eventType = 'SC';
	}
	else if($eventType == "fam"){
		$eventType = 'FA';
	}
	else if($eventType == "clu"){
		$eventType = 'CL';
	}
	else{
		$eventType = 'O';
	}


	//CHECK that no event is scheduled for this USER at this TIME on this DAY
	/*
	$stmt = $mysqli->prepare("SELECT user_ID, event_date, start_time FROM events WHERE user_ID= '$user_id' AND event_date='$eventDate' AND start_time='$eventTime'");

	if(!$stmt){
	  printf("Query Prep Failed: %s\n", $mysqli->error);
	  exit;
	}

	$stmt->execute();

	$stmt->bind_result($user_id, $eventDate, $eventTime);

	while($stmt->fetch()){
	}

	//CHECK for event conflicts
	if(($user_id != null) &&($eventDate != null) && ($eventTime != null)){

	  echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	  ));
	  header("Location: calendar.php?error=5");
	  exit;
	}
	*/

	//Adding event into the database

	try {
	       $bd = new PDO('mysql:host=localhost;dbname=calendar', 'mom', 'dad');
	       } catch(Exception $e) {
	       exit('Unable to connect to database.');
			}


	$tempID = 12;
	$stmt = $mysqli->prepare("insert into events (event_name, event_date, user_ID, event_type) values (:name, :eventDate, :tempID, :eventType)");
	if(!$stmt){

		printf("Query Prep Failed: %s\n", $mysqli->error);
		header("Location: calendar.php?error=2");
		exit;
	}

	//REPLACE THIS FOR USER_ID WHICH IS FOUND IN THE SESSION

	//$user = real_escape_string($_SESSION['user_id']);
	//$stmt->bind_param('ssi', $name, $eventDate, $tempID);
	$q =$bd->prepare($stmt);

	$stmt->execute(array(':name'=>$name, ':eventDate'=>$eventDate, ':user_ID'=>$tempID, ':eventType'=>$eventType));
	//makew sure query was successful

	$stmt->close();
	echo("whasup");
	$respdata = json_encode(array(
		"success" => true,
		"name" => $name,
		"date" => $eventDate,
		"type" => $eventType
	));
	echo $respdata;
	header("Location: calendar.php");
	exit;

?>
