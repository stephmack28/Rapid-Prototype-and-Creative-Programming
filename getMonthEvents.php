<?php

ini_set("session.cookie_httponly", 1);

require 'database_cal.php';

session_start();
\
//making sure the current user is the one adding events
if ($_SESSION['username'] != $_POST['user']){

	exit;

}

if( ! isset($_POST['eventDate'])) ){

	exit;

}

$calDate = $_POST['eventDate'];
$dateArr = date_parse($calDate);
$month = $dateArr[month];
$year = $dateArr[year];
$day = $dateArr[day];
$time = $dateArr[hour];

$eventTitle = $_POST['eventName'];

$username = $_POST['user'];

$type = $_POST['type'];



$stmt = $mysqli->prepare("SELECT event_name, YEAR(event_date), MONTH(event_date), DAY(event_date), HOUR(event_date), event_ID, type from events where MONTH(event_date)=? AND YEAR(event_date)=? AND username = ?");

if(!$stmt){

 printf("Query Prep Failed: %s\n", $mysqli->error);

 exit;

}



$stmt->bind_param('ssss',$month,$year,$username);

$stmt->execute();

$stmt->bind_result($id,$year,$month,$day,$hour,$type);




$monthEvents = array();

$i = 0;

while($stmt->fetch()){

  $monthEvent = array("event_ID"=>$id, "year"=>$year, "month"=>($month-1),"day"=>$day, "hour"=>str_pad($hour, 2, "0", STR_PAD_LEFT), "type"=>$type);

  array_push($monthEvents,$monthEVent);

}

$stmt->close();

echo(json_encode($monthEvents));



?>
