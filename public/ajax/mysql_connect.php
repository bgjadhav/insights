<?php
$server='open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com';
$user="jira_cli";
$pass="HjqjH56jjeBt5dVY";
$db="JIRA";

$mysqli = new mysqli($server, $user, $pass, $db);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>