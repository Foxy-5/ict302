<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);
$bookingid = $_GET['bookingid'];
$query1 = "Select Booking_date, Booking_start, First_name, Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status from booking, student where booking.bookingID = '$bookingid' LIMIT 1";
$result1 = mysqli_query($con, $query1);
$bookingdata = mysqli_fetch_assoc($result1);

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
                        <li class="item"><a href="calendar.php">View Calendar</a></li>
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
        <h1>View booking</h1>
        <hr class="redbar">
        Current Booking<br /><br>
        <table class="userprofile">
            <tr>
                <th>Booking ID</th>
                <th>Previous meeting ID</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['BookingID'] . "</td>";
            echo "<td>" . $bookingdata['PreviousMeetingID'] . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Student Name</th>
                <th>Student ID</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['First_name'] . " " . $bookingdata['Last_name'] . "</td>";
            echo "<td>" . $bookingdata['StudentID'] . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Booking date</th>
                <th>Booking start</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Booking_date'] . "</td>";
            echo "<td>" . $bookingdata['Booking_start'] . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Duration</th>
                <th>Comment</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Duration'] . "</td>";
            echo "<td>" . $bookingdata['Comment'] . "</td>";
            echo "</tr>";
            ?>
             <tr>
                <th>Status</th>
                <th>Edit Booking</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Status'] . "</td>";
            ?>
            <td><a class="linktobutton" href="#">Edit</a></td>
            <?php
            echo "</tr>";
            ?>
        </table>

    </div>
</body>

</html>