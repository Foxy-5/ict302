<?php
define('access', true);
session_start();
//includes methods from this pages
include("include/connection.php");
include("include/function.php");
//get user session data
$user_data = check_login($con);
//if the form is posted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['email'];
    $user_name = $_POST['user_name'];
    $id = $user_data['StaffID'];
    //check for empty fields
    if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($user_name)){
        //update user data query
        $query = "UPDATE staff SET First_name='$firstname', Last_name='$lastname', Email='$email', Username='$user_name' WHERE StaffID= '$id'";

        if(mysqli_query($con,$query)){
            echo '<script>
            alert("Account details was succesfully updated.");
            window.location.href="profile";
            </script>';
            mysqli_commit($con);
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
    <title>Edit Profile | Meetme v2</title>
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
                <li id="analytics" class="dropdown"><a href="#"><span class="glyphicon glyphicon-tasks"></span> Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics">Staff Analytics</a></li>
                        <li><a href="studentlisting">Student Analytics</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="profile"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h3>Edit Your Profile</h3>
        <hr class="redbar">
        <h4>Personal Details</h4>
        <br>
        <form class="editprofileform" method="post">

            <div class="containerprofile">
                <label for="user_name" class="editprofiletext">Username</label><br>
                <input class="editprofilebox" type="text" name="user_name" value="<?php echo $user_data['Username'] ?>"><br><br>

                <label for="first_name" class="editprofiletext">First Name</label><br>
                <input class="editprofilebox" name="first_name" id="text" value="<?php echo $user_data['First_name'] ?>"><br><br>

                <label for="last_name" class="editprofiletext">Last Name</label><br>
                <input class="editprofilebox" name="last_name" id="text" value="<?php echo $user_data['Last_name'] ?>"><br><br>

                <label for="email" class="editprofiletext">Email</label><br>
                <input class="editprofilebox" name="email" id="text" value="<?php echo $user_data['Email'] ?>"><br><br>

                <input class="linktobutton" type="button" value = "Cancel" onclick="location.href = 'profile'">
                <input class="linktobutton" id="button" type="submit" value="Update Profile">
            </div>
        
        </form>
    </div>
</body>

</html>
