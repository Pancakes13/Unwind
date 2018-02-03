<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require("../../functions/sql_connect.php");
$postdata = file_get_contents("php://input");

$checkInId = $_GET['check_in_id'];
$result = $mysqli->query("SELECT COUNT(*) AS 'count', `room_res`.`room_reserved_id`, `r`.`room_number`, `rt`.`name`, `rt`.`price`, `rt`.`description`, SUM(`rt`.`price`) AS `total`
FROM `room_reserved` `room_res`
INNER JOIN `room` `r`
ON `room_res`.`room_id` = `r`.`room_id`
INNER JOIN `room_type` `rt`
ON `r`.`room_type_id` = `rt`.`room_type_id`
INNER JOIN `reservation_request` `rr`
ON `room_res`.`reservation_request_id` = `rr`.`reservation_request_id`
INNER JOIN `reservation` `res`
ON `rr`.`reservation_request_id` = `res`.`reservation_request_id`
INNER JOIN `check_in` `c`
ON `res`.`reservation_id` = `c`.`reservation_id`
AND `c`.`check_in_id` = $checkInId
GROUP BY `rt`.`room_type_id`");

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {
        $outp .= ",";
    }
    $outp .= '{"RoomReservedId":"'  . $rs["room_reserved_id"] . '",';
    $outp .= '"RoomNumber":"'  . $rs["room_number"] . '",';
    $outp .= '"Count":"'  . $rs["count"] . '",';
    $outp .= '"Name":"'  . $rs["name"] . '",';
    $outp .= '"Price":"'  . $rs["price"] . '",';
    $outp .= '"Total":"'  . $rs["total"] . '",';
    $outp .= '"Description":"'   . $rs["description"]        . '"}';
}
$outp ='{"records":['.$outp.']}';
$mysqli->close();

echo($outp);
?>