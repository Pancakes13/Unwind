<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require("../../functions/sql_connect.php");
session_start();


$foodOrderId = $request['food_order_id'];
$result = $mysqli->query("SELECT `i`.`food_item_id`, `i`.`qty`, `f`.`name`, `f`.`description`, `f`.`price`
FROM `food_item` `i`
INNER JOIN `food` `f`
ON `i`.`food_id` = `f`.`food_id`
AND `i`.`food_order_id` = $foodOrderId");

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($outp != "") {
        $outp .= ",";
    }
    $outp .= '{"FoodItemId":"'  . $rs["food_item_id"] . '",';
    $outp .= '"Qty":"'  . $rs["qty"] . '",';
    $outp .= '"Name":"'  . $rs["name"] . '",';
    $outp .= '"Description":"'  . $rs["description"] . '",';
    $outp .= '"Price":"'   . $rs["price"]        . '"}';
}
$outp ='{"records":['.$outp.']}';
$mysqli->close();

echo($outp);
?>