<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);
$bookingid = $_GET['bookingid'];
$query1 = "Select Booking_date, Booking_start, Booking_end, student.First_name, student.Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status from booking, student where booking.bookingID = '$bookingid' and booking.StudentID = student.StudentID LIMIT 1";
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
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li id="appointment" class="dropdown"><a href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel.php">Upload Excel</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming.php">View Upcoming Bookings</a></li>
                                <li><a href="allbooking.php">View All Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="analytics.php">Analytics</a></li>
                <li class="active"><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
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
                <th>Booking End</th>
                <th>Duration (Minutes)</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Booking_end'] . "</td>";
            echo "<td>" . $bookingdata['Duration'] . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Comment</th>
                <th>Status</th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Comment'] . "</td>";
            echo "<td>" . $bookingdata['Status'] . "</td>";
            ?>
            <?php
            echo "</tr>";
            ?>
        </table>
        <br>
        <label for="editbooking">Edit Booking</label>
        <a class="linktobutton" href="editbooking.php?bookingid=<?php echo $bookingdata['BookingID']; ?>">Edit</a>
        <br>
        <br>

    </div>
</body>

</html>