<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require("../../functions/sql_connect.php");

$result = $mysqli->query("SELECT COUNT(check_in_id) AS `number`, CONCAT(YEAR(check_in_start),'-',MONTH(check_in_start),'-',DAY(check_in_start)) AS `month`
FROM `check_in`
GROUP BY MONTH(check_in_start)");
$outp = "";
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {
        $outp .= ",";
    }
    $outp .= '{"Month":"'  . $rs['month'] . '",';
    $outp .= '"Number":"'  . $rs['number'] . '"}';
    }

$result = $mysqli->query("SELECT COUNT(check_in_id) AS `number`, CONCAT(YEAR(check_in_start),'-',MONTH(check_in_start),'-',DAY(check_in_start)) AS `month`
FROM `check_in`
GROUP BY YEAR(check_in_start)");
    while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {
        $outp .= ",";
    }
    $outp .= '{"Month":"'  . $rs['month'] . '",';
    $outp .= '"Number":"'  . $rs['number'] . '"}';
    }

// ask niel laturrr
// $result = $mysqli->query("SELECT 
// FROM `inquiry` `i`
// INNER JOIN `user_account` `u`
// ON `i`.`user_id` = `u`.`user_id`
// AND `i`.`employee_id` IS NULL
// ORDER BY `i`.`inquiry_timestamp` DESC");
//     while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
//     if ($outp != "") {
//         $outp .= ",";
//     }
//     $outp .= '{"Month":"'  . $rs['month'] . '",';
//     $outp .= '"Number":"'  . $rs['number'] . '"}';
//     }

$outp ='{"records":['.$outp.']}';
$mysqli->close();

echo($outp);
?>