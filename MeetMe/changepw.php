<?php
define('access', true);
session_start();
//include methods from connection and function
include("include/connection.php");
include("include/function.php");
// get user session data
$user_data = check_login($con);
//get user id from session
$userid = $user_data['StaffID'];
//when the form is posted, this function will run
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //get current
    $current_pw = $_POST['current_pw'];
    //set new password 1
    $new_pw1 = $_POST['new_pw1'];
    //set new password 2
    $new_pw2 = $_POST['new_pw2'];
    //check for empty fields
    if(!empty($current_pw) && !empty($new_pw1) && !empty($new_pw2) && ($new_pw1 == $new_pw2))
    {
            //check password hash match
            if(password_verify($current_pw,$user_data['Password'])){
                $hash = password_hash($new_pw1,PASSWORD_DEFAULT);
                $query = "UPDATE staff SET Password='$hash' WHERE StaffID = '$userid'";
                if(mysqli_query($con,$query)){
                    echo '<script>
                    alert("Password was succesfully updated. Please login again.");
                    window.location.href="login";
                    </script>';
                    mysqli_commit($con);
                    die;
                }
                else{
                    echo '<script>alert("An error has occured.")</script>';
                }
            }
    }
    else{
        echo '<script>alert("Current password or new password dont match")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Profile | Meetme v2</title>
    <link rel="stylesheet" href="css/mystyle.css">
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel">Upload Excel</a></li>
                        <li><a href="chooseavailtime">Upload Time</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming">View Upcoming Bookings</a></li>
                                <li><a href="allbooking">View All Bookings</a></li>
                                <li><a href="openbooking">View Open Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="analytics" class="dropdown"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics">Staff Analytics</a></li>
                        <li><a href="studentlisting">Student Analytics</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="profile"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <!-- Content of web page -->
    <div class="content">
        <h3>Change your password</h3>
        <hr class="redbar">
        <form class="editprofileform" method="post">
            <div class="containerprofile">
                <label for="current_pw" class="editprofiletext">Current password</label><br>
                <input class="editprofilebox" type="password" name="current_pw"><br><br>

                <label for="new_pw1" class="editprofiletext">new password</label><br>
                <input class="editprofilebox" type="password" name="new_pw1"><br><br>

                <label for="new_pw2" class="editprofiletext">re-enter new password</label><br>
                <input class="editprofilebox" type="password" name="new_pw2"><br><br>

                <input class="linktobutton" type="button" value="Cancel" onclick="location.href = 'profile'">
                <input class="linktobutton" id="button" type="submit" value="Update password">
            </div>
        </form>

    </div>
</body>

</html>