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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="css/mystyle.css">
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <title>Upcoming Bookings | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li class="dropdown active"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="calendar.php">View Calendar</a></li>
                    </ul>
                </li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h1>Upcoming booking</h1>
        <hr class="redbar">
        Upcoming Booking<br /><br>
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Booking date</th>
                    <th>Booking start</th>
                    <th>Student name</th>
                    <th>View this booking</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = date("Y-m-d");
                $query1 = "Select Booking_date, Booking_start, First_name, Last_name, BookingID from booking, student where (booking.ConvenerID = '$userid') and (booking.Status = 'confirmed') and (booking.StudentID = student.StudentID) and (booking.Booking_date >= '$today') ORDER BY booking_start ASC";

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
            </tbody>

        </table>
        <p>
            <input type="button" value="Print Table" onclick="myApp.printTable()" />
        </p>
    </div>
</body>
<script>
    var myApp = new function() {
        this.printTable = function() {
            var tab = document.getElementById('myTable');
            var win = window.open('', '', 'height=700,width=700');
            win.document.write(tab.outerHTML);
            win.document.close();
            win.print();
        }
    }
</script>
</html>