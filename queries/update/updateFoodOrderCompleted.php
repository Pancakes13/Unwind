<?php
    require("../../functions/sql_connect.php");
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if(count($request>0)){
        $id = $request['id'];
        $employeeId = 1; //Change to session value//
        
        $query = "UPDATE `food_order` 
        SET `food_order_status` = 'Completed', `employee_id` = $employeeId
        WHERE `food_order_id` = $id";
        $result = mysqli_query($mysqli, $query);
    }
?>