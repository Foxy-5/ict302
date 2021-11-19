<?php
//session_start();  
    define('access',TRUE);
    include("include/connection.php");
    include("include/function.php");
    header("Location: login.php");
    //$user_data = check_login($con);
?>

<!-- <!DOCTYPE html>
<html>
    <head>
        <title>MeetMe</title>
    </head>
    <body>
        <h1>Welcome to MeetMe</h1>
        <br>
        <button onclick="location.href='login.php'">Login</button><br><br>
        <button onclick="location.href='signup.php'">Sign Up</button><br>
    </body>
</html> -->
