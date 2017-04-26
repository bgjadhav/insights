<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require_once 'mysql_connect.php';

$output = array('vacation' => 0, 'sickness' => 0, 'maternity' => 0);


if (empty($_POST['username']))
{
	die(json_encode($output));
}

$output['vacation'] = $_POST['vacation'];
$output['sickness'] = $_POST['sickness'];
$output['maternity'] = $_POST['maternity'];

$query = "UPDATE open_users SET vacation = ".$output['vacation'].", sickness = ".$output['sickness'].", maternity = ".$output['maternity']." WHERE username = '".$_POST['username']."';";


$result = $mysqli->query($query);


$output['affected_rows'] = mysqli_affected_rows($mysqli);


die(json_encode($output));
?>