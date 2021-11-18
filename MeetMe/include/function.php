<?php
if(!defined('access')) {
   http_response_code(404);
   exit();
}

function check_login($con)
{
    if(isset($_SESSION['StaffID']))
    {
        $id = $_SESSION['StaffID'];
        $query = "select * from Staff where StaffID = '$id' limit 1";

        $result = mysqli_query($con, $query);
        if($result && mysqli_num_rows($result) > 0)
        {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    //redirect to login
    header("Location: login");
    exit();
}
?>
