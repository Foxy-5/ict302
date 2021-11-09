<?php
session_start();

include("connection.php");
include("function.php");

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
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="calendar.php">View Calendar</a></li>
                    </ul>
                </li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="content">
        <h3>Your Profile</h3>
        <hr class="redbar">
        <h4>Personal Details</h4>
        <br>
        <!-- comment here -->



        <div class="containerprofile">
            <a href="editprofile.php?" class="linktobutton">Edit personal details</a>
        </div>


        <!--
        <div class="containerprofile">
            <a href="editprofile.php?" class="linktobutton">Edit personal details</a>
        </div>
            <table class="userprofile">
                <tr>
                    <th>Staff ID</th>
                    <th>Username</th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td>" . $user_data['StaffID'] . "</td>";
                echo "<td>" . $user_data['Username'] . "</td>";
                echo "</tr>";
                ?>

                <tr>
                    <th>First name</th>
                    <th>Last name</th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td>" . $user_data['First_name'] . "</td>";
                echo "<td>" . $user_data['Last_name'] . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <th>Registered email</th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td>" . $user_data['Email'] . "</td>";
                echo "</tr>";
                ?>
            </table>
        <br>
        <br>
        <a href="editprofile.php?" class="linktobutton">Edit personal details</a>
        -->
        
        <!-- <a href="editprofile.php?StaffID=<?php echo $user_data['StaffID']; ?>"class="linktobutton">Edit personal details</a> -->
    </div>
</body>

</html>