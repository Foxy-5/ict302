<?php
define('access', true);
session_start();

include("include/connection.php");
include("include/function.php");

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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="css/mystyle.css">
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
    <title>All Bookings | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown active"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="uploadExcel.php">Upload Excel</a></li>
                        <li id="sub-dropdown" class="dropdown"><a href="#">View Calendar <span class="glyphicon glyphicon-chevron-right"></span></a>
                            <ul id="sub-dropdown-menu" class="dropdown-menu">
                                <li><a href="upcoming.php">View Upcoming Bookings</a></li>
                                <li><a href="allbooking.php">View All bookings</a></li>
                                <li><a href="openbooking.php">View Open Bookings</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li id="analytics" class="dropdown"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics.php">Staff Analytics</a></li>
                        <li><a href="studentlisting.php">Student Analytics</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h5>Hi <?php echo $user_data['First_name']; ?> <?php echo $user_data['Last_name']; ?>!</h5>
        <h1>All Booking(s)</h1>
        <hr class="redbar">
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Booking Date</th>
                    <th>Start Time</th>
                    <th>Student Name</th>
                    <th>Manage Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $today = date("Y-m-d");
                $staffId = $user_data['StaffID'];
                // $query1 = "Select Booking_date, Booking_start, First_name, Last_name, BookingID from booking, student where (booking.ConvenerID = '$staffid') and (booking.StudentID = student.StudentID) ORDER BY booking_start ASC";
                $query1 = "Select Booking_start, Auth_key, case WHEN booking.StudentID is NULL THEN NULL ELSE student.First_name end as First_name, case WHEN booking.StudentID is NULL THEN NULL ELSE student.Last_name end as Last_name, BookingID from booking LEFT JOIN student on booking.StudentID = student.StudentID where booking.ConvenerID = '$staffId' order by booking_start asc";
                $result1 = mysqli_query($con, $query1);
                while ($row = mysqli_fetch_array($result1)) {
                    $starttime = date("h:i:s a", strtotime($row['Booking_start']));
                    $bookingdate = date("Y-m-d",strtotime($row['Booking_start']));
                ?>
                    <tr>
                        <td><?php echo $bookingdate; ?></td>
                        <td><?php echo $starttime; ?></td>
                        <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                        <td><a class="linktobutton" href="viewbooking.php?bookingid=<?php echo $row['Auth_key']; ?>"><span class="glyphicon glyphicon-eye-open"></span> View Booking</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>

        </table>
        <button class="linktobutton" onclick="PrintTable();"><span class="glyphicon glyphicon-print"></span> Print</button>
        <!--<input class="linktobutton" type="button" onclick="PrintTable();" value="Print" />-->
    </div>

</body>
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