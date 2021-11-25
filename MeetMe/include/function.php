<?php

//only allowed files can access this file
if(!defined('access')) {
   http_response_code(404);
   exit();
}

/**
 * This function checks if user is logged into the system
 * 
 * param:
 *      con: databse connection
 */
function check_login($con){
    //check if session staff id is assigned
    if(isset($_SESSION['StaffID'])){
        $id = $_SESSION['StaffID'];

        //check if staff id is valid
        $query = "select * from Staff where StaffID = '$id' limit 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    //redirect to login
    header("Location: login");
    exit();
}
?>
