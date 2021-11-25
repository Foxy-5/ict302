<?php
define('access', true);
session_start();

include("include/connection.php");
include("include/function.php");
include("include/email.php");

$user_data = check_login($con);
$userid = $user_data['StaffID'];
$bookingId = $_GET['bookingid'];
$query1 = "Select Booking_date, Booking_start, Booking_end, case WHEN booking.StudentID is NULL THEN NULL ELSE student.First_name end as First_name, case WHEN booking.StudentID is NULL THEN NULL ELSE student.Last_name end as Last_name, BookingID, Comment, PreviousMeetingID , booking.StudentID, Duration, Status from booking LEFT JOIN student ON booking.StudentID = student.StudentID where booking.Auth_key = '$bookingId' LIMIT 1";
$result1 = mysqli_query($con, $query1);
$bookingdata = mysqli_fetch_assoc($result1);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $previousid = $_POST['previousid'];
    $newbookingend = date("Y-m-d H:i:s", strtotime($_POST['newbookingend']));
    //echo $newbookingend;
    if ($newbookingend != '1970-01-01 07:30:00') {
        $bookingend = $_POST['newbookingend'];
        $bookingenddate = date("Y-m-d H:i:s", strtotime($bookingend));
    } else {
        $bookingenddate = date("Y-m-d H:i:s", strtotime($bookingdata['Booking_end']));
    }

    $status = $_POST['status'];
    $acptStatus = array("Cancelled", "Ended", "Not confirmed", "Confirmed");

    if (!in_array($status, $acptStatus)) {
        echo '<script>
            alert("Invalid input");
            window.location.href="viewbooking?bookingid=' . $bookingId . '";
            </script>';
            exit();
    }
    //check if booking set to ended and student is null
    if ($status == "Ended") {
        if ($bookingdata['StudentID'] == NULL) {
            echo '<script>
            alert("Cannot set booking to ended without a student");
            window.location.href="viewbooking?bookingid=' . $bookingId . '";
            </script>';
            exit();
        }

        if ($bookingdata['Status'] == "Cancelled") {
            echo '<script>
                alert("Cannot cancel previously cancelled booking!");
                window.location.href="viewbooking?bookingid=' . $bookingId . '";
                </script>';
            exit();
        }
    }

    else if ($status == "Cancelled") {
        if ($bookingdata['Status'] != "Cancelled" && $bookingdata['Status'] != "Not confirmed") {
            //if the booking is ended, cannot be cancelled
            if ($bookingdata['Status'] == "Ended") {
                echo '<script>
                alert("Cannot end cancelled booking!");
                window.location.href="viewbooking?bookingid=' . $bookingId . '";
                </script>';
                exit();
            }

            //the code will come here if the meeting was previously confirmed

            $stdtEmail = prepEmailStudent(2, $bookingId);
            $staffEmail = prepEmailStaff(2, $bookingId);

            if (!$stdtEmail || !$staffEmail) {
                http_response_code(404);
                header("Location: error404");
                exit();
            }
            sendEmail($stdtEmail);
            sendEmail($staffEmail);
        }
    }
    //not confirmed
    else if ($status == "Not confirmed") {
        if ($bookingdata['Status'] != "Not confirmed") {
            echo '<script>
            alert("Invalid status");
            window.location.href="viewbooking?bookingid=' . $bookingId . '";
            </script>';
            exit();
        }
    }

    if ($previousid > 0) {
        $initial = 0;
    } else {
        $initial = 1;
    }

    $comment = $_POST['comment'];
    $bstart = $bookingdata['Booking_start'];
    $bstartd = date_create($bstart);
    $bookingend = date_create($bookingenddate);
    $duration = date_diff($bstartd, $bookingend);
    $elapsed = $duration->days * 24 * 60;
    $elapsed += $duration->h * 60;
    $elapsed += $duration->i;
    if ($status != "Ended") {
        $elapsed = 0;
    }
    // echo "start date" . $bstart ."\r\n";
    // echo "end date" . $bookingenddate . "\r\n";
    //echo $elapsed;

    $query = "UPDATE booking SET Status = '$status', Booking_end = '$bookingenddate', PreviousMeetingID = '$previousid', Comment = '$comment', Duration = '$elapsed', Initial = '$initial' WHERE Auth_key= '$bookingId'";
    if (mysqli_query($con, $query)) {
        echo '<script>
                alert("Booking details was succesfully updated.");
                window.location.href="viewbooking?bookingid=' . $bookingId . '";
            </script>';
        mysqli_commit($con);
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
        <h1>Edit Booking</h1>
        <hr class="redbar">
        <form method="post">
            <table class="userprofile">
                <tr>
                    <th>Booking ID</th>
                    <th>Student Name</th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td>" . $bookingdata['BookingID'] . "</td>";
                echo "<td>" . $bookingdata['First_name'] . " " . $bookingdata['Last_name'] . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <th>Student ID</th>
                    <th>Booking Date</th>
                </tr>
                <?php
                echo "<tr>";
                echo "<td>" . $bookingdata['StudentID'] . "</td>";
                echo "<td>" . $bookingdata['Booking_date'] . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <th>Booking Start (Date/Time)</th>
                    <th>Duration</th>
                </tr>
                <?php
                $starttime = date("h:i:s a", strtotime($bookingdata['Booking_start']));
                echo "<tr>";
                echo "<td>" . $starttime . "</td>";
                echo "<td>" . $bookingdata['Duration'] . "</td>";
                echo "</tr>";
                ?>
                <tr>
                    <th>Booking End (Date/Time)</th>
                    <th>Change Booking End (Date/Time)</th>
                </tr>
                <?php
                $endtime = date("h:i:s a", strtotime($bookingdata['Booking_end']));
                echo "<tr>";
                echo "<td>" . $endtime . "</td>"; ?>
                <td><input type="datetime-local" name="newbookingend" id="text" value=""></td>
                <?php
                echo "</tr>";
                ?>
                <tr>
                    <th>Previous Booking ID</th>
                    <th>Status</th>
                </tr>
                <?php
                echo "<tr>";
                ?>
                <td><input type="text" name="previousid" id="text" value="<?php echo $bookingdata['PreviousMeetingID'] ?>"></td>
                <td><?php
                    $defaultState = array($bookingdata['Status']);

                    $options = array("Cancelled", "Ended");
                    $selections = array_diff($options, $defaultState);
                    $selections = array_values($selections);
                    ?>

                    <select name="status" id="text" selected="selected">
                        <?php
                        echo "<option value=$defaultState[0] selected=\"selected\">$defaultState[0]</option>";
                        for ($opCount = 0; $opCount < sizeof($selections); $opCount++) {
                            $tempSelect = $selections[$opCount];
                            echo "<option value=$tempSelect>$tempSelect</option>";
                        }
                        ?>

                    </select>
                </td>
                <?php
                echo "</tr>";
                ?>
                <tr>
                    <th colspan="2">Comment</th>
                </tr>
                <?php
                echo "<tr>";
                ?>
                <td colspan="2"><textarea class="commentbox" name="comment" id="comment" cols="10" rows="2"><?php echo $bookingdata['Comment'] ?></textarea></td>
                <?php
                echo "</tr>";
                ?>
            </table>
            <br>
            <div class="containerprofile">
                <input class="linktobutton" type="button" value="Cancel Update" onclick="location.href = 'viewbooking?bookingid=<?php echo $bookingId; ?>'">
                <input class="linktobutton" id="button" type="submit" value="Update Booking">
            </div>
        </form>
    </div>
</body>

</html>
