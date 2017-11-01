<?php
require 'database_cal.php';
session_start();

$_SESSION['event_id'] = $_GET['val'];
$user_id = $_SESSION['user_id'];

//DELETE EVENT_ID only if session user matches user_id
$stmt = $mysqli->prepare("delete from events where (event_ID =?),(user_ID='$user_id')");
$stmt -> bind_param('i', $_SESSION['event_id']);

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$stmt->execute();

$stmt->close();

?>
