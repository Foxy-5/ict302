<?php

session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);
$userid = $user_data['StaffID'];
$bookingid = $_GET['bookingid'];
$query1 = "Select Booking_date, Booking_start, Booking_end, First_name, Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status from booking, student where booking.bookingID = '$bookingid' and booking.StudentID = student.StudentID LIMIT 1";
$result1 = mysqli_query($con, $query1);
$bookingdata = mysqli_fetch_assoc($result1);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $previousid = $_POST['previousid'];
    $newbookingend = date("Y-m-d H:i:s", strtotime($_POST['newbookingend']));
    if ($newbookingend == '0000-00-00 00:00:00') {
        $bookingend = $_POST['newbookingend'];
        $bookingenddate = date("Y-m-d H:i:s", strtotime($bookingend));
    } else {
        $bookingenddate = date("Y-m-d H:i:s", strtotime($bookingdata['Booking_end']));
    }
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $bookingstart = $bookingdata['Booking_start'];
    $bstart = $bookingdata['Booking_start'];
    $bstartd = date_create($bstart);
    $bookingend = date_create($bookingenddate);
    $duration = date_diff($bstartd, $bookingend);
    $elapsed = $duration->days * 24 * 60;
    $elapsed += $duration->h * 60;
    $elapsed += $duration->i;
    // echo "start date" . $bstart ."\r\n";
    // echo "end date" . $bookingenddate . "\r\n";
    //echo $elapsed;
    $query = "UPDATE booking SET Status = '$status', Booking_end = '$bookingenddate', PreviousMeetingID = '$previousid', Comment = '$comment', Duration = '$elapsed' WHERE BookingID= '$bookingid'";

    if (mysqli_query($con, $query)) {
        echo '<script>
        alert("Bookings details was succesfully updated.");
        </script>';
        header("Location: viewbooking.php?bookingid=$bookingid");
        die;
    } else {
        echo '<script>alert("An error has occured.")</script>';
    }
}
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
    <title>Edit booking | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright">
            <ul class="nav navbar-nav">
                <li class="active"><a href="home.php">Home</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointment <span class="caret"></span></a>
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
        <h1>Edit booking</h1>
        <hr class="redbar">
        <form class="editprofileform" method="post">
            <ul>
                <li>
                    <label for="bookingid">BookingID</label>
                    <p><?php echo $bookingdata['BookingID'] ?></p>
                </li>
                <li>
                    <label for="studentname">Student Name</label>
                    <p><?php echo $bookingdata['First_name'] . " " . $bookingdata['Last_name'] ?></p>
                </li>
                <li>
                    <label for="studentid">Student ID</label>
                    <p><?php echo $bookingdata['StudentID'] ?></p>
                </li>
                <li>
                    <label for="bookingdate">Booking date</label>
                    <p><?php echo $bookingdata['Booking_date'] ?></p>
                </li>
                <li>
                    <label for="Bookingstart">Booking Start</label>
                    <p><?php echo $bookingdata['Booking_start'] ?></p>
                </li>
                <li>
                    <label for="duration">Duration</label>
                    <p><?php echo $bookingdata['Duration'] ?></p>
                </li>
                <li>
                    <label for="Bookingend">Booking End</label>
                    <p><?php echo $bookingdata['Booking_end'] ?></p>
                </li>
                <li>
                    <label for="NewBookingend">Change booking end</label>
                    <input type="datetime-local" name="newbookingend" id="text" value="">
                </li>
                <li>
                    <label for="previousid">Previous bookingID</label>
                    <input type="text" name="previousid" id="text" value="<?php echo $bookingdata['PreviousMeetingID'] ?>">
                </li>
                <li>
                    <label for="status">Status</label>
                    <?php $defaultstate = $bookingdata['Status'];?>
                    <select name="status" id="text" selected="selected">
                        <option value='<?php echo $defaultstate ?>' selected='selected'><?php echo $defaultstate ?></option>
                        <option value="confirmed">confirmed</option>
                        <option value="cancelled">cancelled</option>
                        <option value="ended">ended</option>
                    </select>
                </li>
                <li>
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" cols="10" rows="2"><?php echo $bookingdata['Comment'] ?></textarea>
                </li>
            </ul>
            <br>
            <input id="button" type="submit" value="Update booking">
            <input type="button" value="cancel update" onclick="location.href = 'viewbooking.php?bookingid=<?php echo $bookingdata['BookingID']; ?>'"><br>
            <br>
        </form>
    </div>
</body>

</html>