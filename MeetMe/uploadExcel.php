<?php
define('access', TRUE);
session_start();

include("include/connection.php");
include("include/function.php");
include("email.php");

$user_data = check_login($con);

if (isset($_FILES['excel'])) {
    $errors = array();
    $file_name = $_FILES['excel']['name'];
    $file_size = $_FILES['excel']['size'];
    $file_tmp = $_FILES['excel']['tmp_name'];
    $file_type = $_FILES['excel']['type'];
    //$file_ext=strtolower(end(explode('.',$_FILES['excel']['name'])));
    $file_ext = strtolower(pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION));
    $extensions = array("csv");
    $errortype = 0;
    if (in_array($file_ext, $extensions) === false) {
        $errortype = 1;
    }

    if ($file_size > 2097152) {
        $errortype = 2;
    }

    if ($errortype == 0) {
        move_uploaded_file($file_tmp, "uploads/" . $file_name);
        echo '<script>alert("Excel file uploaded.")</script>';
    } else {
        if ($errortype == 1) {
            echo '<script>alert("extension not allowed, please choose a excel file.")</script>';
        } else if ($errortype == 2) {
            echo '<script>alert("File size must be excately 2 MB")</script>';
        }
    }
}

if (isset($_POST["import"])) {
    $file = "uploads/" . $_FILES['excel']['name'];
    $userid = $user_data['StaffID'];
    $date = date("Y-m-d H:i:s");
    if ($file_open = fopen($file, "r")) {
        $query3 = "INSERT into list(UserID,ListDate) values ('$userid','$date')";
        $result3 = mysqli_query($con, $query3);
        if (!$result3) {
            echo '<script>
            alert("an error with insert list has occurred.");
            </script>';
            exit();
        }
        mysqli_commit($con);
        $listquery = "select ListID from list where (UserID = '$userid') AND (ListDate = '$date')";
        $result = mysqli_query($con, $listquery);
        if (!$result) {
            echo '<script>
            alert("an error with insert list has occurred.");
            </script>';
            exit();
        }
        else{
            $fetch = mysqli_fetch_assoc($result);
            $listid = $fetch['ListID'];
        }

        $commitToDatabase = true;
        $listOfAuthKey = array();

        while (($csv = fgetcsv($file_open, 1000, ",")) !== FALSE) {
            //insert row into mysql database

            //check for empty input/invalid input
            $StudentID = $csv[0];
            $Email = $csv[1];
            $Firstname = $csv[2];
            $Lastname = $csv[3];

            //bug: inserting 0 for some duplicate keys
            $query1 = "INSERT IGNORE into student(StudentID,Email,First_name,Last_name) VALUES ('$StudentID','$Email','$Firstname','$Lastname')";
            $result1 = mysqli_query($con, $query1);
            if (!$result1) {
                echo '<script>
            alert("an error with insert student has occurred.");
            </script>';
                $commitToDatabase = false;
                break;
            }

            $uniqueid = $StudentID.$listid.uniqid("user",true);
            $hashedAuthKey = str_split(hash('sha256',$uniqueid),32)[0];

            $query2 = "INSERT into studentlist(ListID,StudentID,Auth_Key) VALUES ('$listid','$StudentID','$hashedAuthKey')";
            $result2 = mysqli_query($con, $query2);
            if (!$result2) {
                echo '<script>
                alert("an error with insert studentlist has occurred.");
                </script>';
                $commitToDatabase = false;
                break;
            }
            $listOfAuthKey[] = $hashedAuthKey;
        }
        if($commitToDatabase){
            mysqli_commit($con);
            for($emailLoop = 0;$emailLoop < sizeof($listOfAuthKey);$emailLoop++){
                sendConfirmationEmail($listOfAuthKey[$emailLoop]);
            }
        }
    }
}
?>
<script>
    function fileValidation() {
        var fileInput =
            document.getElementById('excel');

        var filePath = fileInput.value;

        // Allowing file type
        var allowedExtensions = /(\.csv)$/i;

        if (!allowedExtensions.exec(filePath)) {
            alert('Invalid file type');
            fileInput.value = '';
            return false;
        }
    }
</script>
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
    <title>Upload File | Meetme v2</title>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="home.php"><img src="Image/MU Logo.png" height="80"></a>
        </div>
        <div class="navpaddingright collapse navbar-collapse" id="mynavbar">
            <ul class="nav navbar-nav">
                <li><a href="home.php">Home</a></li>
                <li id="appointment" class="dropdown active"><a href="#">Appointment <span class="caret"></span></a>
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
                <li id="analytics" class="dropdown"><a href="#">Analytics <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="staffanalytics.php">Staff Analytics</a></li>
                        <li><a href="studentlisting.php">Student Analytics</a></li>
                    </ul>
                </li>
                <li><a href="about.php">About</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <h5>Hi <?php echo $user_data['First_name']; ?> <?php echo $user_data['Last_name']; ?>!</h5>
        <h3>Upload Excel File</h3>
        <hr class="redbar">
        <div class="uploadfilecontainer">
            <p>
                <b><em>Instructions</em></b>
                <br>
                1) Open up Microsoft Excel
                <br>
                2) Fill up Student's Details accordingly in a horizontal manner (Number, Email, First Name, Family Name/Last Name)
                <br>
                3) Save file as <b><em>filename.csv</em></b>
                <br>
            </p>
            <h5>
                <p><i>You are allow to download the templete below or refer to the image below for more details</i></p>
                Example: <a href="uploads/example.csv" download="example.csv">example.csv</a>
            </h5>
            <img src="Image/UploadFileExample.png">
            <br>
            <br>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" id="excel" name="excel" onchange="return fileValidation()" /><br>
                <input class="linktobutton" id="import" name="import" type="submit" />
            </form>

        </div>
    </div>
</body>

</html>