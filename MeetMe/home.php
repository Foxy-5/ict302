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
    <link rel="stylesheet" href="css/mystyle.css">
    <title>Home | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="http://localhost/meetme/home.php"><img src="Image/MU Logo.png" height="100"></a>
            </div>
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
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="glyphicon glyphicon-user"></span><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">User Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h3>Welcome back  <?php echo $user_data['First_name'];?> <?php echo $user_data['Last_name'];?></h3>
        <br>
        <ol style="list-style-type: lower-alpha">
            <li>
                Use Strong Passwords<br />
                Cyber criminals can conduct dictionary or brute-force attacks
                to guess your password. Always keep your password random by
                ensuring that your password does not have a pattern and is
                unpredictable.
            </li>
            <li>
                Enable MFA When Available<br />
                Multi-Factor Authentication (MFA) provides an additional layer
                of security in countering phishing, fake websites, spamming,
                viruses, worms, Trojans, keystroke loggers and spyware.
            </li>
            <li>
                Maintain Good Password Hygeiene<br />
                Don't provide your passwords or OTP in response to a phone
                call, email or suspicious website as it could be a phishing
                scam
            </li>
        </ol>
    </div>
</body>

</html>