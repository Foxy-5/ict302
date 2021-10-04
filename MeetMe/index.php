<?php
//session_start();

    include("connection.php");
    include("function.php");

    //$user_data = check_login($con);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>MeetMe</title>
    </head>
    <body>
        <!--<a href = "logout.php">logout</a>-->
        <h1>Welcome to MeetMe</h1>
        <br>
        <button onclick="location.href='login.php'">Login</button><br><br>
        <button onclick="location.href='signup.php'">Sign Up</button><br>
        <!--hello, <?php echo $user_data['user_name']; ?>.-->
    </body>
</html>
