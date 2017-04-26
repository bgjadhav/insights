<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if (empty($_POST['sprint_id'])){
	echo json_encode(array('error' => 'Must post sprint_id!'));die;
}

require_once 'mysql_connect.php';

$query = "SELECT u.*, i.*, 
UNIX_TIMESTAMP(i.last_updated) AS last_updated_ts, 
IFNULL(osubs.subtasks_open, 0) AS subtasks_open, 
IFNULL(csubs.subtasks_closed, 0) AS subtasks_closed, 
(IFNULL(osubs.subtasks_open, 0) + IFNULL(csubs.subtasks_closed, 0)) as total_subtasks
FROM open_users u 
LEFT JOIN issues i ON (u.username = i.assignee AND i.sprint_id = ".$_POST['sprint_id'].")
LEFT JOIN (SELECT parent_id, COUNT(*) AS subtasks_open FROM issues WHERE status != 'Closed' GROUP BY parent_id) AS osubs ON (osubs.parent_id = i.issue_id)
LEFT JOIN (SELECT parent_id, COUNT(*) AS subtasks_closed FROM issues WHERE status = 'Closed' GROUP BY parent_id) AS csubs ON (csubs.parent_id = i.issue_id)
ORDER BY u.vacation, u.sickness, u.maternity, u.last_name";


$result = $mysqli->query($query);

$issues = array();

if (mysqli_num_rows($result))
{
	while ($row = $result->fetch_object())
	{
		// $key = strtolower($row->last_name.'-'.$row->username);

		$issues[$row->open_user_id]['user'] = array(
			'0' => $row->last_name, // Sort descends down to here, go figure.
		    'assignee' => $row->username,
		    'display_name' => $row->display_name,
		    'first_name' => $row->first_name,
		    'last_name' => $row->last_name,
		    'vacation' => $row->vacation,
		    'sickness' => $row->sickness,
		    'maternity' => $row->maternity,
		    'avatar_url' => empty($row->avatar_url) ? './img/avatars/anonymous.png' : $row->avatar_url
		);

		$summary = preg_replace('/\[[^)]+\]/', '', $row->summary);
		if (strlen($summary) > 48)
		{
			$summary = substr($summary, 0,  48).'...';
		}

		//Empty array in case user has no issues.
		if (!isset($issues[$row->open_user_id]['issues']))
		{
			$issues[$row->open_user_id]['issues'] = array();
		}

		//New array item for each issue
		if (!empty($row->issue_id))
		{
        	$issues[$row->open_user_id]['issues'][] = array(
	        	'issue_id' => $row->issue_id,
	    		'board' => $row->board,
	    		'key' => $row->key,
	    		'summary' => $summary,
			    'type' => $row->type,
			    'label' => empty($row->label) ? 'Uncategorised' : $row->label,
			    'class' => empty($row->label) ? 'uncategorised' : strtolower($row->label),
			    'subtask' => $row->subtask,
			    'total_subtasks' => $row->total_subtasks,
			    'subtasks_open' => $row->subtasks_open,
			    'subtasks_closed' => $row->subtasks_closed,
			    'subtask_open_width' => $row->total_subtasks > 0 ? round(($row->subtasks_open / ($row->subtasks_open + $row->subtasks_closed) * 100)) : 0,
			    'subtask_closed_width' => $row->total_subtasks > 0 ? round(($row->subtasks_closed / ($row->subtasks_open + $row->subtasks_closed) * 100)): 0,
			    'sprint_id' => $row->sprint_id,
			    'sprint_name' => $row->sprint_name,
			    'epic' => $row->epic,
			    'status' => strtolower($row->status),
			    'creator' => strtoupper($row->creator),
			    'comment_count' => $row->comment_count,
			    'last_updated' => date('jS F Y g:i a', (strtotime($row->last_updated) - $_POST['timezone_offset'])),
			    'last_updated_ts' => $row->last_updated_ts
        	);
    	}
    }
}

// function cmp_by_last_name($a, $b)
// {
//     return $a["user"]["last_name"] - $b["user"]["last_name"];
// }


// usort($issues, "cmp_by_last_name");
// sort($issues);
// print_r($issues);die;

array_push($issues, array_shift($issues)); //Remove unassigned from the start and tack it on to the end.

echo json_encode($issues);

?>