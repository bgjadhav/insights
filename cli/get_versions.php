#!/usr/bin/php
<?php
chdir(dirname(__FILE__));
require_once './mysql_connect.php';
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$jira_username = 'OPEN API';
$jira_password = 'Jira123';

$ch = curl_init("http://issues.mediamath.com/rest/api/2/project/OPEN/versions");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $jira_username . ":" . $jira_password);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_VERBOSE, true);
$output = curl_exec($ch);       
curl_close($ch);

$versions = json_decode($output);

foreach ($versions as $row){
	$version_name = $mysqli->real_escape_string($row->name);
	$description = $mysqli->real_escape_string($row->description);
	$released = $row->released ? 1 : 0;
	$archived = $row->archived ? 1 : 0;
	$start_date = !empty($row->startDate) ? "'".$row->startDate."'" : 'NULL';
	$release_date = !empty($row->releaseDate) ? "'".$row->releaseDate."'" : 'NULL';

	$query = "REPLACE INTO versions (id, version_name, description, released, start_date, release_date, archived)
	VALUES ({$row->id}, '{$version_name}', '{$description}', {$released}, {$start_date}, {$release_date}, {$archived});";

	$result = $mysqli->query($query);
	if ($result){
		$i++;
	} else {
		printf("Error: %s\n", $mysqli->error);
	}
}