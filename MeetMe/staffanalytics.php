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
    <link rel="stylesheet" href="css/mystyle.css">
    <title>navbar</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">MeetMe</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="#">View Appointment</a></li>
                    </ul>
                </li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="glyphicon glyphicon-user"></span><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">User Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h1>Staff Meeting Analytics</h1>
        <br>
        <table border="2">
            <tr>
                <th>
                    <a href="?orderBy=firstname">First Name</a>
                </th>
                <th>
                    <a href="?orderBy=lastname">Last Name</a>
                </th>
                <th>
                    <a href="?orderBy=meeting_duration">Meeting Duration</a>
                </th>
            </tr>
            <?php
            $orderBy = array('firstname', 'lastname', 'meeting_duration', 'cancelled_meeting');
            $order = 'firstname';
            if (isset($_GET['orderBy']) && in_array($_GET['orderBy'], $orderBy)) {
                $order = $_GET['orderBy'];
            }
            $query1 = "Select staff.First_name, staff.Last_name, staff.Meeting_duration FROM staff";
            $result1 = mysqli_query($con, $query1);
            while ($row = mysqli_fetch_array($result1)) {
                echo "<tr>";
                echo "<td>" . $row['First_name'] . "</td>";
                echo "<td>" . $row['Last_name'] . "</td>";
                echo "<td>" . $row['Meeting_duration'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
</body>

</html>