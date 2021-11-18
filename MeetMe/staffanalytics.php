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
    <title>Staff Analytics | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                <li id="appointment" class="dropdown"><a href="#"><span class="glyphicon glyphicon-calendar"></span> Appointment <span class="caret"></span></a>
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
                <li id="analytics" class="dropdown active"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
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
        <h1>Staff Meeting Analytics</h1>
        <hr class="redbar">
        <br>
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Standard Hour Meeting (%)</th>
                    <th>After Hour Meeting (%)</th>
                    <th>Meeting Hours (Mins)</th>
                    <th>No. of Cancelled meeting</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $status = "cancelled";
                $query1 = "Select
                staff.StaffID,
                staff.First_name,
                staff.Last_name,
                sum(CASE 
                WHEN booking.Status = 'ended' 
                THEN booking.duration 
                ELSE 0 END) as duration,
                sum(CASE
                WHEN booking.Status = 'ended'
                THEN 1
                ELSE 0
                END) as total,
                COUNT(
                    CASE 
                    WHEN(booking.Status = 'ended') 
                    THEN 
                        CASE 
                        WHEN (DAYOFWEEK(booking.Booking_date) = '1' OR DAYOFWEEK(booking.Booking_date) = '7')
                            THEN 1 
                            ELSE 0
                        END
                    END
                ) AS afterhour,
                COALESCE(sum(booking.Status = 'cancelled'), 0) as Status
                from
                    staff
                LEFT JOIN booking ON
                    staff.StaffID = booking.convenerID
                GROUP BY
                    staff.StaffID";
                // $query1 = "Select staff.StaffID, staff.First_name, staff.Last_name, sum(booking.duration) as duration, COALESCE(sum(booking.Status='$status'),0) as Status from staff LEFT JOIN booking ON staff.StaffID=booking.convenerID GROUP BY staff.StaffID";
                $result1 = mysqli_query($con, $query1);
                while ($row = mysqli_fetch_array($result1)) {
                    $total = $row['total'];
                    if($total != 0){
                        $afterpercentage = ($row['afterhour']/$total) * 100;
                    }
                    else{
                        $afterpercentage = 0;
                    }
                    if($total != 0){
                        $duringpercentage = (($total - $row['afterhour'])/$total) * 100;
                    }
                    else{
                        $duringpercentage = 0;
                    }
                ?>
                    <tr>
                        <td><?php echo $row['StaffID']; ?></td>
                        <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                        <td><?php echo $duringpercentage . "%"; ?></td>
                        <td><?php echo $afterpercentage. "%"; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
</body>

</html>