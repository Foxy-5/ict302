<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);
$userid = $user_data['StaffID'];

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
        <h1>Meeting Analytics</h1>
        <br>
        <a href="staffanalytics.php"class="linktobutton">Staff meeting Analytics</a>
        <a href="staffanalytics.php"class="linktobutton">another button</a>
        <br>
        <br>
        <ul>
            <li>
                Upcoming meetings <br />
                <?php
                $query1 = "Select Booking_date, Booking_start, First_name, Last_name from booking, student where (booking.OrganizerID = '$userid') and (booking.Status = 'confirmed') and (booking.StudentID = student.StudentID)";

                $result1 = mysqli_query($con, $query1);

                echo "<table border='2'>
                <tr>
                <th>Booking date</th>
                <th>Booking start</th>
                <th>Student name</th>
                </tr>";
                while ($row = mysqli_fetch_array($result1)) {
                    echo "<tr>";
                    echo "<td>" . $row['Booking_date'] . "</td>";
                    echo "<td>" . $row['Booking_start'] . "</td>";
                    echo "<td>" . $row['First_name'] . $row['Last_name'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                ?>
            </li>
        </ul>
    </div>
</body>

</html>