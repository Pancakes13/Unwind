<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require("../../functions/sql_connect.php");
$postdata = file_get_contents("php://input");

$roomId = $_GET['roomId'];
$result = $mysqli->query("SELECT `u`.`user_id`, CONCAT(`u`.`first_Name`,' ',`u`.`middle_initial`, ' ', `u`.`last_name`) AS `name`, `u`.`email`,
`rr`.`checkin_date`, `rr`.`checkout_date`, `rr`.`child_qty`, `rr`.`adult_qty`, `c`.`check_in_id`,
YEAR(`rr`.`checkin_date`) AS `checkin_year`, MONTHNAME(`rr`.`checkin_date`) AS `checkin_month`, DAY(`rr`.`checkin_date`) AS `checkin_day`,
YEAR(`rr`.`checkout_date`) AS `checkout_year`, MONTHNAME(`rr`.`checkout_date`) AS `checkout_month`, DAY(`rr`.`checkout_date`) AS `checkout_day`
FROM `check_in` `c`
INNER JOIN `reservation` `res`
ON `c`.`reservation_id` = `res`.`reservation_id`
INNER JOIN `reservation_request` `rr`
ON `res`.`reservation_request_id` = `rr`.`reservation_request_id`
INNER JOIN `user_account` `u`
ON `rr`.`user_id` = `u`.`user_id`
INNER JOIN `room_reserved` `roomres`
ON `rr`.`reservation_request_id` = `roomres`.`reservation_request_id`
INNER JOIN `room` `room`
ON `room`.`room_id` = `roomres`.`room_id`
AND `rr`.`checkin_date` <= CURDATE()
AND `rr`.`checkout_date` >= CURDATE()
AND `room`.`room_id` = $roomId");

$rs = $result->fetch_array(MYSQLI_ASSOC);
$outp = "";
$outp .= '{"UserId":"'  . $rs["user_id"] . '",';
$outp .= '"CheckInId":"'  . $rs["check_in_id"] . '",';
$outp .= '"Name":"'  . $rs["name"] . '",';
$outp .= '"Email":"'  . $rs["email"] . '",';
$outp .= '"CheckInDate":"'  . $rs["checkin_date"] . '",';
$outp .= '"CheckOutDate":"'  . $rs["checkout_date"] . '",';
$outp .= '"CheckInYear":"'  . $rs["checkin_year"] . '",';
$outp .= '"CheckInMonth":"'  . $rs["checkin_month"] . '",';
$outp .= '"CheckInDay":"'  . $rs["checkin_day"] . '",';
$outp .= '"CheckOutYear":"'  . $rs["checkout_year"] . '",';
$outp .= '"CheckOutMonth":"'  . $rs["checkout_month"] . '",';
$outp .= '"CheckOutDay":"'  . $rs["checkout_day"] . '",';
$outp .= '"ChildQty":"'  . $rs["child_qty"] . '",';
$outp .= '"AdultQty":"'   . $rs["adult_qty"]        . '"}';

$outp ='{"records":['.$outp.']}';
$mysqli->close();

echo($outp);
?>
