<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

//$query = "UPDATE versions_meta SET status='".$mysqli->real_escape_string($_GET['status'])."', editor_user_id=".$_GET['user']." WHERE version_id=".$_GET['id'].";";
$query = "INSERT INTO versions_meta (version_id, status, editor_user_id) VALUES (".$_GET['id'].", '".$mysqli->real_escape_string($_GET['status'])."', ".$_GET['user'].")";

$result = $mysqli->query($query);



echo json_encode(array("success" => $result));

?>