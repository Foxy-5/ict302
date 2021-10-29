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
        <h1>All booking</h1>
        <hr class="redbar">
        <p>All your bookings</p>
        <table class="upcomingbooking">
            <tr>
                <th>Booking date</th>
                <th>Booking start</th>
                <th>Student name</th>
                <th>View this booking</th>
            </tr>
            <?php
            $today = date("Y-m-d");
            $userid = $user_data['StaffID'];
            $query1 = "Select Booking_date, Booking_start, First_name, Last_name, BookingID from booking, student where (booking.ConvenerID = '$userid') and (booking.StudentID = student.StudentID) ORDER BY booking_start ASC";

            $result1 = mysqli_query($con, $query1);
            while ($row = mysqli_fetch_array($result1)) {
            ?>
                <tr>
                    <td><?php echo $row['Booking_date']; ?></td>
                    <td><?php echo $row['Booking_start']; ?></td>
                    <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                    <td><a class="linktobutton" href="viewbooking.php?bookingid=<?php echo $row['BookingID']; ?>">View</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
        </p>
    </div>
</body>

</html>