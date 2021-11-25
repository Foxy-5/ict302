<?php
    session_start();
    define('access',true);

    //check for authentication key
    if(!isset($_GET['authkey'])){
        http_response_code(404);
        exit();
    }

    if(strlen($_GET['authkey'])!=32||!preg_match("/^[a-zA-Z0-9]*$/",$_GET['authkey'])){
        http_response_code(404);
        exit(); 
    }

    require_once("include/connection.php");
    require_once("include/email.php");
    require_once("include/info_retrieve.php");

    //finding the booking from the database
    $bkAuthKey = $_GET['authkey'];
    $searchBkQuery = "SELECT * FROM booking WHERE Auth_key = '$bkAuthKey' AND StudentID IS NOT NULL limit 1";

    if(!$bkResult = mysqli_query($con,$searchBkQuery)){
        echo '<script>alert("Cannot connect to system, try again later");
                window.location.href="error404";
              </script>;';
        exit();
    }

    if(!mysqli_num_rows($bkResult)>0){
        echo '<script>alert("Booking not found");
                window.location.href="error404";
              </script>;';
        exit();
    }

    $bkDets = mysqli_fetch_assoc($bkResult);

    //shows error if the booking is either ended or cancelled
    if($bkDets['Status']=='Ended'){
        echo '<script>alert("Booking is over, cannot be cancelled");
                window.location.href="error404";
              </script>';
        exit();
    }
    else if($bkDets['Status']=='Cancelled'){
        echo '<script>alert("Booking is already cancelled, cannot be canelled again");
                window.location.href="error404";
              </script>;';
        exit();
    }

    //getting name of student
    $studentId = $bkDets['StudentID'];
    $stdtDetsQuery = "SELECT First_name, Last_name FROM student WHERE StudentID = '$studentId'";

    if(!$stdtResult = mysqli_query($con,$stdtDetsQuery)){
        echo '<script>
                alert("Cannot connect to system, try again later");
                window.location.href="error404";
              </script>;';
        exit();
    }

    if(!mysqli_num_rows($stdtResult)>0){
        echo '<script>
                alert("Booking not found");
                window.location.href="error404";
              </script>;';
        exit();
    }

    $stdtDets = mysqli_fetch_assoc($stdtResult);

    //saves the booking authentication key if everything retrieves
    $_SESSION['cnclBk'] = $bkAuthKey;


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
    <title>Cancel Meeting | MeetMe v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="#"><img src="Image/MU Logo.png" height="80"></a>
        </div>
    </nav>

    <div class="content">
        <h1>Booking Cancellation</h1>
        <hr class="redbar">
        Booking Details:<br><br>
        <table class="userprofile">
            <tr>
                <th>Booking ID</th>
                <th>Previous Meeting ID</th>
            </tr>
            <?php
                //prints all details of the booking

                //if previous booking is not null, prints the previous booking id
                $prevMeeting = (is_null($bkDets['PreviousMeetingID'])) ? "N/A" : $bkDets['PreviousMeetingID'];
                echo "<tr>";
                echo "  <td>" . $bkDets['BookingID'] . "</td>";
                echo "  <td>" . $prevMeeting . "</td>";
                echo "</tr>";
            ?>
            <tr>
                <th>Student Name</th>
                <th>Student ID</th>
            </tr>
            <?php
                echo "<tr>";
                echo "  <td>" . $stdtDets['First_name'] . " " . $stdtDets['Last_name'] . "</td>";
                echo "  <td>" . $bkDets['StudentID'] . "</td>";
                echo "</tr>";
            ?>
            <tr>
                <th>Booking Date</th>
                <th>Booking Start</th>
            </tr>
            <?php
                //formatting time output 
                $starttime = date("h:i a", strtotime($bkDets['Booking_start']));
                echo "<tr>";
                echo "  <td>" . $bkDets["Booking_date"] . "</td>";
                echo "  <td>" . $starttime . "</td>";
                echo "</tr>";
            ?>
            <tr>
                <th>Booking End</th>
                <th>Duration (Minutes)</th>
            </tr>
            <?php
                $endtime = date("h:i a", strtotime($bkDets['Booking_end']));
                echo "<tr>";
                echo "  <td>" . $endtime . "</td>";
                echo "  <td>" . $bkDets['Duration'] . "</td>";
                echo "</tr>";
            ?>
            <tr>
                <th>Comment</th>
                <th>Status</th>
            </tr>
            <?php
                echo "<tr>";
                echo "  <td>" . $bkDets['Comment'] . "</td>";
                echo "  <td>" . $bkDets['Status'] . "</td>";
                echo "</tr>";
            ?>
        </table>
        <br>
        <div class="containerprofile">
            <a class="linktobutton" href="confirmcancelbooking">Confirm cancellation</a>
        </div>
        <br>
        <br>

    </div>
</body>

</html>