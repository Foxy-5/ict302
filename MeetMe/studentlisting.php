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
        <h1>Student Analytics</h1>
        <hr class="redbar">
        <?php
        $query1 = "Select * from student";
        $result1 = mysqli_query($con, $query1);
        ?>
        <table id="myTable" class="upcomingbooking">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Meeting Hours</th>
                    <th>Meeting Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_array($result1)) {
                    if($row['Meeting_duration'] === NULL){
                        $hours = 0;
                    }
                    else{
                        $hours = $row['Meeting_duration'];
                    }
                    if($row['Appcount'] === NULL){
                        $count = 0;
                    }
                    else{
                        $count = $row['Appcount'];
                    }
                ?>
                    <tr>
                        <td><?php echo $row['StudentID']; ?></td>
                        <td><?php echo $row['First_name'] . " " . $row['Last_name']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $hours; ?></td>
                        <td><?php echo $count; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>