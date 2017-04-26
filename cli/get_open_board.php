#!/usr/bin/php
<?php
chdir(dirname(__FILE__));
require_once './mysql_connect.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$result = $mysqli->query("SELECT UNIX_TIMESTAMP(MAX(last_imported)) FROM issues;");
list($last_import) = $result->fetch_row();
$last_import = '"'.date('Y-m-d H:i', $last_import).'"';
 // die ($last_import);
// $last_import = '2014-10-10 13:50';

$jira_username = 'OPEN API';
$jira_password = 'Jira123';

function get_worklog($issue_id)
{
	global $jira_username, $jira_password, $mysqli;
	$ch = curl_init("http://issues.mediamath.com/rest/api/2/issue/".$issue_id."/worklog");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERPWD, $jira_username.":". $jira_password);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	$worklogs = json_decode($output, true);
	
	if (is_array($worklogs['worklogs'])){
		foreach ($worklogs['worklogs'] as $worklog){
			$work_log_id = $worklog['id'];
			$username = $worklog['author']['name'];
			$comment = $mysqli->real_escape_string($worklog['comment']);
			$time_spent = $worklog['timeSpentSeconds'];
			$created = $worklog['created'];
			$updated = $worklog['updated'];

			$query = "REPLACE INTO work_logs (work_log_id, issue_id, username, comment, time_spent, created, updated) VALUES ({$work_log_id}, {$issue_id}, '{$username}', '{$comment}', {$time_spent}, '{$created}', '{$updated}');";
			$mysqli->query($query);
		}
	}
}




//curl -u "dbougourd:ilovejenna"  -H "Content-Type: application/json" "http://issues.mediamath.com/rest/api/2/search?jql=project%20%3D%20OPEN%20AND%20updated%20>%3D%20-30m&fields=summary,labels,issuetype,customfield_10760,customfield_10761,assignee,creator,status&maxResults=3" > output2.txt

//junk //$ch = curl_init("http://issues.mediamath.com/rest/api/2/search?jql=project%20%3D%20".$board."%20AND%20updated%20>%3D%20-20000m&fields=summary,comment,labels,issuetype,customfield_10760,customfield_10761,assignee,creator,status&maxResults=1000&startAt=0");

$board="OPEN";

$ch = curl_init("http://issues.mediamath.com/rest/api/2/search?jql=project%20%3D%20".$board."%20AND%20updated%20%3E%3D%20".urlencode($last_import)."%20&fields=summary,comment,parent,labels,issuetype,timetracking,fixVersions,customfield_10760,customfield_10241,customfield_10761,components,assignee,creator,updated,status");

//debug to find JIRA fields:
// $ch = curl_init("http://issues.mediamath.com/rest/api/2/search?jql=key=OPEN-1541&fields=*all&maxResults=10&startAt=0");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $jira_username . ":" . $jira_password);
//curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);       
curl_close($ch);


$output_array=json_decode($output,true);
 // print_r($output_array);die;
$num_issues=$output_array["total"];

if ($num_issues==0)
{
	echo "No issues this time on takeshi's castle\n";
	die();
}
else
{
	echo "Found ".$num_issues." updated issues\n"; 
}

$issues=$output_array["issues"];



foreach ($issues as $issue)
{

	$id=$issue["id"];
	$key=$issue["key"];
	$summary=$mysqli->real_escape_string(trim($issue["fields"]["summary"]));
	$status=$mysqli->real_escape_string(trim($issue["fields"]["status"]["name"]));
	$type=$mysqli->real_escape_string($issue["fields"]["issuetype"]["name"]);
	$label=$mysqli->real_escape_string($issue["fields"]["labels"][0]);
	$subtask=$mysqli->real_escape_string($issue["fields"]["issuetype"]["subtask"]);
	$assignee=$mysqli->real_escape_string($issue["fields"]["assignee"]["name"]);
	$creator=$mysqli->real_escape_string($issue["fields"]["creator"]["name"]);
	$owner=$mysqli->real_escape_string($issue["fields"]["customfield_10241"]["name"]);
	$epic=$mysqli->real_escape_string($issue["fields"]["customfield_10761"]);
	$comment_count = $issue['fields']['comment']['total'];
	$parent_id = $issue['fields']['parent']['id'];
	$last_updated = date('Y-m-d H:i:s', strtotime($issue['fields']['updated']));
	$version_id = $issue['fields']['fixVersions'][0]['id'];

	$original_estimate = $issue['fields']['timetracking']['originalEstimateSeconds'];
	$remaining_estimate = $issue['fields']['timetracking']['remainingEstimateSeconds'];
	$time_spent = $issue['fields']['timetracking']['timeSpentSeconds'];
	$component = $mysqli->real_escape_string($issue['fields']['components'][0]['name']);

	// echo $last_updated." is >= ".$last_import.PHP_EOL;
	
	$latest_sprint = count($issue["fields"]["customfield_10760"]) - 1;

	unset($sprint_id, $sprint_name, $state, $start_date, $end_date);

	$sprint_id_raw=$issue["fields"]["customfield_10760"][$latest_sprint];
	$sprint_id_array=explode(",", $sprint_id_raw);

	foreach ($sprint_id_array as $sprint_piece)
	{	
		if(substr($sprint_piece, 0,5)=='name='){
			$sprint_name=$mysqli->real_escape_string(substr($sprint_piece,5));
		}
		if(substr($sprint_piece, 0,3)=='id='){
			$sprint_id=substr($sprint_piece,3);
			$sprint_id=str_ireplace("]", "", $sprint_id);
		}
		if(substr($sprint_piece, 0,6)=='state='){
			$state=$mysqli->real_escape_string(substr($sprint_piece, 6));
		}
		if(substr($sprint_piece, 0,10)=='startDate='){
			$start_date=substr($sprint_piece, 10);
		}
		if(substr($sprint_piece, 0,8)=='endDate='){
			$end_date=substr($sprint_piece, 8);
		}
	}

	$sprints[] = array(
		'sprint_id' => $sprint_id, 
		'sprint_name' => $sprint_name,
		'state' => $state,
		'start_date' => date('Y-m-d H:i:s', strtotime($start_date)),
		'end_date' => date('Y-m-d H:i:s', strtotime($end_date))
		);


	// if (in_array($label, array('Analytics', 'Infrastructure', 'Integrations', 'Managed_App_Development', )))

	// write to DB

	$sql="REPLACE INTO 
			issues (
				`issue_id`,
				`board`,
				`key`,
				`summary`,
				`type`,
				`label`,
				`component`,
				`subtask`,
				`parent_id`,
				`sprint_id`,
				`sprint_name`,
				`assignee`,
				`creator`,
				`owner`,
				`epic`,
				`status`,
				`last_updated`,
				`comment_count`,
				`original_estimate`,
				`remaining_estimate`,
				`time_spent`,
				`version_id`
			) 
			VALUES (
				'$id',
				'$board',
				'$key',
				'$summary',
				'$type',
				'$label',
				'$component',
				'$subtask',
				'$parent_id',
				'$sprint_id',
				'$sprint_name',
				'$assignee',
				'$creator',
				'$owner',
				'$epic',
				'$status',
				'$last_updated',
				'$comment_count',
				'$original_estimate',
				'$remaining_estimate',
				'$time_spent',
				'$version_id'
			)";
	$res = $mysqli->query($sql);

	echo $key."\n";


	get_worklog($id);

}


	// UPDATE EPICS

	$sql='REPLACE INTO `epics` (`epic_id`,`key`,`summary` )
			SELECT 
				`issue_id` as `epic_id`,
				`key`,
				`summary`
			FROM
				`issues` a
			WHERE
				`type` = "Epic";';
	$res = $mysqli->query($sql);


foreach ($sprints as $sprint)
{
	$res = $mysqli->query("REPLACE INTO sprints (sprint_id, sprint_name, state, start_date, end_date) VALUES (".$sprint['sprint_id'].", '".$sprint['sprint_name']."', '".$sprint['state']."', '".$sprint['start_date']."', '".$sprint['end_date']."');");
}

?>