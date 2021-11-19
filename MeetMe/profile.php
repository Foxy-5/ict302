<?php
define('access', true);
session_start();

include("include/connection.php");
include("include/function.php");

$user_data = check_login($con);
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
    <div class="content">
        <h3>Your Profile</h3>
        <hr class="redbar">
        <h4>Personal Details</h4>
        <br>
        <div class="containerprofile">
            <label for="StaffID" class="editprofiletext">Staff ID</label><br>
            <input class="myprofilebox" type="text" value=" <?php echo $user_data['StaffID']?>" readonly><br><br>

            <label for="UserName" class="editprofiletext">UserName</label><br>
            <input class="myprofilebox" type="text" value=" <?php echo $user_data['Username']?>" readonly><br><br>

            <label for="FirstName" class="editprofiletext">First Name</label><br>
            <input class="myprofilebox" type="text" value=" <?php echo $user_data['First_name']?>" readonly><br><br>

            <label for="LastName" class="editprofiletext">Last Name/Family Name</label><br>
            <input class="myprofilebox" type="text" value=" <?php echo $user_data['Last_name']?>" readonly><br><br>

            <label for="Email" class="editprofiletext">Registered Email</label><br>
            <input class="myprofilebox" type="text" value=" <?php echo $user_data['Email']?>" readonly><br><br>
        
            <a href="editprofile?" class="linktobutton">Edit Personal Details</a>
            <a href="changepw?" class ="linktobutton">Change Password</a>
        </div>
    </div>
</body>

</html>
