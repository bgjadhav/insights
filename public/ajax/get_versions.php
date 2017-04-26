<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

$query = "SELECT * FROM versions WHERE archived != 1 ORDER BY release_date ASC";


$result = $mysqli->query($query);

$issues = array();

if (mysqli_num_rows($result))
{
	while ($row = $result->fetch_object())
	{
		$json[] = $row;
		
    }
}

echo json_encode($json);

?>