<?php
    require("../../functions/sql_connect.php");
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if(count($request>0)){
        $id = $request['id'];
        $name = $request['name'];
        $desc = $request['desc'];
        $price = $request['price'];
        
        $query = "UPDATE `food` 
        SET `name` = '$name', `description` = '$desc', `price` = $price
        WHERE `food_id` = $id";
        $result = mysqli_query($mysqli, $query);
        echo $result;
    }
?>