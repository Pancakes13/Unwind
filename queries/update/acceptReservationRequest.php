<?php
    require("../../functions/sql_connect.php");
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if(count($request>0)){
        $id = $request['id'];
        $employeeId = 1; //Change to session value//
        
        $query = "UPDATE `unwind_db` 
        SET `reservation_request_status` = 'Accepted', `employee_id` = $employeeId
        WHERE `reservation_request_id` = $id";
        $result = mysqli_query($mysqli, $query);
    }
?>
