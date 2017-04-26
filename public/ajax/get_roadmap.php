<?php

$user="jira-frontend";
$pass="AUfh9SN6FZDWTszQ";
$server="open-internal.cc2cezg5kv8d.us-east-1.rds.amazonaws.com";
$database="JIRA";

$db = new mysqli($server, $user, $pass, $database);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$sql='
                select 
                v.`version_name`,
                v.id as `id`,
                v.`description`,
                CASE 
                WHEN v.`released`="1" THEN "RELEASED"
                ELSE ""
                END as `release_status`,
                CASE WHEN
                v.`release_date` IS NULL THEN ""
                ELSE date(v.`release_date`)
                END as `release_date`,
                (select count(*) from `issues` i where i.`version_id`=v.`id`) as `tickets`,
                (select count(*) from `issues` i where i.`version_id`=v.`id` and type="Story") as `stories`,
                (select count(*) from `issues` i where i.`version_id`=v.`id` and type="Ad-Hoc") as `Ad-Hoc`,
                (select count(*) from `issues` i where i.`version_id`=v.`id` and type="Defect") as `Defect`,
                (select count(*) from `issues` i where i.`version_id`=v.`id` and i.`status` !="Closed") as `unresolved`,
                CONCAT((select count(*) from `issues` i where i.`version_id`=v.`id` and i.`status` !="Closed" and `subtask` =0),"/",(select count(*) from `issues` i where i.`version_id`=v.`id` and `subtask` =0)) as `ticket_to_resolve`,
                (select vm.status from `versions_meta` vm where `version_id`=v.`id` order by vm.TIMESTAMP desc limit 0,1) as `latest_update`,
                CONCAT("https://issues.mediamath.com/browse/OPEN/fixforversion/",v.id) as "overview_link",
                CONCAT("https://issues.mediamath.com/issues/?jql=fixVersion%20%3D%20%22",REPLACE(v.`version_name`," ","+"),"%22%20AND%20project%20%3D%20OPEN") as "all_issues_link",
                vp.*,
				vq.quarter
                from 
                        `versions` v
                        left join `versions_priority` vp on v.`id`=vp.`version_id`
                        left join `versions_quarter` vq on v.`id`=vq.`version_id`
                where
	                v.`released`="0"
	                OR (v.`released`="1" AND `release_date` > DATE_SUB(NOW(), INTERVAL 30 DAY))
                #order by UPPER(`version_name`) asc
                order by 
	                `released` desc,
	                vp.`exec_team_priority` desc,
	                vp.`priority` desc,
	                CASE WHEN
	                        release_date IS NULL THEN "3014-01-01"
	                        ELSE release_date
	                END
	                asc,
	                UPPER(`version_name`) asc
                ;
        ';

if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$issues = array();

if (mysqli_num_rows($result))
{
    while ($row = $result->fetch_object())
    {
        $json[] = $row;

    }
}

echo json_encode($json);



/*
echo "<table style=\"width: 100%;\">";
echo "<thead>";
echo "<tr>";
echo "<th>Status</th>";
echo "<th>Version Name</th>";
echo "<th>Description</th>";
echo "<th>Release Date</th>";
echo "<th>Tickets</th>";
echo "<th>Stories</th>";
echo "</tr>";
echo "</thead>";

echo "<tbody>";
while($row = $result->fetch_assoc()){
    echo "<tr>";
    if($row['exec_team_priority']=="1" && $row['Release_Status']!="RELEASED"){
        echo "<td class='bold small red center'>Priority</td>";
    }
    else{
        echo "<td class='bold small center'>".$row['Release_Status']."</td>";
    }
    echo "<td><b><a href='".$row['all_issues_link']."' target='_blank'>".$row['version_name']."</a></b></td>";
    echo "<td class='grey small'>".$row['description']."</td>";
    echo "<td>".$row['release_date']."</td>";
    echo "<td class='orange center "; if($row['Release_Status']=="RELEASED"){echo 'strike grey';} echo "'>".$row['unresolved']."/".$row['tickets']."</td>";
    echo "<td class='orange center "; if($row['Release_Status']=="RELEASED"){echo 'strike grey';} echo "'>".$row['Stories']."</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
*/



?>
