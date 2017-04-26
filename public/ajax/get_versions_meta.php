<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

$query = "SELECT vm.*, ou.avatar_url from versions_meta vm 
			LEFT JOIN versions v ON (v.id = vm.version_id) 
			LEFT JOIN open_users ou ON (ou.open_user_id = vm.editor_user_id)
			WHERE v.archived = 0 ORDER BY vm.timestamp DESC;";


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