<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

if (!(isset($_POST['status']) && isset($_POST['editor_user_id']) && isset($_POST['version_id']))){
	die(json_encode(array('success' => false, 'error' => 'status, editor_user_id and version_id are required.')));
}


$status = $mysqli->real_escape_string($_POST['status']);
$version_id = $mysqli->real_escape_string($_POST['version_id']);
$editor_user_id = $mysqli->real_escape_string($_POST['editor_user_id']);

$result = $mysqli->query("INSERT INTO versions_meta (version_id, status, editor_user_id) VALUES (".$version_id.", '".$status."', ".$editor_user_id.");");

if ($result){
	$json = array('success' => true);
} else {
	$json = array('success' => false);
}

echo json_encode($json);

?>