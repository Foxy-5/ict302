<?php
define('access', true);
session_start();
//include methods from this pages
include("include/connection.php");
include("include/function.php");
//get user session data
$user_data = check_login($con);
//get user id
$userid = $user_data['StaffID'];
//set page 
$_SESSION['bkPageFrom'] = 'home';
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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <title>Home | Meetme v2</title>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
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
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel">Upload Excel</a></li>
                        <li><a href="chooseavailtime">Upload Time</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming">View Upcoming Bookings</a></li>
                                <li><a href="allbooking">View All bookings</a></li>
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
        <h1>Welcome back <?php echo $user_data['First_name']; ?> <?php echo $user_data['Last_name']; ?>!</h1>
        <br>
        <h3>Today's Booking</h3>
        <hr class="redbar">
        <br>
        <!-- Display table for today's booking -->
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Manage Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //get today's date
                $today = date("Y-m-d");
                //query for today's booking
                $query1 = "Select Booking_date, Booking_start, First_name, Last_name, Auth_key, booking.StudentID from booking, student where (booking.ConvenerID = '$userid') and (booking.Status = 'confirmed') and (booking.StudentID = student.StudentID) and (booking.Booking_date = '$today') ORDER BY booking_start ASC";

                $result1 = mysqli_query($con, $query1);
                //print out rows of booking
                while ($row = mysqli_fetch_array($result1)) {
                    $starttime = date("h:i:s a", strtotime($row['Booking_start']));
                ?>
                    <tr>
                        <td><?php echo $row['Booking_date']; ?></td>
                        <td><?php echo $starttime; ?></td>
                        <td><?php echo $row['StudentID']; ?></td>
                        <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                        <td><a class="linktobutton" href="viewbooking?bookingid=<?php echo $row['Auth_key']; ?>"><span class="glyphicon glyphicon-eye-open"></span> View Booking</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <!-- Print table button -->
        <button class="linktobutton" onclick="PrintTable();"><span class="glyphicon glyphicon-print"></span> Print</button>
    </div>
</body>
<!-- Script for print table -->
<script type="text/javascript">
    function PrintTable() {
        var printWindow = window.open('', '', 'height=700,width=700');
        printWindow.document.write('<html><head><title>Table Contents</title>');

        //Print the Table CSS.
        var table_style = document.getElementById("table_style").innerHTML;
        printWindow.document.write('<style type = "text/css">');
        printWindow.document.write(table_style);
        printWindow.document.write('</style>');
        printWindow.document.write('</head>');

        //Print the DIV contents i.e. the HTML Table.
        printWindow.document.write('<body>');
        var divContents = document.getElementById("myTable").outerHTML;
        printWindow.document.write(divContents);
        printWindow.document.write('</body>');

        printWindow.document.write('</html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

</html>