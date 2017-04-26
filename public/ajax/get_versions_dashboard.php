<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


require_once 'mysql_connect.php';

$query = "SELECT
v.`version_name`,
v.id as `version_id`,
v.`description`,
CASE
WHEN v.`released`='1' THEN 'RELEASED'
ELSE ''
END as `Release_Status`,
CASE WHEN
v.`release_date` IS NULL THEN ''
ELSE date(v.`release_date`)
END as `release_date`,
(select count(*) from `issues` i where i.`version_id`=v.`id`) as `tickets`,
(select count(*) from `issues` i where i.`version_id`=v.`id` and type='Story') as `Stories`,
(select count(*) from `issues` i where i.`version_id`=v.`id` and type='Ad-Hoc') as `Ad-Hoc`,
(select count(*) from `issues` i where i.`version_id`=v.`id` and type='Defect') as `Defect`,
(select count(*) from `issues` i where i.`version_id`=v.`id` and i.`status` !='Closed') as `unresolved`,
CONCAT((select count(*) from `issues` i where i.`version_id`=v.`id` and i.`status` !='Closed' and `subtask` =0),'/',(select count(*) from `issues` i where i.`version_id`=v.`id` and `subtask` =0)) as `ticket_to_resolve`,
(select vm.status from `versions_meta` vm where `version_id`=v.`id` order by vm.TIMESTAMP desc limit 0,1) as `latest_update`,
CONCAT('https://issues.mediamath.com/browse/OPEN/fixforversion/',v.id) as 'overview_link',
CONCAT('https://issues.mediamath.com/issues/?jql=fixVersion%20%3D%20%22',REPLACE(v.`version_name`,' ','+'),'%22%20AND%20project%20%3D%20OPEN') as 'all_issues_link'
from `versions` v
where
v.`released`='0'
OR (v.`released`='1' AND `release_date` > DATE_SUB(NOW(), INTERVAL 30 DAY))
#order by UPPER(`version_name`) asc
order by
`released` desc,
CASE WHEN
release_date IS NULL THEN '3014-01-01'
ELSE release_date
END
asc,
UPPER(`version_name`) asc
;";


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