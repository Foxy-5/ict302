<?php
session_start();

include("connection.php");
include("function.php");

$user_data = check_login($con);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['email'];
    $user_name = $_POST['user_name'];
    $id = $user_data['StaffID'];
    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($user_name)){
        $query = "UPDATE staff SET First_name='$firstname', Last_name='$lastname', Email='$email', Username='$user_name' WHERE StaffID= '$id'";

        if(mysqli_query($con,$query)){
            echo '<script>
            alert("Account details was succesfully updated.");
            window.location.href="profile.php";
            </script>';
            die;
        }
        else{
            echo '<script>alert("An error has occured.")</script>';
        }
    }
    else{
        echo '<script>alert("Fields cannot be empty")</script>';
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
    <title>navbar</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">MeetMe</a>
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
        <h1>Edit Your Profile</h1>
        <hr class="redbar">
        <h3>Personal details</h3>
        <br>
        <form class="editprofileform" method="post">
            <ul>
                <li>
                    <label for="first_name">First name</label>
                    <input type="text" name="first_name" id="text" value="<?php echo $user_data['First_name'] ?>">
                </li>
                <li>
                    <label for="last_name">Last name</label>
                    <input type="text" name="last_name" id="text" value="<?php echo $user_data['Last_name'] ?>">
                </li>
                <li>
                    <label for="email">email</label>
                    <input type="email" name="email" id="text" value="<?php echo $user_data['Email'] ?>">
                </li>
                <li>
                    <label for="user_name">username</label>
                    <input id="text" type="text" name="user_name" value="<?php echo $user_data['Username'] ?>">
                </li>
            </ul>
            <br>
            <input id="button" type="submit" value="Update profile"><br><br>
        </form>
    </div>
</body>

</html>