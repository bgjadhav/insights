<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

require_once 'mysql_connect.php';

$subtasks = array();

$query = "SELECT i.*, u.first_name, u.last_name FROM issues i
LEFT JOIN open_users u ON (u.username = i.assignee)
WHERE i.subtask = 1 AND i.parent_id = ".$_POST['issue_id'];

$result = $mysqli->query($query);
while ($row = $result->fetch_object())
{
	$subtasks[] = array(
		'initials' => strtoupper(substr($row->first_name, 0, 1)).strtoupper(substr($row->last_name, 0, 1)),
		'display_name' => $row->first_name." ".$row->last_name,
		'status' => $row->status,
		'summary' => (strlen($row->summary) > 40) ? substr($row->summary, 0,  40).'...' : $row->summary
		); 
}


die(json_encode($subtasks));

?>