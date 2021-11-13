<?php
define('access', TRUE);
session_start();

include("include/connection.php");
include("include/function.php");

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
    <style id="table_style" type="text/css">
        body {
            font-family: Arial;
            font-size: 10pt;
        }

        table {
            border: 1px solid #ccc;
            border-collapse: collapse;
        }

        table th {
            background-color: #F7F7F7;
            color: #333;
            font-weight: bold;
        }

        table th,
        table td {
            padding: 5px;
            border: 1px solid #ccc;
        }
    </style>
    <title>Data Dashboard | Meetme v2</title>
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
                <li class="active"><a href="analytics.php">Analytics</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h1>Meeting Analytics</h1>
        <hr class="redbar">
        <a href="staffanalytics.php"class="linktobutton">Staff Meeting Analytics</a>
        <a href="studentlisting.php"class="linktobutton">Student Analytics</a>
        <br>
        <br>
        <ul>
            <li>
                <?php
                $query1 = "Select Booking_date, Booking_start, First_name, Last_name from booking, student where (booking.OrganizerID = '$userid') and (booking.Status = 'confirmed') and (booking.StudentID = student.StudentID)";

                $result1 = mysqli_query($con, $query1);

                echo "<table border='2'>
                <tr>
                <th>Booking Date</th>
                <th>Booking Date/Start Time</th>
                <th>Student Name</th>
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