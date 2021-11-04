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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="css/mystyle.css">
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <title>Staff Analytics | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="item"><a href="uploadExcel.php">Upload Excel</a></li>
                        <li class="item"><a href="calendar.php">View Calendar</a></li>
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
        <h1>Staff Meeting Analytics</h1>
        <br>
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Staff ID</th>
                    <th>Staff name</th>
                    <th>Meeting hours(minutes)</th>
                    <th>No. of Cancelled meeting</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $status = "cancelled";
                $query1 = "Select staff.StaffID, staff.First_name, staff.Last_name, sum(booking.duration) as duration, COALESCE(sum(booking.Status='$status'),0) as Status from staff LEFT JOIN booking ON staff.StaffID=booking.convenerID GROUP BY staff.StaffID";
                $result1 = mysqli_query($con, $query1);
                while ($row = mysqli_fetch_array($result1)) {
                ?>
                    <tr>
                        <td><?php echo $row['StaffID']; ?></td>
                        <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                        <td><?php echo $row['duration']; ?></td>
                        <td><?php echo $row['Status']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
</body>

</html>