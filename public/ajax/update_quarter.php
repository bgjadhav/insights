<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

$query = "REPLACE into versions_quarter (version_id, quarter) values(".$_GET['id'].", ".$_GET['quarter'].")";

$result = $mysqli->query($query);

echo json_encode(array("success" => $result));

?>