<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require_once 'mysql_connect.php';

$query = "SELECT i.*, UNIX_TIMESTAMP(i.last_updated) AS last_updated_ts, u.*
FROM issues i 
LEFT JOIN open_users u ON (u.username = i.assignee)
WHERE (i.status = 'Closed' AND i.last_updated < NOW() - INTERVAL 2 WEEK) OR (i.status != 'Closed')";

$result = $mysqli->query($query);
	if (!$result){
		printf("Error: %s\n", $mysqli->error);
		die;
	}

$issues = array();

if (mysqli_num_rows($result))
{
	while ($row = $result->fetch_object())
	{
		$issues[$row->issue_id] = $row;
	}
}

$result = $mysqli->query("SELECT * FROM work_logs");

if (mysqli_num_rows($result))
{
	while ($row = $result->fetch_object())
	{
		// var_dump($row);
		$issues[$row->issue_id]->work_logs[$row->work_log_id] = $row;
	}
}

echo json_encode($issues);