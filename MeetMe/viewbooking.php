<?php
define('access', true);
session_start();

include("include/connection.php");
include("include/function.php");

$user_data = check_login($con);
$bookingId = $_GET['bookingid'];
// $query1 = "Select Booking_date, Booking_start, Booking_end, student.First_name, student.Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status from booking, student where booking.bookingID = '$bookingid' and booking.StudentID = student.StudentID LIMIT 1";
$query1 = "Select Booking_date, Booking_start, Booking_end, case WHEN booking.StudentID is NULL THEN NULL ELSE student.First_name end as First_name, case WHEN booking.StudentID is NULL THEN NULL ELSE student.Last_name end as Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status, booking.Initial from booking LEFT JOIN student ON booking.StudentID = student.StudentID where booking.Auth_key = '$bookingId'limit 1";
$result1 = mysqli_query($con, $query1);
if(!mysqli_num_rows($result1)>0){
    echo "<script>alert('Invalid meeting')</script>";
    exit();
}
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
    <title>Booking Details | MeetMe v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown active"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
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
                <li><a href="profile"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h1>View Booking</h1>
        <hr class="redbar">
        Current Booking<br /><br>
        <table class="userprofile">
            <tr>
                <th>Booking ID</th>
                <th>Previous Meeting ID</th>
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
                <th>Booking Date</th>
                <th>Booking Start</th>
            </tr>
            <?php
            $starttime = date("h:i:s a", strtotime($bookingdata['Booking_start']));
            echo "<tr>";
            echo "<td>" . $bookingdata["Booking_date"] . "</td>";
            echo "<td>" . $starttime . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Booking End</th>
                <th>Duration (Minutes)</th>
            </tr>
            <?php
            $endtime = date("h:i:s a", strtotime($bookingdata['Booking_end']));
            echo "<tr>";
            echo "<td>" . $endtime . "</td>";
            echo "<td>" . $bookingdata['Duration'] . "</td>";
            echo "</tr>";
            ?>
            <tr>
                <th>Meeting Type</th>
                <th>Status</th>
            </tr>
            <?php
            if($bookingdata['Initial'] == 0)
            {
                $initial = "follow up meeting";
            }
            else if ($bookingdata['Initial'] == 1)
            {
                $initial = "first meeting";
            }
            echo "<tr>";
            echo "<td>" . $initial . "</td>";
            echo "<td>" . $bookingdata['Status'] ."</td>";
            ?>
            <?php
            echo "</tr>";
            ?>
            <tr>
                <th>Comment</th>
                <th></th>
            </tr>
            <?php
            echo "<tr>";
            echo "<td>" . $bookingdata['Comment'] . "</td>";
            echo "<td></td>";
            ?>
            <?php
            echo "</tr>";
            ?>
        </table>
        <br>
        <!--<label for="editbooking">Edit Booking</label>-->
        <div class="containerprofile">
            <a class="linktobutton" href="allbooking">Back</a>
            <a class="linktobutton" href="editbooking?bookingid=<?php echo $bookingId; ?>">Edit</a>
        </div>
        <br>
        <br>

    </div>
</body>

</html>